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

    public function skpBantuanHubungan()
    {
        return $this->belongsTo(Hubungan::class, 'hubungan');
    }

    public function skpBantuanJenisKelamin()
    {
        return $this->belongsTo(JenisKelamin::class, 'jk');
    }

    public function skpBantuanAgama()
    {
        return $this->belongsTo(Agama::class, 'agama');
    }

    public function skpBantuanPekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan');
    }

    public function skpBantuanKategoriBantuan()
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
