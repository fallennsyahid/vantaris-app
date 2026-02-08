<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pengembalian extends Model
{
    protected $fillable = [
        'pengembalian_id',
        'peminjaman_id',
        'received_by',
        'tanggal_kembali_sebenarnya',
        'kondisi',
        'catatan',
        'is_tanggung_jawab_selesai',
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
}
