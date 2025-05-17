<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Hubungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class HubunganControllerApi extends Controller
{
    public function index()
    {
        $hubungan = Cache::remember('hubungan_list', 1296000, function () {
            return Hubungan::query()
                ->select('id', 'jenis_hubungan')
                ->orderByDesc('id')
                ->get();
        });

        return response()->json([
            'error' => false,
            'message' => 'Data Hubungan',
            'data' => $hubungan,
        ], HttpFoundationResponse::HTTP_OK);
    }
}
