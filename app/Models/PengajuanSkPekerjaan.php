<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanSkPekerjaan extends Model
{
    protected $table = 'pengajuan_sk_pekerjaans';

    protected $fillable = [
        'hubungan',
        'kk_id',
        'nik_id',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jk',
        'status_perkawinan',
        'pekerjaan_terdahulu',
        'pekerjaan_sekarang',
        'alamat',
        'file_kk',
    ];

    public function getFileKkAttribute($value)
    {
        return $value ? $value : null;
    }

    public function hubunganPengaju()
    {
        return $this->belongsTo(Hubungan::class, 'hubungan');
    }

    public function jenisKelaminPengaju()
    {
        return $this->belongsTo(JenisKelamin::class, 'jk');
    }

    public function statusPerkawinanPengaju()
    {
        return $this->belongsTo(StatusPerkawinan::class, 'status_perkawinan');
    }

    public function pekerjaanTerdahuluPengaju()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_terdahulu');
    }

    public function pekerjaanSekarangPengaju()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_sekarang');
    }

    public function agamaPengaju()
    {
        return $this->belongsTo(Agama::class, 'agama');
    }

    public function idKkPengaju()
    {
        return $this->belongsTo(KartuKeluarga::class, 'kk_id');
    }

    public function idNikPengaju()
    {
        return $this->belongsTo(Nik::class, 'nik_id');
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
