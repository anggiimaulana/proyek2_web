<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penghasilan extends Model
{
    protected $table = 'penghasilan';

    protected $fillable = [
        'rentang_penghasilan',
    ];

    public function skpotBeasiswa()
    {
        return $this->hasMany(PengajuanSkpotBeasiswa::class, 'penghasilan');
    }

    public function sktmListrik()
    {
        return $this->hasMany(PengajuanSktmListrik::class, 'penghasilan');
    }
}
