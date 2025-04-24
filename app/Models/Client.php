<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Client extends Model
{
    use HasFactory;
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

    public function setPasswordAttribute($value)
    {
        // Cek dulu apakah password-nya sudah di-hash atau belum
        if (!Hash::needsRehash($value)) {
            $value = Hash::make($value);
        }

        $this->attributes['password'] = $value;
    }

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
        return $this->hasMany(Pengajuan::class, 'id_user_pengajuan')
            ->onDelete('cascade');
    }
}
