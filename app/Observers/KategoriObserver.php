<?php

namespace App\Observers;

use App\Models\Kategori;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class KategoriObserver
{
    /**
     * Handle the Kategori "created" event.
     */
    public function created(Kategori $kategori): void
    {
        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'create',
            'entitas' => 'kategori',
            'keterangan_dan_detail' => json_encode([
                'kategori_id' => $kategori->kategori_id,
                'nama_kategori' => $kategori->nama_kategori,
                'slug' => $kategori->slug,
                'status' => $kategori->status,
                'message' => "Kategori '{$kategori->nama_kategori}' berhasil ditambahkan"
            ])
        ]);
    }

    /**
     * Handle the Kategori "updated" event.
     */
    public function updated(Kategori $kategori): void
    {
        $changes = $kategori->getChanges();
        $original = $kategori->getOriginal();

        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'update',
            'entitas' => 'kategori',
            'keterangan_dan_detail' => json_encode([
                'kategori_id' => $kategori->kategori_id,
                'nama_kategori' => $kategori->nama_kategori,
                'changes' => $changes,
                'original' => $original,
                'message' => "Kategori '{$kategori->nama_kategori}' berhasil diperbarui"
            ])
        ]);
    }

    /**
     * Handle the Kategori "deleted" event.
     */
    public function deleted(Kategori $kategori): void
    {
        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'delete',
            'entitas' => 'kategori',
            'keterangan_dan_detail' => json_encode([
                'kategori_id' => $kategori->kategori_id,
                'nama_kategori' => $kategori->nama_kategori,
                'message' => "Kategori '{$kategori->nama_kategori}' berhasil dihapus"
            ])
        ]);
    }

    /**
     * Handle the Kategori "restored" event.
     */
    public function restored(Kategori $kategori): void
    {
        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'restore',
            'entitas' => 'kategori',
            'keterangan_dan_detail' => json_encode([
                'kategori_id' => $kategori->kategori_id,
                'nama_kategori' => $kategori->nama_kategori,
                'message' => "Kategori '{$kategori->nama_kategori}' berhasil dipulihkan"
            ])
        ]);
    }

    /**
     * Handle the Kategori "force deleted" event.
     */
    public function forceDeleted(Kategori $kategori): void
    {
        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'force_delete',
            'entitas' => 'kategori',
            'keterangan_dan_detail' => json_encode([
                'kategori_id' => $kategori->kategori_id,
                'nama_kategori' => $kategori->nama_kategori,
                'message' => "Kategori '{$kategori->nama_kategori}' dihapus permanen"
            ])
        ]);
    }
}
