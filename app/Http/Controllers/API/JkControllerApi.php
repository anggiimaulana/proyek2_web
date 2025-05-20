<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\JenisKelamin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class JkControllerApi extends Controller
{
    public function index()
    {
        $jk = Cache::remember('jk_list', 1296000, function () {
            return JenisKelamin::query()
                ->select('id', 'jenis_kelamin')
                ->get();
        });
        return response()->json([
            'error' => false,
            'message' => 'Data Jenis Kelamin',
            'data' => $jk,
        ], HttpFoundationResponse::HTTP_OK);
    }
}
