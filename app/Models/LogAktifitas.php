<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LogAktivitas extends Model
{
    protected $table = 'log_aktifitas'; // Manual karena Laravel mungkin anggap jamak 'log_aktifitas'
    protected $fillable = ['log_id', 'user_id', 'aksi', 'entitas', 'keterangan_dan_detail'];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($model) => $model->log_id = (string) Str::uuid());
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
