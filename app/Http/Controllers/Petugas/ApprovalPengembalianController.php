<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use App\Enums\StatusPeminjaman;
use App\Enums\KondisiAlat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApprovalPengembalianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get peminjaman yang sudah diambil (siap dikembalikan)
        $peminjamanDiambil = Peminjaman::where('status', StatusPeminjaman::DIAMBIL)
            ->with(['peminjam', 'details.alat'])
            ->latest()
            ->get();

        // Get peminjaman yang sudah dikembalikan (untuk history)
        $peminjamanDikembalikan = Peminjaman::whereIn('status', [StatusPeminjaman::KEMBALI, StatusPeminjaman::TERLAMBAT])
            ->with(['peminjam', 'details.alat', 'pengembalian'])
            ->latest()
            ->take(10)
            ->get();

        // Statistics
        $totalMenunggu = Peminjaman::where('status', StatusPeminjaman::DIAMBIL)->count();
        $totalKembali = Peminjaman::where('status', StatusPeminjaman::KEMBALI)->count();
        $totalTerlambat = Peminjaman::where('status', StatusPeminjaman::TERLAMBAT)->count();

        // Hitung jumlah user yang terblokir
        $totalUserBlokir = User::where('status_blokir', true)->count();

        return view('petugas.approve-pengembalian.index', compact(
            'peminjamanDiambil',
            'peminjamanDikembalikan',
            'totalMenunggu',
            'totalKembali',
            'totalTerlambat',
            'totalUserBlokir'
        ));
    }

    /**
     * Scan QR Code untuk proses pengembalian
     */
    public function scanProcess(Request $request)
    {
        try {
            $qrToken = $request->input('qr_token');

            // Cari peminjaman berdasarkan QR token
            $peminjaman = Peminjaman::where('qr_token', $qrToken)
                ->with(['peminjam', 'details.alat'])
                ->first();

            if (!$peminjaman) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak valid atau peminjaman tidak ditemukan!'
                ], 404);
            }

            // Validasi status harus 'diambil'
            if ($peminjaman->status !== StatusPeminjaman::DIAMBIL) {
                $statusText = ucfirst($peminjaman->status->value);
                return response()->json([
                    'success' => false,
                    'message' => "Peminjaman ini tidak dapat dikembalikan. Status saat ini: {$statusText}"
                ], 400);
            }

            // Return data peminjaman untuk ditampilkan di modal konfirmasi
            return response()->json([
                'success' => true,
                'message' => 'QR Code valid! Silakan lanjutkan proses pengembalian.',
                'data' => [
                    'id' => $peminjaman->peminjaman_id,
                    'kode' => $peminjaman->kode_peminjaman,
                    'peminjam' => $peminjaman->peminjam->nama_lengkap ?? $peminjaman->peminjam->name,
                    'tanggal_pinjam' => $peminjaman->tanggal_pengajuan->format('d/m/Y'),
                    'tanggal_rencana' => $peminjaman->tanggal_pengembalian_rencana->format('d/m/Y'),
                    'alat' => $peminjaman->details->map(function ($detail) {
                        return [
                            'nama' => $detail->alat->nama_alat,
                            'jumlah' => $detail->jumlah
                        ];
                    }),
                    // Hitung apakah terlambat
                    'is_late' => Carbon::now()->greaterThan($peminjaman->tanggal_pengembalian_rencana),
                    'days_late' => max(0, Carbon::now()->diffInDays($peminjaman->tanggal_pengembalian_rencana, false))
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process return (actual return with kondisi)
     */
    public function processReturn(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjamans,id',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'catatan' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::with(['peminjam', 'details.alat'])
                ->where('peminjaman_id', $request->peminjaman_id)
                ->firstOrFail();

            // Validasi status
            if ($peminjaman->status !== StatusPeminjaman::DIAMBIL) {
                return response()->json([
                    'success' => false,
                    'message' => 'Peminjaman ini tidak dapat dikembalikan!'
                ], 400);
            }

            $tanggalKembaliSebenarnya = Carbon::now();
            $tanggalRencana = $peminjaman->tanggal_pengembalian_rencana;

            // Hitung keterlambatan
            $isLate = $tanggalKembaliSebenarnya->greaterThan($tanggalRencana);
            $daysLate = 0;

            if ($isLate) {
                $daysLate = $tanggalKembaliSebenarnya->diffInDays($tanggalRencana);
            }

            // Update status peminjaman
            $peminjaman->status = $isLate ? StatusPeminjaman::TERLAMBAT : StatusPeminjaman::KEMBALI;
            $peminjaman->save();

            // Buat record pengembalian
            $pengembalian = Pengembalian::create([
                'peminjaman_id' => $peminjaman->peminjaman_id,
                'tanggal_kembali_sebenarnya' => $tanggalKembaliSebenarnya,
                'kondisi' => KondisiAlat::from($request->kondisi),
                'catatan' => $request->catatan,
                'denda' => 0, // Bisa disesuaikan dengan logika denda
            ]);

            // Kembalikan stok alat
            foreach ($peminjaman->details as $detail) {
                $detail->alat->increment('stok', $detail->jumlah);
            }

            // Auto-blokir jika terlambat
            if ($isLate && $daysLate > 0) {
                $user = $peminjaman->peminjam;
                $user->status_blokir = true;
                // Durasi blokir = sekarang + jumlah hari keterlambatan (minimal 1 hari)
                $user->durasi_blokir = Carbon::now()->addDays($daysLate);
                $user->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $isLate
                    ? "Pengembalian berhasil! User diblokir selama {$daysLate} hari karena terlambat."
                    : 'Pengembalian berhasil diproses!',
                'data' => [
                    'is_late' => $isLate,
                    'days_late' => $daysLate,
                    'kode' => $peminjaman->kode_peminjaman
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $peminjaman = Peminjaman::with(['peminjam', 'details.alat.kategori', 'pengembalian'])
            ->where('peminjaman_id', $id)
            ->firstOrFail();

        return view('petugas.approve-pengembalian.show', compact('peminjaman'));
    }
}
