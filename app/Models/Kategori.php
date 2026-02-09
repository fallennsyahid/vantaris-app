<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kategori extends Model
{
    protected $table = 'kategoris';
    protected $fillable = [
        'kategori_id',
        'nama_kategori',
        'slug',
        'status',
    ];

    public function getRouteKeyName()
    {
        return 'kategori_id';
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->kategori_id)) {
                $model->kategori_id = (string) Str::uuid();
            }
            if (empty($model->slug) && !empty($model->nama_kategori)) {
                $model->slug = Str::slug($model->nama_kategori);
            }
            if (empty($model->status)) {
                $model->status = 'active';
            }
        });
    }

    public function alats()
    {
        return $this->hasMany(Alat::class, 'kategori_id', 'kategori_id');
    }
}
