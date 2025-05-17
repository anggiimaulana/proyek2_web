<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPengajuan extends Model
{
    use HasFactory;
    protected $table = 'status_pengajuan';
    protected $fillable = ['status'];

    protected static function booted()
    {
        static::created(function ($statusPengajuan) {
            self::clearCache($statusPengajuan);
        });

        static::updated(function ($statusPengajuan) {
            self::clearCache($statusPengajuan);
        });

        static::deleted(function ($statusPengajuan) {
            self::clearCache($statusPengajuan);
        });
    }

    // Di model PengajuanSktmBeasiswa
    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'status');
    }
}
