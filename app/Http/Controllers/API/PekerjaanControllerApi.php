<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pekerjaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class PekerjaanControllerApi extends Controller
{
    public function index()
    {
        $pekerjaan = Cache::remember('pekerjaan_list', 1296000, function () {
            return Pekerjaan::query()
                ->select('id', 'nama_pekerjaan')
                ->orderByDesc('id')
                ->get();
        });
        return response()->json([
            'error' => false,
            'message' => 'Data Pekerjaan',
            'data' => $pekerjaan,
        ], HttpFoundationResponse::HTTP_OK);
    }
}
