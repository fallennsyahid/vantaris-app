<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\RolesEnum;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'user_id'       => Str::uuid(),
            'nama_lengkap'  => 'Administrator Vantaris',
            'username'      => 'admin',
            'no_telp'       => '081234567890',
            'email'         => 'admin@vantaris.com',
            'password'      => Hash::make('password'), // Ganti sesuai keinginan
            'role'          => RolesEnum::ADMIN,
            'status_blokir' => false,
            'email_verified_at' => now(),
        ]);

        // 2. Akun Petugas
        User::create([
            'user_id'       => Str::uuid(),
            'nama_lengkap'  => 'Petugas Sarpras',
            'username'      => 'petugas',
            'no_telp'       => '081234567891',
            'email'         => 'petugas@vantaris.com',
            'password'      => Hash::make('password'),
            'role'          => RolesEnum::PETUGAS,
            'status_blokir' => false,
            'email_verified_at' => now(),
        ]);

        // 3. Akun Peminjam (Siswa/Guru)
        User::create([
            'user_id'       => Str::uuid(),
            'nama_lengkap'  => 'Umaru Syahid',
            'username'      => 'umaru',
            'no_telp'       => '081234567892',
            'email'         => 'umaru@example.com',
            'password'      => Hash::make('password'),
            'role'          => RolesEnum::PEMINJAM,
            'status_blokir' => false,
            'email_verified_at' => now(),
        ]);
    }
}
