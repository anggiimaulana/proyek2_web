<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\KategoriBantuan;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class KategoriBantuanControllerApi extends Controller
{
    public function index()
    {
        $kategoriBantuan = KategoriBantuan::all();
        $response = [
            'error' => false,
            'message' => 'Kategori Bantuan',
            'data' => $kategoriBantuan
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
