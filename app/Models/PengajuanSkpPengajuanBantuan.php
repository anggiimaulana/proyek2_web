<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanSkpPengajuanBantuan extends Model
{
    protected $table = 'pengajuan_skp_pengajuan_bantuans';

    protected $fillable = [
        'hubungan',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jk',
        'agama',
        'alamat',
        'pekerjaan',
        'kategori_bantuan',
        'file_kk',
    ];

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

    public function kategoriBantuan()
    {
        return $this->belongsTo(KategoriBantuan::class, 'kategori_bantuan');
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
