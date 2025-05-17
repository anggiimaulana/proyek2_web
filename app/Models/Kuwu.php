<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Models\Contracts\FilamentUser;

class Kuwu extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $table = 'kuwu';

    protected $fillable = [
        'nip',
        'name',
        'jk',
        'status',
        'agama',
        'email',
        'password'
    ];

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return true;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return null;
    }

    public function getUserName(): string
    {
        return (string) ($this->name ?? 'Kuwu');
    }



    // Relasi
    public function kuwuJenisKelamin()
    {
        return $this->belongsTo(JenisKelamin::class);
    }

    public function kuwuStatusPerkawinan()
    {
        return $this->belongsTo(StatusPerkawinan::class);
    }

    public function kuwusAgama()
    {
        return $this->belongsTo(Agama::class);
    }

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'id_kuwu_updated')
            ->onDelete('cascade');
    }
}
