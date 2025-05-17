<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisKelamin extends Model
{
    protected $table = 'jenis_kelamin';

    protected $fillable = ['jenis_kelamin'];

    protected static function booted()
    {
        static::created(function ($jenisKelamin) {
            self::clearCache($jenisKelamin);
        });

        static::updated(function ($jenisKelamin) {
            self::clearCache($jenisKelamin);
        });

        static::deleted(function ($jenisKelamin) {
            self::clearCache($jenisKelamin);
        });
    }

    public function users() {
        return $this->hasMany(User::class, 'jk');
    }

    public function client() {
        return $this->hasMany(Nik::class, 'jk');
    }

    public function kuwu() {
        return $this->hasMany(Kuwu::class, 'jk');
    }

    public function skpotBeasiswa() {
        return $this->hasMany(PengajuanSkpotBeasiswa::class, 'jk');
    }

    public function sktmBeasiswa() {
        return $this->hasMany(PengajuanSktmBeasiswa::class, 'jk');
    }

    public function sktmListrik() {
        return $this->hasMany(PengajuanSktmListrik::class, 'jk');
    }

    public function skStatus() {
        return $this->hasMany(PengajuanSkStatus::class, 'jk');
    }

    public function skPekerjaan() {
        return $this->hasMany(PengajuanSkPekerjaan::class, 'jk');
    }

    public function skBelumMenikah() {
        return $this->hasMany(PengajuanSkBelumMenikah::class, 'jk');
    }

    public function sktmSekolah() {
        return $this->hasMany(PengajuanSktmSekolah::class, 'jk');
    }

    public function skUsaha() {
        return $this->hasMany(PengajuanSkUsaha::class, 'jk');
    }
}
