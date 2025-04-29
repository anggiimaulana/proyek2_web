<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanSktmSekolah extends Model
{
    protected $table = 'pengajuan_sktm_sekolahs';

    protected $fillable = [
        'hubungan',
        'nama',
        'nama_anak',
        'tempat_lahir',
        'tanggal_lahir',
        'jk',
        'agama',
        'asal_sekolah',
        'kelas',
        'file_kk',
    ];

    public function hubunganPengaju()
    {
        return $this->belongsTo(Hubungan::class);
    }

    public function jenisKelaminPengaju()
    {
        return $this->belongsTo(JenisKelamin::class);
    }

    public function agamaPengaju()
    {
        return $this->belongsTo(Agama::class);
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
