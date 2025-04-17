<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBantuan extends Model
{
    protected $table = 'kategori_bantuan';
    protected $fillable = ['nama_kategori'];

    public function pengajuanSkpPengajuanBantuan() {
        return $this->hasMany(PengajuanSkpPengajuanBantuan::class, 'kategori_bantuan');
    }
}
