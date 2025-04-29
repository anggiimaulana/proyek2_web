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

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
