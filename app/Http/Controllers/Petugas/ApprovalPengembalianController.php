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
use Illuminate\Support\Facades\Auth;
use App\Exports\PengembalianExport;
use Maatwebsite\Excel\Facades\Excel;

class ApprovalPengembalianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get peminjaman yang sudah diambil (siap dikembalikan)
        $peminjamanDiambil = Peminjaman::where('status', StatusPeminjaman::DIAMBIL->value)
            ->with(['peminjam', 'details.alat'])
            ->latest()
            ->get();

        // Get peminjaman yang sudah dikembalikan (untuk history)
        $peminjamanDikembalikan = Peminjaman::whereIn('status', [StatusPeminjaman::KEMBALI->value, StatusPeminjaman::TERLAMBAT->value])
            ->with(['peminjam', 'details.alat', 'pengembalian'])
            ->latest()
            ->take(10)
            ->get();

        // Statistics
        $totalMenunggu = Peminjaman::where('status', StatusPeminjaman::DIAMBIL->value)->count();
        $totalKembali = Peminjaman::where('status', StatusPeminjaman::KEMBALI->value)->count();
        $totalTerlambat = Peminjaman::where('status', StatusPeminjaman::TERLAMBAT->value)->count();

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
            $currentStatus = is_object($peminjaman->status) ? $peminjaman->status->value : $peminjaman->status;
            if ($currentStatus !== StatusPeminjaman::DIAMBIL->value) {
                $statusText = ucfirst(str_replace('_', ' ', $currentStatus));
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
            'peminjaman_id' => 'required|exists:peminjamans,peminjaman_id',
            'kondisi' => 'required|in:baik,rusak,tidak_lengkap,hilang',
            'catatan' => 'required_unless:kondisi,baik|nullable|string|max:500'
        ], [
            'catatan.required_unless' => 'Catatan wajib diisi jika kondisi alat bukan "Baik"'
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::with(['peminjam', 'details.alat'])
                ->where('peminjaman_id', $request->peminjaman_id)
                ->firstOrFail();

            // Validasi status
            $currentStatus = is_object($peminjaman->status) ? $peminjaman->status->value : $peminjaman->status;
            if ($currentStatus !== StatusPeminjaman::DIAMBIL->value) {
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
            $peminjaman->status = $isLate ? StatusPeminjaman::TERLAMBAT->value : StatusPeminjaman::KEMBALI->value;
            $peminjaman->save();

            // Buat record pengembalian
            $pengembalian = Pengembalian::create([
                'peminjaman_id' => $peminjaman->peminjaman_id,
                // 'received_by' => auth()->user()->user_id,
                'received_by' => Auth::user()->user_id,
                'tanggal_pengembalian_sebenarnya' => $tanggalKembaliSebenarnya,
                'kondisi' => KondisiAlat::from($request->kondisi),
                'catatan' => $request->catatan,
                'is_tanggung_jawab_selesai' => $request->kondisi === 'baik' ? true : false,
            ]);

            // Kembalikan stok alat HANYA jika kondisi baik atau rusak
            // Jika hilang atau tidak_lengkap, stok TIDAK dikembalikan
            if (in_array($request->kondisi, ['baik', 'rusak'])) {
                foreach ($peminjaman->details as $detail) {
                    $detail->alat->increment('stok', $detail->jumlah);
                }
            }

            // Auto-blokir user
            $user = $peminjaman->peminjam;
            $blokirReason = '';
            $blokirDays = 0;

            // Blokir jika terlambat
            if ($isLate && $daysLate > 0) {
                $blokirDays = $daysLate;
                $blokirReason = "terlambat {$daysLate} hari";
            }

            // Blokir jika kondisi alat bukan baik
            if ($request->kondisi !== 'baik') {
                $kondisiBlokir = match ($request->kondisi) {
                    'rusak' => 7,           // 7 hari untuk rusak
                    'tidak_lengkap' => 14,  // 14 hari untuk tidak lengkap
                    'hilang' => 30,         // 30 hari untuk hilang
                    default => 0,
                };

                // Jika sudah terlambat, tambahkan hari blokirnya
                if ($blokirDays > 0) {
                    $blokirDays += $kondisiBlokir;
                    $blokirReason .= " dan kondisi alat {$request->kondisi}";
                } else {
                    $blokirDays = $kondisiBlokir;
                    $blokirReason = "kondisi alat {$request->kondisi}";
                }
            }

            // Terapkan blokir jika ada alasan
            if ($blokirDays > 0) {
                $user->status_blokir = true;
                $user->durasi_blokir = Carbon::now()->addDays($blokirDays);
                $user->save();
            }

            DB::commit();

            // Generate message
            $message = 'Pengembalian berhasil diproses!';
            if ($blokirDays > 0) {
                $message = "Pengembalian berhasil! User diblokir selama {$blokirDays} hari karena {$blokirReason}.";
            }

            // Tambahkan info stok jika tidak dikembalikan
            if (in_array($request->kondisi, ['hilang', 'tidak_lengkap'])) {
                $message .= " Stok tidak dikembalikan karena kondisi alat {$request->kondisi}.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'is_late' => $isLate,
                    'days_late' => $daysLate,
                    'kode' => $peminjaman->kode_peminjaman,
                    'is_blocked' => $blokirDays > 0,
                    'block_days' => $blokirDays,
                    'stock_returned' => in_array($request->kondisi, ['baik', 'rusak'])
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

    /**
     * Export data pengembalian to Excel
     */
    public function export(Request $request)
    {
        // Validate date inputs if provided
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Generate filename with date range if provided
        $filename = 'data-pengembalian';
        if ($startDate && $endDate) {
            $filename .= '-' . date('d-m-Y', strtotime($startDate)) . '-sd-' . date('d-m-Y', strtotime($endDate));
        } else {
            $filename .= '-' . date('d-m-Y');
        }
        $filename .= '.xlsx';

        return Excel::download(new PengembalianExport($startDate, $endDate), $filename);
    }
}
