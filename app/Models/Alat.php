<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    protected $table = 'alats';
    protected $fillable = [
        'alat_id',
        'kategori_id',
        'nama_alat',
        'stok',
        'foto_alat',
    ];

    public function getRouteKeyName()
    {
        return 'alat_id';
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->alat_id)) {
                $model->alat_id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'kategori_id');
    }
}
