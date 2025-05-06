<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Client extends Authenticatable
{
    use HasFactory, HasApiTokens;
    protected $table = 'client';

    protected $fillable = [
        'nik',
        'name',
        'tempat_lahir',
        'tanggal_lahir',
        'jk',
        'status',
        'agama',
        'alamat',
        'pendidikan',
        'pekerjaan',
        'nomor_telepon',
        'password',
    ];

    public function clientJenisKelamin()
    {
        return $this->belongsTo(JenisKelamin::class, 'jk');
    }

    public function clientStatusPerkawinan()
    {
        return $this->belongsTo(StatusPerkawinan::class, 'status');
    }

    public function clientAgama()
    {
        return $this->belongsTo(Agama::class, 'agama');
    }

    public function clientPendidikan()
    {
        return $this->belongsTo(Pendidikan::class, 'pendidikan');
    }

    public function clientPekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan');
    }

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'id_user_pengajuan');
    }
}
