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
        'alamat',
        'pekerjaan',
        'penghasilan',
        'nama_pln',
        'file_kk',
    ];

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
