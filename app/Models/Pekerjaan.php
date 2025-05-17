<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pekerjaan extends Model
{
    protected $table = 'pekerjaan';

    protected $fillable = [
        'nama_pekerjaan',
    ];

    public function client()
    {
        return $this->hasMany(Nik::class, 'pekerjaan');
    }

    public function pengajuanSkpotBeasiswaAnak()
    {
        return $this->hasMany(PengajuanSkpotBeasiswa::class, 'pekerjaan_anak');
    }

    public function pengajuanSkpotBeasiswaOrtu()
    {
        return $this->hasMany(PengajuanSkpotBeasiswa::class, 'pekerjaan_ortu');
    }

    public function pengajuanSktmBeasiswa()
    {
        return $this->hasMany(PengajuanSktmBeasiswa::class, 'pekerjaan');
    }

    public function pengajuanSkStatus()
    {
        return $this->hasMany(PengajuanSkStatus::class, 'pekerjaan');
    }

    public function pengajuanSkPekerjaanTerdahulu()
    {
        return $this->hasMany(PengajuanSkPekerjaan::class, 'pekerjaan_terdahulu');
    }

    public function pengajuanSkPekerjaanSekarang()
    {
        return $this->hasMany(PengajuanSkPekerjaan::class, 'pekerjaan_sekarang');
    }

    public function pengajuanSkBelumMenikah()
    {
        return $this->hasMany(PengajuanSkBelumMenikah::class, 'pekerjaan');
    }

    public function pengajuanSktmListrik()
    {
        return $this->hasMany(PengajuanSktmListrik::class, 'pekerjaan');
    }

    public function pengajuanSktmSekolah()
    {
        return $this->hasMany(PengajuanSktmSekolah::class, 'pekerjaan');
    }

    public function pengajuanSkUsaha()
    {
        return $this->hasMany(PengajuanSkUsaha::class, 'pekerjaan');
    }
}
