<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPengajuan extends Model
{
    use HasFactory;
    protected $table = 'kategori_pengajuan';
    protected $fillable = ['nama_kategori'];

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'kategori_pengajuan');
    }
}
