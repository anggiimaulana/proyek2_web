<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\StatusPerkawinan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class StatusPerkawinanControllerApi extends Controller
{
    public function index()
    {
        $statusPerkawinan = Cache::remember('status_perkawinan_list', 1296000, function () {
            return StatusPerkawinan::query()
                ->select('id', 'status_perkawinan')
                ->orderByDesc('id')
                ->get();
        });
        return response()->json([
            'error' => false,
            'message' => 'Data Status Perkawinan',
            'data' => $statusPerkawinan,
        ], HttpFoundationResponse::HTTP_OK);
    }
}
