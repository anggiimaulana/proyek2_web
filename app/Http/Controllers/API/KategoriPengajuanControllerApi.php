<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\KategoriPengajuan;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class KategoriPengajuanControllerApi extends Controller
{
    public function index()
    {
        $kategoriPengajuan = KategoriPengajuan::all();
        $response = [
            'error' => false,
            'message' => 'Kategori Pengajuan',
            'data' => $kategoriPengajuan
        ];

        return response()->json($response, HttpFoundationResponse::HTTP_OK);
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
