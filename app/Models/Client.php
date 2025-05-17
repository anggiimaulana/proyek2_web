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
        'kk_id',
        'nama_kepala_keluarga',
        'nomor_telepon',
        'password',
    ];

    public function kartuKeluarga()
    {
        return $this->belongsTo(KartuKeluarga::class, 'kk_id')->select('id');
    }

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'id_user_pengajuan');
    }
}
