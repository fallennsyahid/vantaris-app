<?php

namespace App\Models;

use App\Enums\KondisiAlat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pengembalian extends Model
{
    protected $table = 'pengembalians';

    protected $primaryKey = 'id';

    protected $fillable = [
        'pengembalian_id',
        'peminjaman_id',
        'received_by',
        'tanggal_pengembalian_sebenarnya',
        'kondisi',
        'catatan',
        'is_tanggung_jawab_selesai',
    ];

    protected $casts = [
        'tanggal_pengembalian_sebenarnya' => 'datetime',
        'is_tanggung_jawab_selesai' => 'boolean',
        'kondisi' => KondisiAlat::class,
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($model) => $model->pengembalian_id = (string) Str::uuid());
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'received_by', 'user_id');
    }

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id', 'peminjaman_id');
    }

    // Relasi ke Petugas yang menerima
    public function penerima()
    {
        return $this->belongsTo(User::class, 'received_by', 'user_id');
    }
}
