<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSkBelumMenikah extends Model
{
    use HasFactory;
    protected $table = 'pengajuan_sk_belum_menikah';

    protected $fillable = [
        'hubungan',
        'nama',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jk',
        'agama',
        'pekerjaan',
        'status_perkawinan',
        'file_kk',
    ];

    public function skBelumMenikahHubungan()
    {
        return $this->belongsTo(Hubungan::class, 'hubungan');
    }

    public function skBelumMenikahJenisKelamin()
    {
        return $this->belongsTo(JenisKelamin::class, 'jk');
    }

    public function skBelumMenikahAgama()
    {
        return $this->belongsTo(Agama::class, 'agama');
    }

    public function skBelumMenikahPekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan');
    }

    public function skBelumMenikahStatusPerkawinan()
    {
        return $this->belongsTo(StatusPerkawinan::class, 'status_perkawinan');
    }

    public function pengajuan()
    {
        return $this->morphOne(Pengajuan::class, 'detail');
    }

    protected static function booted()
    {
        static::deleting(function ($detail) {
            $detail->pengajuan()->delete();
        });
    }
}
