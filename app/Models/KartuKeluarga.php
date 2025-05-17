<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class KartuKeluarga extends Model
{
    use HasFactory;

    protected $table = 'kartu_keluargas';

    protected $fillable = [
        'nomor_kk',
        'kepala_keluarga'
    ];

    protected static function clearCache($nik)
    {
        Log::info("Clearing cache for NIK: {$nik->id}");

        Cache::forget('nik_list');
        for ($page = 1; $page <= 10; $page++) {
            Cache::forget("nik_by_kk_{$nik->kk_id}_page_{$page}");
        }
    }

    public function niks()
    {
        return $this->hasMany(Nik::class, 'kk_id', 'id');
    }

    public function client()
    {
        return $this->hasMany(Client::class, 'kk_id');
    }

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'kk_id');
    }
}
