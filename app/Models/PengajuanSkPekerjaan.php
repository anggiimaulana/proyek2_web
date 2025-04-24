<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanSkPekerjaan extends Model
{
    protected $table = 'pengajuan_sk_pekerjaan';

    protected $fillable = [
        'hubungan',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jk',
        'status_perkawinan',
        'agama',
        'pekerjaan_terdahulu',
        'pekerjaan_sekarang',
        'file_kk',
    ];

    public function hubungan()
    {
        return $this->belongsTo(Hubungan::class, 'hubungan');
    }

    public function jenisKelamin()
    {
        return $this->belongsTo(JenisKelamin::class, 'jk');
    }

    public function statusPerkawinan()
    {
        return $this->belongsTo(StatusPerkawinan::class, 'status_perkawinan');
    }

    public function pekerjaanTerdahulu()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_terdahulu');
    }

    public function pekerjaanSekarang()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_sekarang');
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
