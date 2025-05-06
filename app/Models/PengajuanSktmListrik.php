<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanSktmListrik extends Model
{
    protected $table = 'pengajuan_sktm_listriks';

    protected $fillable = [
        'hubungan',
        'nama',
        'nik',
        'agama',
        'umur',
        'jk',
        'alamat',
        'pekerjaan',
        'penghasilan',
        'nama_pln',
        'file_kk',
    ];

    public function getFileKkAttribute($value)
    {
        return $value ? 'uploads/kk/' . $value : null;
    }

    public function hubunganPengaju()
    {
        return $this->belongsTo(Hubungan::class, 'hubungan');
    }

    public function pekerjaanPengaju()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan');
    }

    public function penghasilanPengaju()
    {
        return $this->belongsTo(Penghasilan::class, 'penghasilan');
    }

    public function jenisKelaminPengaju()
    {
        return $this->belongsTo(JenisKelamin::class, 'jk');
    }

    public function agamaPengaju()
    {
        return $this->belongsTo(Agama::class, 'agama');
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
