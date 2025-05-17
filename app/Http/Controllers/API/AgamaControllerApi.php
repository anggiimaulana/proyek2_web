<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Agama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class AgamaControllerApi extends Controller
{
    public function index()
    {
        $agama = Cache::remember('agama_list', 1296000, function () {
            return Agama::query()
                ->select('id', 'nama_agama')
                ->orderByDesc('id')
                ->get();
        });
        return response()->json([
            'error' => false,
            'message' => 'Data Agama',
            'data' => $agama,
        ], HttpFoundationResponse::HTTP_OK);
    }
}
