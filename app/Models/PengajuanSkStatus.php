<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSkStatus extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_sk_status';

    protected $fillable = [
        'hubungan',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jk',
        'agama',
        'pekerjaan',
        'status_perkawinan',
        'file_kk',
    ];

    public function skStatusStatusPerkawinan()
    {
        return $this->belongsTo(StatusPerkawinan::class, 'status_perkawinan');
    }

    public function skStatusHubungan()
    {
        return $this->belongsTo(Hubungan::class, 'hubungan');
    }

    public function skStatusJenisKelamin()
    {
        return $this->belongsTo(JenisKelamin::class, 'jk');
    }

    public function skStatusAgama()
    {
        return $this->belongsTo(Agama::class, 'agama');
    }

    public function skStatusPekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan');
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
