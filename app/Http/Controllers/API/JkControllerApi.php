<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\JenisKelamin;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class JkControllerApi extends Controller
{
    public function index()
    {
        $jk = JenisKelamin::all();
        $response = [
            'error' => false,
            'message' => 'Data Jenis Kelamin',
            'data' => $jk,
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
