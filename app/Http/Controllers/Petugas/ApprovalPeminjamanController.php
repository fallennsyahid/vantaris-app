<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Alat;
use App\Enums\StatusPeminjaman;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ApprovalPeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua peminjaman dengan relasi
        $peminjamans = Peminjaman::with(['details.alat', 'peminjam', 'pemberi_izin'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung statistik
        $totalPengajuan = $peminjamans->count();
        $pengajuanDisetujui = $peminjamans->where('status', StatusPeminjaman::DISETUJUI->value)->count();
        $pengajuanPending = $peminjamans->where('status', StatusPeminjaman::PENDING->value)->count();
        $pengajuanDitolak = $peminjamans->where('status', StatusPeminjaman::DITOLAK->value)->count();

        return view('petugas.approve-peminjaman.index', compact(
            'peminjamans',
            'totalPengajuan',
            'pengajuanDisetujui',
            'pengajuanPending',
            'pengajuanDitolak'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $peminjaman = Peminjaman::where('peminjaman_id', $id)
            ->with(['details.alat.kategori', 'peminjam', 'pemberi_izin'])
            ->firstOrFail();

        return view('petugas.approve-peminjaman.show', compact('peminjaman'));
    }

    /**
     * Approve peminjaman.
     */
    public function approve(Request $request, string $id)
    {
        $request->validate([
            'tanggal_pengambilan_sebenarnya' => 'required|date',
            'note' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::where('peminjaman_id', $id)
                ->with('details')
                ->firstOrFail();

            // Cek apakah masih pending
            if ($peminjaman->status !== StatusPeminjaman::PENDING->value) {
                return redirect()->back()
                    ->with('error', 'Peminjaman sudah diproses sebelumnya.');
            }

            // Cek ketersediaan stok
            foreach ($peminjaman->details as $detail) {
                $alat = Alat::where('alat_id', $detail->alat_id)->first();
                if ($alat->stok < $detail->jumlah) {
                    return redirect()->back()
                        ->with('error', "Stok {$alat->nama_alat} tidak mencukupi. Stok tersedia: {$alat->stok}");
                }
            }

            // Update peminjaman
            $peminjaman->update([
                'status' => StatusPeminjaman::DISETUJUI->value,
                'approved_by' => Auth::user()->user_id,
                'tanggal_pengambilan_sebenarnya' => $request->tanggal_pengambilan_sebenarnya,
                'note' => $request->note
            ]);

            // Kurangi stok alat
            foreach ($peminjaman->details as $detail) {
                $alat = Alat::where('alat_id', $detail->alat_id)->first();
                $alat->decrement('stok', $detail->jumlah);
            }

            DB::commit();
            return redirect()->route('petugas.approve-peminjaman.index')
                ->with('success', 'Peminjaman berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject peminjaman.
     */
    public function reject(Request $request, string $id)
    {
        $request->validate([
            'note' => 'required|string|min:10'
        ], [
            'note.required' => 'Alasan penolakan harus diisi',
            'note.min' => 'Alasan penolakan minimal 10 karakter'
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::where('peminjaman_id', $id)->firstOrFail();

            // Cek apakah masih pending
            if ($peminjaman->status !== StatusPeminjaman::PENDING->value) {
                return redirect()->back()
                    ->with('error', 'Peminjaman sudah diproses sebelumnya.');
            }

            // Update peminjaman
            $peminjaman->update([
                'status' => StatusPeminjaman::DITOLAK->value,
                'approved_by' => Auth::user()->user_id,
                'note' => $request->note
            ]);

            DB::commit();
            return redirect()->route('petugas.approve-peminjaman.index')
                ->with('success', 'Peminjaman berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Scan QR Code and process pickup.
     */
    public function scanProcess(Request $request)
    {
        $request->validate([
            'qr_token' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            // Cari peminjaman berdasarkan qr_token
            $peminjaman = Peminjaman::where('qr_token', $request->qr_token)
                ->with(['details.alat', 'peminjam'])
                ->first();

            // Validasi: cek apakah token ada
            if (!$peminjaman) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak valid atau tidak ditemukan.'
                ], 404);
            }

            // Validasi: cek apakah status disetujui
            if ($peminjaman->status !== StatusPeminjaman::DISETUJUI->value) {
                return response()->json([
                    'success' => false,
                    'message' => "Peminjaman dengan status '{$peminjaman->status}' tidak dapat diproses. Status harus 'disetujui'."
                ], 400);
            }

            // Update status menjadi 'diambil' dan set tanggal pengambilan sebenarnya
            $peminjaman->update([
                'status' => StatusPeminjaman::DIAMBIL->value,
                'tanggal_pengambilan_sebenarnya' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil diproses! Alat telah diambil oleh ' . $peminjaman->peminjam->name,
                'data' => [
                    'peminjaman_id' => $peminjaman->peminjaman_id,
                    'peminjam' => $peminjaman->peminjam->name,
                    'status' => $peminjaman->status,
                    'alat' => $peminjaman->details->map(function ($detail) {
                        return $detail->alat->nama_alat . ' (' . $detail->jumlah . 'x)';
                    })->join(', ')
                ]
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
