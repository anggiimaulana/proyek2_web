<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanSktmListrik extends Model
{
    protected $table = 'pengajuan_sktm_listrik';

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

    public function sktmListrikHubungan()
    {
        return $this->belongsTo(Hubungan::class, 'hubungan');
    }

    public function sktmListrikPekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan');
    }

    public function sktmListrikPenghasilan()
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
