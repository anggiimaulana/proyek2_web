<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pendidikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class PendidikanControllerApi extends Controller
{
    public function index()
    {
        $pendidikan = Cache::remember('pendidikan_list', 1296000, function () {
            return Pendidikan::query()
                ->select('id', 'jenis_pendidikan')
                ->get();
        });
        return response()->json([
            'error' => false,
            'message' => 'Data Pendidikan',
            'data' => $pendidikan,
        ], HttpFoundationResponse::HTTP_OK);
    }
}
