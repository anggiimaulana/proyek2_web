<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusPerkawinan extends Model
{
    protected $table = 'status_perkawinan';

    public function users() {
        return $this->hasMany(User::class, 'status');
    }

    public function client() {
        return $this->hasMany(Client::class, 'status');
    }

    public function kuwu() {
        return $this->hasMany(Kuwu::class, 'status');
    }

    public function skStatus() {
        return $this->hasMany(PengajuanSkStatus::class, 'status_perkawinan');
    }

    public function skPekerjaan() {
        return $this->hasMany(PengajuanSkPekerjaan::class, 'status_perkawinan');
    }

    public function skBelumMenikah() {
        return $this->hasMany(PengajuanSkBelumMenikah::class, 'status_perkawinan');
    }

    public function skUsaha() {
        return $this->hasMany(PengajuanSkUsaha::class, 'status_perkawinan');
    }
}
