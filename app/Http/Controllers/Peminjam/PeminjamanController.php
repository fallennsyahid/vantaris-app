<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\Alat;
use App\Enums\StatusPeminjaman;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::user()->user_id;

        $peminjamans = Peminjaman::where('user_id', $userId)
            ->with(['details.alat', 'pemberi_izin'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung statistik
        $totalPengajuan = $peminjamans->count();
        $pengajuanDisetujui = $peminjamans->where('status', StatusPeminjaman::DISETUJUI->value)->count();
        $pengajuanPending = $peminjamans->where('status', StatusPeminjaman::PENDING->value)->count();
        $pengajuanDitolak = $peminjamans->where('status', StatusPeminjaman::DITOLAK->value)->count();

        // Ambil semua alat untuk form
        $alats = Alat::with('kategori')->where('stok', '>', 0)->get();

        return view('peminjam.peminjaman.index', compact(
            'peminjamans',
            'totalPengajuan',
            'pengajuanDisetujui',
            'pengajuanPending',
            'pengajuanDitolak',
            'alats'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_pengambilan_rencana' => 'required|date|after:today',
            'tanggal_pengembalian_rencana' => 'required|date|after:tanggal_pengambilan_rencana',
            'alasan_meminjam' => 'required|string|min:10',
            'alat' => 'required|array|min:1',
            'alat.*.alat_id' => 'required|exists:alats,alat_id',
            'alat.*.jumlah' => 'required|integer|min:1'
        ], [
            'tanggal_pengambilan_rencana.required' => 'Tanggal pengambilan harus diisi',
            'tanggal_pengambilan_rencana.after' => 'Tanggal pengambilan harus setelah hari ini',
            'tanggal_pengembalian_rencana.required' => 'Tanggal pengembalian harus diisi',
            'tanggal_pengembalian_rencana.after' => 'Tanggal pengembalian harus setelah tanggal pengambilan',
            'alasan_meminjam.required' => 'Alasan meminjam harus diisi',
            'alasan_meminjam.min' => 'Alasan meminjam minimal 10 karakter',
            'alat.required' => 'Minimal pilih 1 alat',
            'alat.*.jumlah.min' => 'Jumlah minimal 1'
        ]);

        // Check if user is blocked
        if (Auth::user()->status_blokir) {
            $durasiBlokir = Auth::user()->durasi_blokir
                ? \Carbon\Carbon::parse(Auth::user()->durasi_blokir)->format('d M Y, H:i')
                : 'tidak ditentukan';

            return redirect()->back()
                ->with('error', "Akun Anda sedang ditangguhkan hingga {$durasiBlokir} karena keterlambatan pengembalian. Anda tidak dapat mengajukan peminjaman baru.")
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Cek ketersediaan stok
            foreach ($request->alat as $item) {
                $alat = Alat::where('alat_id', $item['alat_id'])->first();
                if ($alat->stok < $item['jumlah']) {
                    return redirect()->back()
                        ->with('error', "Stok {$alat->nama_alat} tidak mencukupi. Stok tersedia: {$alat->stok}")
                        ->withInput();
                }
            }

            // Buat peminjaman
            $peminjaman = Peminjaman::create([
                'user_id' => Auth::user()->user_id,
                'tanggal_pengambilan_rencana' => $request->tanggal_pengambilan_rencana,
                'tanggal_pengembalian_rencana' => $request->tanggal_pengembalian_rencana,
                'alasan_meminjam' => $request->alasan_meminjam,
                'status' => StatusPeminjaman::PENDING->value
            ]);

            // Buat detail peminjaman
            foreach ($request->alat as $item) {
                PeminjamanDetail::create([
                    'peminjaman_id' => $peminjaman->peminjaman_id,
                    'alat_id' => $item['alat_id'],
                    'jumlah' => $item['jumlah']
                ]);
            }

            DB::commit();
            return redirect()->route('peminjam.peminjaman.index')
                ->with('success', 'Pengajuan peminjaman berhasil dibuat. Menunggu persetujuan petugas.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource with QR Code.
     */
    public function show($id)
    {
        $peminjaman = Peminjaman::where('peminjaman_id', $id)
            ->with(['details.alat.kategori', 'peminjam', 'pemberi_izin'])
            ->firstOrFail();

        // Pastikan user hanya bisa melihat peminjaman sendiri
        if ($peminjaman->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized access');
        }

        // Generate QR Code jika sudah disetujui dan belum ada qr_token
        if ($peminjaman->status === StatusPeminjaman::DISETUJUI->value && !$peminjaman->qr_token) {
            $peminjaman->qr_token = \Illuminate\Support\Str::random(32);
            $peminjaman->save();
        }

        // Generate QR Code SVG
        $qrCode = null;
        if ($peminjaman->qr_token) {
            $qrCode = base64_encode(QrCode::format('svg')
                ->size(300)
                ->errorCorrection('H')
                ->generate($peminjaman->qr_token));
        }

        return view('peminjam.peminjaman.show', compact('peminjaman', 'qrCode'));
    }

    /**
     * Remove the specified resource from storage (cancel peminjaman).
     */
    public function destroy($id)
    {
        $peminjaman = Peminjaman::where('peminjaman_id', $id)->firstOrFail();

        // Pastikan user hanya bisa cancel peminjaman sendiri
        if ($peminjaman->user_id !== Auth::user()->user_id) {
            abort(403, 'Unauthorized access');
        }

        // Hanya bisa cancel jika masih pending
        if ($peminjaman->status !== StatusPeminjaman::PENDING->value) {
            return redirect()->back()
                ->with('error', 'Hanya peminjaman dengan status pending yang dapat dibatalkan.');
        }

        $peminjaman->delete();

        return redirect()->route('peminjam.peminjaman.index')
            ->with('success', 'Pengajuan peminjaman berhasil dibatalkan.');
    }
}
