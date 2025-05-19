<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class ClientControllerApi extends Controller
{
    public function index()
    {
        try {
            $client = Cache::remember('client_list', 1296000, function () {
                return Client::select('id', 'kk_id', 'nama_kepala_keluarga', 'nomor_telepon')
                    ->orderByDesc('id')
                    ->paginate(15);
            });

            return response()->json([
                'error' => false,
                'message' => 'Data Akun Masyarakat',
                'data' => $client,
            ], HttpFoundationResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Gagal mengambil data',
            ], HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(string $id)
    {
        if (!ctype_digit($id)) {
            return response()->json([
                'error' => true,
                'message' => 'ID harus berupa angka',
            ], HttpFoundationResponse::HTTP_BAD_REQUEST);
        }

        try {
            $cacheKey = "client_{$id}";

            $client = Cache::remember($cacheKey, 1296000, function () use ($id) {
                return Client::select('id', 'kk_id', 'nama_kepala_keluarga', 'nomor_telepon')
                    ->where('id', $id)
                    ->firstOrFail();
            });

            return response()->json([
                'error' => false,
                'message' => 'Data Akun Masyarakat',
                'data' => $client,
            ], HttpFoundationResponse::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Data Akun Tidak Ditemukan',
            ], HttpFoundationResponse::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Gagal mengambil data',
            ], HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
