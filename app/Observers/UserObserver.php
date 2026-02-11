<?php

namespace App\Observers;

use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'create',
            'entitas' => 'user',
            'keterangan_dan_detail' => json_encode([
                'user_id' => $user->user_id,
                'nama_lengkap' => $user->nama_lengkap,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role?->value ?? $user->role,
                'message' => "User baru '{$user->nama_lengkap}' berhasil ditambahkan dengan role {$user->role?->value}"
            ])
        ]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $changes = $user->getChanges();
        $original = $user->getOriginal();

        // Hapus password dari log untuk keamanan
        unset($changes['password'], $original['password'], $changes['remember_token'], $original['remember_token']);

        $statusMessage = '';
        if (isset($changes['status_blokir'])) {
            $statusMessage = $changes['status_blokir'] ? ' - User diblokir' : ' - User dibuka blokirnya';
        }

        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'update',
            'entitas' => 'user',
            'keterangan_dan_detail' => json_encode([
                'user_id' => $user->user_id,
                'nama_lengkap' => $user->nama_lengkap,
                'username' => $user->username,
                'changes' => $changes,
                'original' => $original,
                'message' => "User '{$user->nama_lengkap}' diperbarui{$statusMessage}"
            ])
        ]);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'delete',
            'entitas' => 'user',
            'keterangan_dan_detail' => json_encode([
                'user_id' => $user->user_id,
                'nama_lengkap' => $user->nama_lengkap,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role?->value ?? $user->role,
                'message' => "User '{$user->nama_lengkap}' berhasil dihapus"
            ])
        ]);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'restore',
            'entitas' => 'user',
            'keterangan_dan_detail' => json_encode([
                'user_id' => $user->user_id,
                'nama_lengkap' => $user->nama_lengkap,
                'username' => $user->username,
                'message' => "User '{$user->nama_lengkap}' berhasil dipulihkan"
            ])
        ]);
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        LogAktivitas::create([
            'user_id' => Auth::user()?->user_id,
            'aksi' => 'force_delete',
            'entitas' => 'user',
            'keterangan_dan_detail' => json_encode([
                'user_id' => $user->user_id,
                'nama_lengkap' => $user->nama_lengkap,
                'username' => $user->username,
                'message' => "User '{$user->nama_lengkap}' dihapus permanen"
            ])
        ]);
    }
}
