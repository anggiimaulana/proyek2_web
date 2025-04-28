<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSkpotBeasiswa extends Model
{
    use HasFactory;
    protected $table = 'pengajuan_skpot_beasiswas';

    protected $fillable = [
        'hubungan',
        'nama',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jk',
        'agama',
        'nama_ortu',
        'penghasilan',
        'file_kk',
    ];

    public function hubunganPengaju()
    {
        return $this->belongsTo(Hubungan::class, 'hubungan');
    }

    public function jkPpengaju()
    {
        return $this->belongsTo(JenisKelamin::class, 'jk');
    }

    public function agamaPpengaju()
    {
        return $this->belongsTo(Agama::class, 'agama');
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
