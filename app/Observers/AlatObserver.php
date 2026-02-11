<?php

namespace App\Observers;

use App\Models\Alat;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class AlatObserver
{
    /**
     * Handle the Alat "created" event.
     */
    public function created(Alat $alat): void
    {
        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'create',
            'entitas' => 'alat',
            'keterangan_dan_detail' => json_encode([
                'alat_id' => $alat->alat_id,
                'nama_alat' => $alat->nama_alat,
                'kategori_id' => $alat->kategori_id,
                'stok' => $alat->stok,
                'message' => "Alat '{$alat->nama_alat}' berhasil ditambahkan dengan stok {$alat->stok}"
            ])
        ]);
    }

    /**
     * Handle the Alat "updated" event.
     */
    public function updated(Alat $alat): void
    {
        $changes = $alat->getChanges();
        $original = $alat->getOriginal();

        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'update',
            'entitas' => 'alat',
            'keterangan_dan_detail' => json_encode([
                'alat_id' => $alat->alat_id,
                'nama_alat' => $alat->nama_alat,
                'changes' => $changes,
                'original' => $original,
                'message' => "Alat '{$alat->nama_alat}' berhasil diperbarui"
            ])
        ]);
    }

    /**
     * Handle the Alat "deleted" event.
     */
    public function deleted(Alat $alat): void
    {
        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'delete',
            'entitas' => 'alat',
            'keterangan_dan_detail' => json_encode([
                'alat_id' => $alat->alat_id,
                'nama_alat' => $alat->nama_alat,
                'stok' => $alat->stok,
                'message' => "Alat '{$alat->nama_alat}' berhasil dihapus"
            ])
        ]);
    }

    /**
     * Handle the Alat "restored" event.
     */
    public function restored(Alat $alat): void
    {
        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'restore',
            'entitas' => 'alat',
            'keterangan_dan_detail' => json_encode([
                'alat_id' => $alat->alat_id,
                'nama_alat' => $alat->nama_alat,
                'message' => "Alat '{$alat->nama_alat}' berhasil dipulihkan"
            ])
        ]);
    }

    /**
     * Handle the Alat "force deleted" event.
     */
    public function forceDeleted(Alat $alat): void
    {
        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'force_delete',
            'entitas' => 'alat',
            'keterangan_dan_detail' => json_encode([
                'alat_id' => $alat->alat_id,
                'nama_alat' => $alat->nama_alat,
                'message' => "Alat '{$alat->nama_alat}' dihapus permanen"
            ])
        ]);
    }
}
