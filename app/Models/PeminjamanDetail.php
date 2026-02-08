<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PeminjamanDetail extends Model
{
    protected $fillable = ['peminjaman_detail_id', 'peminjaman_id', 'alat_id', 'jumlah'];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($model) => $model->peminjaman_detail_id = (string) Str::uuid());
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id', 'alat_id');
    }

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id', 'peminjaman_id');
    }
}
