<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuans';

    protected $fillable = [
        'id_user_pengajuan',
        'id_admin',
        'kategori_pengajuan',
        'detail_type',
        'detail_id',
        'url_file',
        'status_pengajuan',
        'catatan',
        'id_admin_updated',
        'id_kuwu_updated',
    ];

    public function userPengajuan()
    {
        return $this->belongsTo(Nik::class, 'id_user_pengajuan');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin');
    }

    public function kategoriPengajuan()
    {
        return $this->belongsTo(KategoriPengajuan::class, 'kategori_pengajuan');
    }

    public function statusPengajuan()
    {
        return $this->belongsTo(StatusPengajuan::class, 'status_pengajuan');
    }

    public function detail()
    {
        return $this->morphTo();
    }


    public function adminUpdated()
    {
        return $this->belongsTo(User::class, 'id_admin_updated');
    }

    public function kuwuUpdated()
    {
        return $this->belongsTo(Kuwu::class, 'id_kuwu_updated');
    }

    protected static function booted()
    {
        static::deleting(function ($pengajuan) {
            if ($pengajuan->detail) {
                $pengajuan->detail->delete();
            }
        });
    }
}
