<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanSkUsaha extends Model
{
    protected $table = 'pengajuan_sk_usahas';

    protected $fillable = [
        'hubungan',
        'nama',
        'nik_id',
        'kk_id',
        'jk',
        'tempat_lahir',
        'tanggal_lahir',
        'status_perkawinan',
        'pekerjaan',
        'alamat',
        'nama_usaha',
        'file_ktp',
    ];

    public function getFileKtpAttribute($value)
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

    public function pekerjaanPengaju()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan');
    }

    public function statusPerkawinanPengaju()
    {
        return $this->belongsTo(StatusPerkawinan::class, 'status_perkawinan');
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
