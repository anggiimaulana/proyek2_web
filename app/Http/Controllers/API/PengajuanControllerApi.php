<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengajuanResource;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class PengajuanControllerApi extends Controller
{
    public function index()
    {
        $pengajuan = Pengajuan::with([
            'userPengajuan:id',
            'admin:id',
            'kategoriPengajuan:id,nama_kategori',
            'statusPengajuan:id,status',
            'adminUpdated:id',
            'kuwuUpdated:id',
        ])->orderBy('id', 'desc')->paginate(5);

        return PengajuanResource::collection($pengajuan);
    }

    public function showByUser($id_user)
    {
        $pengajuan = Pengajuan::with([
            'userPengajuan:id',
            'admin:id',
            'kategoriPengajuan:id,nama_kategori',
            'statusPengajuan:id,status',
            'adminUpdated:id',
            'kuwuUpdated:id',
        ])
            ->where('id_user_pengajuan', $id_user)
            ->orderBy('id', 'desc')
            ->paginate(5);

        return PengajuanResource::collection($pengajuan);
    }
}
