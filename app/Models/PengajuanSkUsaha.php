<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanSkUsaha extends Model
{
    protected $table = 'pengajuan_sk_usahas';

    protected $fillable = [
        'hubungan',
        'nama',
        'nik',
        'jk',
        'tempat_lahir',
        'tanggal_lahir',
        'status_perkawinan',
        'pekerjaan',
        'alamat',
        'nama_usaha',
        'file_ktp',
    ];

    public function hubunganPengaju()
    {
        return $this->belongsTo(Hubungan::class, 'hubungan');
    }

    public function jenisKelaminPengaju()
    {
        return $this->belongsTo(JenisKelamin::class, 'jenis_kelamin');
    }

    public function pekerjaanPengaju()
    {
        return $this->belongsTo(Pekerjaan::class, 'nama_pekerjaan');
    }

    public function statusPerkawinanPengaju()
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
