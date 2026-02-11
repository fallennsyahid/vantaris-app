<?php

namespace App\Observers;

use App\Models\Peminjaman;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class PeminjamanObserver
{
    /**
     * Handle the Peminjaman "created" event.
     */
    public function created(Peminjaman $peminjaman): void
    {
        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'create',
            'entitas' => 'peminjaman',
            'keterangan_dan_detail' => json_encode([
                'peminjaman_id' => $peminjaman->peminjaman_id,
                'kode_peminjaman' => $peminjaman->kode_peminjaman,
                'user_id' => $peminjaman->user_id,
                'nama_peminjam' => $peminjaman->peminjam->nama_lengkap ?? 'Unknown',
                'tanggal_pengajuan' => $peminjaman->tanggal_pengajuan,
                'tanggal_pengambilan_rencana' => $peminjaman->tanggal_pengambilan_rencana,
                'tanggal_pengembalian_rencana' => $peminjaman->tanggal_pengembalian_rencana,
                'status' => $peminjaman->status,
                'alasan_meminjam' => $peminjaman->alasan_meminjam,
                'message' => "Peminjaman baru ({$peminjaman->kode_peminjaman}) diajukan oleh {$peminjaman->peminjam->nama_lengkap}"
            ])
        ]);
    }

    /**
     * Handle the Peminjaman "updated" event.
     */
    public function updated(Peminjaman $peminjaman): void
    {
        $changes = $peminjaman->getChanges();
        $original = $peminjaman->getOriginal();

        // Deteksi perubahan status khusus
        $statusMessage = '';
        if (isset($changes['status'])) {
            $statusMessage = " - Status berubah dari '{$original['status']}' menjadi '{$changes['status']}'";
        }

        if (isset($changes['approved_by'])) {
            $approver = $peminjaman->pemberi_izin->nama_lengkap ?? 'Unknown';
            $statusMessage .= " - Disetujui oleh {$approver}";
        }

        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'update',
            'entitas' => 'peminjaman',
            'keterangan_dan_detail' => json_encode([
                'peminjaman_id' => $peminjaman->peminjaman_id,
                'kode_peminjaman' => $peminjaman->kode_peminjaman,
                'changes' => $changes,
                'original' => $original,
                'message' => "Peminjaman ({$peminjaman->kode_peminjaman}) diperbarui{$statusMessage}"
            ])
        ]);
    }

    /**
     * Handle the Peminjaman "deleted" event.
     */
    public function deleted(Peminjaman $peminjaman): void
    {
        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'delete',
            'entitas' => 'peminjaman',
            'keterangan_dan_detail' => json_encode([
                'peminjaman_id' => $peminjaman->peminjaman_id,
                'kode_peminjaman' => $peminjaman->kode_peminjaman,
                'user_id' => $peminjaman->user_id,
                'nama_peminjam' => $peminjaman->peminjam->nama_lengkap ?? 'Unknown',
                'message' => "Peminjaman ({$peminjaman->kode_peminjaman}) dihapus"
            ])
        ]);
    }

    /**
     * Handle the Peminjaman "restored" event.
     */
    public function restored(Peminjaman $peminjaman): void
    {
        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'restore',
            'entitas' => 'peminjaman',
            'keterangan_dan_detail' => json_encode([
                'peminjaman_id' => $peminjaman->peminjaman_id,
                'kode_peminjaman' => $peminjaman->kode_peminjaman,
                'message' => "Peminjaman ({$peminjaman->kode_peminjaman}) dipulihkan"
            ])
        ]);
    }

    /**
     * Handle the Peminjaman "force deleted" event.
     */
    public function forceDeleted(Peminjaman $peminjaman): void
    {
        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'force_delete',
            'entitas' => 'peminjaman',
            'keterangan_dan_detail' => json_encode([
                'peminjaman_id' => $peminjaman->peminjaman_id,
                'kode_peminjaman' => $peminjaman->kode_peminjaman,
                'message' => "Peminjaman ({$peminjaman->kode_peminjaman}) dihapus permanen"
            ])
        ]);
    }
}
