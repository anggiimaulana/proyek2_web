<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nip',
        'name',
        'jk',
        'status',
        'agama',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function userJenisKelamin()
    {
        return $this->belongsTo(JenisKelamin::class);
    }

    public function userStatusPerkawinan()
    {
        return $this->belongsTo(StatusPerkawinan::class);
    }

    public function usersAgama()
    {
        return $this->belongsTo(Agama::class);
    }

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'id_admin')
            ->onDelete('cascade'); 
    }
}
