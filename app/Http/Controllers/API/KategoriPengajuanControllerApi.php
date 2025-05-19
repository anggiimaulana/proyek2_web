<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\KategoriPengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class KategoriPengajuanControllerApi extends Controller
{
    public function index()
    {
        $kategoriPengajuan = Cache::remember('kategori_pengajuan_list', 1296000, function () {
            return KategoriPengajuan::query()
                ->select('id', 'nama_kategori')
                ->orderByDesc('id')
                ->get();
        });

        return response()->json([
            'error' => false,
            'message' => 'Kategori Pengajuan',
            'data' => $kategoriPengajuan
        ], HttpFoundationResponse::HTTP_OK);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
