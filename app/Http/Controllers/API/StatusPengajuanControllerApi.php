<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\StatusPengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class StatusPengajuanControllerApi extends Controller
{
    public function index()
    {
        $statusPengajuan = Cache::remember('status_pengajuan_list', 1296000, function () {
            return StatusPengajuan::query()
                ->select('id', 'status')
                ->orderByDesc('id')
                ->get();
        });
        return response()->json([
            'error' => false,
            'message' => 'Status Pengajuan',
            'data' => $statusPengajuan
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
