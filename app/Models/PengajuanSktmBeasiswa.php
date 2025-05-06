<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSktmBeasiswa extends Model
{
    use HasFactory;
    protected $table = 'pengajuan_sktm_beasiswas';

    protected $fillable = [
        'hubungan',
        'nama_anak',
        'tempat_lahir',
        'tanggal_lahir',
        'suku',
        'agama',
        'jk',
        'pekerjaan_anak',
        'nama',
        'nama_ibu',
        'pekerjaan_ortu',
        'alamat',
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

    public function jenisKelaminPengaju()
    {
        return $this->belongsTo(JenisKelamin::class, 'jk');
    }

    public function agamaPengaju()
    {
        return $this->belongsTo(Agama::class, 'agama');
    }

    public function pekerjaanOrtuPengaju()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_ortu');
    }

    public function pekerjaanAnakPengaju()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_anak');
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
