<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'kategori',
        'jenis_layanan',
        'keluhan',
        'lokasi',
        'gambar',
        'status',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
