<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hubungan extends Model
{
    use HasFactory;
    protected $table = 'hubungan';

    protected $fillable = [
        'jenis_hubungan',
    ];

    protected static function booted()
    {
        static::created(function ($hubungan) {
            self::clearCache($hubungan);
        });

        static::updated(function ($hubungan) {
            self::clearCache($hubungan);
        });

        static::deleted(function ($hubungan) {
            self::clearCache($hubungan);
        });
    }

    public function skpotBeasiswa() {
        return $this->hasMany(PengajuanSkpotBeasiswa::class, 'hubungan');
    }

    public function hubunganClient() {
        return $this->hasMany(Nik::class, 'hubungan');
    }

    public function sktmBeasiswa() {
        return $this->hasMany(PengajuanSktmBeasiswa::class, 'hubungan');
    }

    public function skStatus() {
        return $this->hasMany(PengajuanSkStatus::class, 'hubungan');
    }

    public function skPekerjaan() {
        return $this->hasMany(PengajuanSkPekerjaan::class, 'hubungan');
    }

    public function skBelumMenikah() {
        return $this->hasMany(PengajuanSkBelumMenikah::class, 'hubungan');
    }

    public function sktmSekolah() {
        return $this->hasMany(PengajuanSktmSekolah::class, 'hubungan');
    }

    public function sktmListrik() {
        return $this->hasMany(PengajuanSktmListrik::class, 'hubungan');
    }
    public function skUsaha() {
        return $this->hasMany(PengajuanSkUsaha::class, 'hubungan');
    }
}
