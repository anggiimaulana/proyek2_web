<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agama extends Model
{
    protected $table = 'agama';

    protected $fillable = [
        'nama_agama',
    ];

    protected static function booted()
    {
        static::created(function ($agama) {
            self::clearCache($agama);
        });

        static::updated(function ($agama) {
            self::clearCache($agama);
        });

        static::deleted(function ($agama) {
            self::clearCache($agama);
        });
    }

    public function users()
    {
        return $this->hasMany(User::class, 'agama');
    }

    public function client()
    {
        return $this->hasMany(Nik::class, 'agama');
    }

    public function kuwu()
    {
        return $this->hasMany(Kuwu::class, 'agama');
    }

    public function skpotBeasiswa()
    {
        return $this->hasMany(PengajuanSkpotBeasiswa::class, 'agama');
    }

    public function sktmListrik()
    {
        return $this->hasMany(PengajuanSktmListrik::class, 'agama');
    }

    public function sktmBeasiswa()
    {
        return $this->hasMany(PengajuanSktmBeasiswa::class, 'agama');
    }

    public function skStatus()
    {
        return $this->hasMany(PengajuanSkStatus::class, 'agama');
    }

    public function skPekerjaan()
    {
        return $this->hasMany(Pekerjaan::class, 'agama');
    }

    public function sktmSekolah()
    {
        return $this->hasMany(PengajuanSktmSekolah::class, 'agama');
    }
}
