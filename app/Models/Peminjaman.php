<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';
    protected $primaryKey = 'peminjaman_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'peminjaman_id',
        'user_id',
        'tanggal_pengajuan',
        'tanggal_pengambilan_rencana',
        'tanggal_pengembalian_rencana',
        'tanggal_pengambilan_sebenarnya',
        'alasan_meminjam',
        'approved_by',
        'status',
        'note',
        'qr_token'
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'tanggal_pengambilan_rencana' => 'datetime',
        'tanggal_pengembalian_rencana' => 'datetime',
        'tanggal_pengambilan_sebenarnya' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->peminjaman_id)) {
                $model->peminjaman_id = (string) Str::uuid();
            }
        });
    }

    // Relasi ke User yang meminjam
    public function peminjam()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relasi ke Petugas yang menyetujui
    public function pemberi_izin()
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id');
    }

    // Relasi ke detail alat yang dipinjam
    public function details()
    {
        return $this->hasMany(PeminjamanDetail::class, 'peminjaman_id', 'peminjaman_id');
    }

    // Relasi ke data pengembalian (One-to-One)
    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'peminjaman_id', 'peminjaman_id');
    }

    // Accessor for kode_peminjaman (generate from peminjaman_id)
    public function getKodePeminjamanAttribute()
    {
        return 'PJM-' . strtoupper(substr($this->peminjaman_id, 0, 8));
    }
}
