<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPengajuan extends Model
{
    use HasFactory;
    protected $table = 'kategori_pengajuan';
    protected $fillable = ['nama_kategori'];

    protected static function booted()
    {
        static::created(function ($kategoriPengajuan) {
            self::clearCache($kategoriPengajuan);
        });

        static::updated(function ($kategoriPengajuan) {
            self::clearCache($kategoriPengajuan);
        });

        static::deleted(function ($kategoriPengajuan) {
            self::clearCache($kategoriPengajuan);
        });
    }

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'kategori_pengajuan');
    }
}
