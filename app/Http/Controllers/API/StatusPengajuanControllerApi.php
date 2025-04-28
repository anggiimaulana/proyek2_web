<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\StatusPengajuan;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class StatusPengajuanControllerApi extends Controller
{
    public function index()
    {
        $statusPengajuan = StatusPengajuan::all();
        $response = [
            'error' => false,
            'message' => 'Status Pengajuan',
            'data' => $statusPengajuan
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
