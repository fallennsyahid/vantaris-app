<?php

namespace App\Observers;

use App\Models\Pengembalian;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class PengembalianObserver
{
    /**
     * Handle the Pengembalian "created" event.
     */
    public function created(Pengembalian $pengembalian): void
    {
        $peminjaman = $pengembalian->peminjaman;

        $kondisiValue = is_object($pengembalian->kondisi) ? $pengembalian->kondisi->value : $pengembalian->kondisi;

        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'create',
            'entitas' => 'pengembalian',
            'keterangan_dan_detail' => json_encode([
                'pengembalian_id' => $pengembalian->pengembalian_id,
                'peminjaman_id' => $pengembalian->peminjaman_id,
                'kode_peminjaman' => $peminjaman->kode_peminjaman ?? null,
                'received_by' => $pengembalian->received_by,
                'nama_penerima' => $pengembalian->penerima->nama_lengkap ?? 'Unknown',
                'tanggal_pengembalian_sebenarnya' => $pengembalian->tanggal_pengembalian_sebenarnya,
                'kondisi' => $kondisiValue,
                'catatan' => $pengembalian->catatan,
                'message' => "Pengembalian untuk peminjaman ({$peminjaman->kode_peminjaman}) berhasil dicatat dengan kondisi: {$kondisiValue}"
            ])
        ]);
    }

    /**
     * Handle the Pengembalian "updated" event.
     */
    public function updated(Pengembalian $pengembalian): void
    {
        $changes = $pengembalian->getChanges();
        $original = $pengembalian->getOriginal();
        $peminjaman = $pengembalian->peminjaman;

        // Convert enum to value if exists in changes
        if (isset($changes['kondisi']) && is_object($changes['kondisi'])) {
            $changes['kondisi'] = $changes['kondisi']->value;
        }
        if (isset($original['kondisi']) && is_object($original['kondisi'])) {
            $original['kondisi'] = $original['kondisi']->value;
        }

        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'update',
            'entitas' => 'pengembalian',
            'keterangan_dan_detail' => json_encode([
                'pengembalian_id' => $pengembalian->pengembalian_id,
                'peminjaman_id' => $pengembalian->peminjaman_id,
                'kode_peminjaman' => $peminjaman->kode_peminjaman ?? null,
                'changes' => $changes,
                'original' => $original,
                'message' => "Pengembalian untuk peminjaman ({$peminjaman->kode_peminjaman}) diperbarui"
            ])
        ]);
    }

    /**
     * Handle the Pengembalian "deleted" event.
     */
    public function deleted(Pengembalian $pengembalian): void
    {
        $peminjaman = $pengembalian->peminjaman;

        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'delete',
            'entitas' => 'pengembalian',
            'keterangan_dan_detail' => json_encode([
                'pengembalian_id' => $pengembalian->pengembalian_id,
                'peminjaman_id' => $pengembalian->peminjaman_id,
                'kode_peminjaman' => $peminjaman->kode_peminjaman ?? null,
                'message' => "Pengembalian untuk peminjaman ({$peminjaman->kode_peminjaman}) dihapus"
            ])
        ]);
    }

    /**
     * Handle the Pengembalian "restored" event.
     */
    public function restored(Pengembalian $pengembalian): void
    {
        $peminjaman = $pengembalian->peminjaman;

        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'restore',
            'entitas' => 'pengembalian',
            'keterangan_dan_detail' => json_encode([
                'pengembalian_id' => $pengembalian->pengembalian_id,
                'peminjaman_id' => $pengembalian->peminjaman_id,
                'kode_peminjaman' => $peminjaman->kode_peminjaman ?? null,
                'message' => "Pengembalian untuk peminjaman ({$peminjaman->kode_peminjaman}) dipulihkan"
            ])
        ]);
    }

    /**
     * Handle the Pengembalian "force deleted" event.
     */
    public function forceDeleted(Pengembalian $pengembalian): void
    {
        $peminjaman = $pengembalian->peminjaman;

        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'force_delete',
            'entitas' => 'pengembalian',
            'keterangan_dan_detail' => json_encode([
                'pengembalian_id' => $pengembalian->pengembalian_id,
                'peminjaman_id' => $pengembalian->peminjaman_id,
                'kode_peminjaman' => $peminjaman->kode_peminjaman ?? null,
                'message' => "Pengembalian untuk peminjaman ({$peminjaman->kode_peminjaman}) dihapus permanen"
            ])
        ]);
    }
}
