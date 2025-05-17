<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanSktmSekolah extends Model
{
    protected $table = 'pengajuan_sktm_sekolahs';

    protected $fillable = [
        'hubungan',
        'nama',
        'kk_id',
        'nik_id',
        'tempat_lahir_ortu',
        'tanggal_lahir_ortu',
        'pekerjaan',
        'nama_anak',
        'tempat_lahir',
        'tanggal_lahir',
        'jk',
        'agama',
        'asal_sekolah',
        'kelas',
        'alamat',
        'file_kk',
    ];

    public function getFileKkAttribute($value)
    {
        return $value ?  $value : null;
    }


    public function hubunganPengaju()
    {
        return $this->belongsTo(Hubungan::class, 'hubungan');
    }

    public function jenisKelaminPengaju()
    {
        return $this->belongsTo(JenisKelamin::class, 'jk');
    }

    public function agamaPengaju()
    {
        return $this->belongsTo(Agama::class, 'agama');
    }

    public function pekerjaanPengaju()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan');
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
