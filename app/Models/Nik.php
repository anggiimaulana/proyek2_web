<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Nik extends Model
{
    protected $table = 'niks';
    protected $fillable = [
        'nomor_nik',
        'kk_id',
        'name',
        'hubungan',
        'tempat_lahir',
        'tanggal_lahir',
        'jk',
        'status',
        'agama',
        'alamat',
        'pendidikan',
        'pekerjaan',
    ];

    protected static function clearCache($nik)
    {
        Log::info("Clearing cache for NIK: {$nik->id}");

        Cache::forget('nik_list');
        for ($page = 1; $page <= 10; $page++) {
            Cache::forget("nik_by_kk_{$nik->kk_id}_page_{$page}");
        }
    }
    public function kartuKeluarga()
    {
        return $this->belongsTo(KartuKeluarga::class, 'kk_id',);
    }

    public function hubunganClient()
    {
        return $this->belongsTo(Hubungan::class, 'hubungan')->select('id', 'jenis_hubungan');
    }

    public function clientJenisKelamin()
    {
        return $this->belongsTo(JenisKelamin::class, 'jk')->select('id', 'jenis_kelamin');
    }

    public function clientStatusPerkawinan()
    {
        return $this->belongsTo(StatusPerkawinan::class, 'status')->select('id', 'status_perkawinan');
    }

    public function clientAgama()
    {
        return $this->belongsTo(Agama::class, 'agama')->select('id', 'nama_agama');
    }

    public function clientPendidikan()
    {
        return $this->belongsTo(Pendidikan::class, 'pendidikan')->select('id', 'jenis_pendidikan');
    }

    public function clientPekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan')->select('id', 'nama_pekerjaan');
    }

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'nik_id');
    }
}
