<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Penghasilan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class PenghasilanControllerApi extends Controller
{
    public function index()
    {
        $penghasilan = Cache::remember('penghasilan_list', 1296000, function () {
            return Penghasilan::query()
                ->select('id', 'penghasilan')
                ->orderByDesc('id')
                ->get();
        });
        $response = [
            'error' => false,
            'message' => 'Penghasilan',
            'data' => $penghasilan
        ];

        return response()->json($response, HttpFoundationResponse::HTTP_OK);
    }
}
