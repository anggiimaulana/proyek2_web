<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\KartuKeluarga;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class KkControllerApi extends Controller
{
    public function index()
    {
        $kk = Cache::remember('kk_list', 1296000, function () {
            return KartuKeluarga::query()
                ->select('id', 'nomor_kk', 'kepala_keluarga')
                ->orderBy('id', 'desc')
                ->get();
        });
        $response = [
            'error' => false,
            'message' => 'Data Kartu Keluarga',
            'data' => $kk,
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function show(string $id): JsonResponse
    {
        if (!ctype_digit($id)) {
            return response()->json([
                'error' => true,
                'message' => 'ID tidak valid.',
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $kk = KartuKeluarga::findOrFail($id);

            return response()->json([
                'error' => false,
                'message' => 'Data Kartu Keluarga ditemukan',
                'data' => $kk,
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Data Kartu Keluarga tidak ditemukan',
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
