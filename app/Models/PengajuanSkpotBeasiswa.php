<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSkpotBeasiswa extends Model
{
    use HasFactory;
    protected $table = 'pengajuan_skpot_beasiswa';

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

    public function skpotBeasiswaHubungan()
    {
        return $this->belongsTo(Hubungan::class, 'hubungan');
    }

    public function skpotBeasiswaJk()
    {
        return $this->belongsTo(JenisKelamin::class, 'jk');
    }

    public function skpotBeasiswaAgama()
    {
        return $this->belongsTo(Agama::class, 'agama');
    }

    public function skpotBeasiswaPenghasilan()
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
