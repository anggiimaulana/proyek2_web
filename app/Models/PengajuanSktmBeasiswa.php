<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSktmBeasiswa extends Model
{
    use HasFactory;
    protected $table = 'pengajuan_sktm_beasiswa';

    protected $fillable = [
        'hubungan',
        'nama_anak',
        'tempat_lahir',
        'tanggal_lahir',
        'suku',
        'agama',
        'jk',
        'pekerjaan_anak',
        'nama_ayah',
        'nama_ibu',
        'pekerjaan_ortu',
        'file_kk',
    ];

    public function sktmBeasiswaHubungan()
    {
        return $this->belongsTo(Hubungan::class, 'hubungan');
    }

    public function sktmBeasiswaJk()
    {
        return $this->belongsTo(JenisKelamin::class, 'jk');
    }

    public function sktmBeasiswaAgama()
    {
        return $this->belongsTo(Agama::class, 'agama');
    }

    public function sktmBeasiswaPekerjaanOrtu()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_ortu');
    }

    public function sktmBeasiswaPekerjaanAnak()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_anak');
    }

    public function pengajuan()
    {
        return $this->morphOne(Pengajuan::class, 'detail');
    }
}
