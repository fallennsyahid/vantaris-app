<?php

use App\Enums\RolesEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AlatController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\UserPetugasController;
use App\Http\Controllers\Admin\LogAktifitasController;
use App\Http\Controllers\Admin\PengembalianController;
use App\Http\Controllers\Admin\UserPeminjamController;
use App\Http\Controllers\Petugas\ApprovalPeminjamanController;
use App\Http\Controllers\Petugas\ApprovalPengembalianController;
use App\Http\Controllers\Petugas\AlatController as PetugasAlatController;
use App\Http\Controllers\Peminjam\AlatController as PeminjamAlatController;
use App\Http\Controllers\Peminjam\PeminjamanController as PeminjamPeminjamanController;
use App\Http\Controllers\Peminjam\PengembalianController as PeminjamPengembalianController;

Route::get('/', function () {
    return view('auth.login');
});

// Redirect /dashboard ke dashboard sesuai role
Route::get('/dashboard', function () {
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login');
    }

    return match ($user->role->value) {
        RolesEnum::ADMIN->value => redirect()->route('admin.dashboard'),
        RolesEnum::PETUGAS->value => redirect()->route('petugas.dashboard'),
        RolesEnum::PEMINJAM->value => redirect()->route('peminjam.dashboard'),
        default => redirect()->route('login'),
    };
})->middleware(['auth'])->name('dashboard');

// Routes untuk Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('/alat', AlatController::class);
    Route::resource('/kategori', KategoriController::class);
    Route::patch('/kategori/{kategori}/toggle-status', [KategoriController::class, 'toggleStatus'])->name('kategori.toggleStatus');
    Route::resource('/user-petugas', UserPetugasController::class);
    Route::patch('/user-peminjam/{id}/toggle-status', [UserPeminjamController::class, 'toggleStatus'])->name('user-peminjam.toggleStatus');
    Route::resource('/user-peminjam', UserPeminjamController::class);
    Route::patch('/user-petugas/{id}/toggle-status', [UserPetugasController::class, 'toggleStatus'])->name('user-petugas.toggleStatus');
    Route::resource('/peminjaman', PeminjamanController::class);
    Route::resource('/pengembalian', PengembalianController::class);
    Route::resource('/log', LogAktifitasController::class);

    // Tambahkan routes admin lainnya di sini
});

// Routes untuk Petugas
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', function () {
        return view('petugas.dashboard');
    })->name('dashboard');

    Route::resource('/alat', PetugasAlatController::class);
    Route::resource('/approve-peminjaman', ApprovalPeminjamanController::class);
    Route::post('/approve-peminjaman/{id}/approve', [ApprovalPeminjamanController::class, 'approve'])->name('approve-peminjaman.approve');
    Route::post('/approve-peminjaman/{id}/reject', [ApprovalPeminjamanController::class, 'reject'])->name('approve-peminjaman.reject');
    Route::post('/peminjaman/scan-proses', [ApprovalPeminjamanController::class, 'scanProcess'])->name('peminjaman.scan-proses');
    Route::get('/peminjaman/export', [ApprovalPeminjamanController::class, 'export'])->name('peminjaman.export');
    Route::resource('/approve-pengembalian', ApprovalPengembalianController::class);
    Route::post('/pengembalian/scan-proses', [ApprovalPengembalianController::class, 'scanProcess'])->name('pengembalian.scan-proses');
    Route::post('/pengembalian/proses', [ApprovalPengembalianController::class, 'processReturn'])->name('pengembalian.proses');
    Route::get('/pengembalian/export', [ApprovalPengembalianController::class, 'export'])->name('pengembalian.export');

    // Tambahkan routes petugas lainnya di sini
});

// Routes untuk Peminjam
Route::middleware(['auth', 'role:peminjam'])->prefix('peminjam')->name('peminjam.')->group(function () {
    Route::get('/dashboard', function () {
        return view('peminjam.dashboard');
    })->name('dashboard');

    Route::resource('/alat', PeminjamAlatController::class);
    Route::resource('/peminjaman', PeminjamPeminjamanController::class);
    Route::resource('/pengembalian', PeminjamPengembalianController::class);

    // Tambahkan routes peminjam lainnya di sini
});

// Profile routes (accessible by all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
