<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuwu extends Model
{
    use HasFactory;
    protected $table = 'kuwu';

    protected $fillable = [
        'nip',
        'nama',
        'jk',
        'status',
        'agama',
        'email',
        'password'
    ];

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
