<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendidikan extends Model
{
    protected $table = 'pendidikan';

    protected $fillable = ['jenis_pendidikan'];

    public function client() {
        return $this->hasMany(Client::class, 'pendidikan');
    }
}
