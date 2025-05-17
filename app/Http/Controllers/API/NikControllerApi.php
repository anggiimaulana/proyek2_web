<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\NikResource;
use App\Models\Nik;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NikControllerApi extends Controller
{
    public function index()
    {
        $nikUser = Cache::remember('nik_list', 1296000, function () {
            return Nik::with([
                'kartuKeluarga:id,nomor_kk',
                'clientJenisKelamin:id,jenis_kelamin',
                'hubunganClient:id,jenis_hubungan',
                'clientAgama:id,agama',
                'clientPendidikan:id,pendidikan',
                'clientPekerjaan:id,pekerjaan',
                'clientStatusPerkawinan:id,status_perkawinan',
                'clientStatusHubunganKeluarga:id,hubungan'
            ])->query()
                ->select('id', 'nomor_nik', 'kk_id', 'name', 'jk', 'hubungan', 'agama', 'tempat_lahir', 'tanggal_lahir', 'pendidikan', 'pekerjaan', 'status', 'alamat')
                ->orderByDesc('id')
                ->get();
        });

        return response()->json([
            'error' => false,
            'message' => 'Data NIK',
            'data' => $nikUser
        ], Response::HTTP_OK);
    }

    public function showByKk(string $id_kk)
    {
        $cacheKey = "nik_by_kk_{$id_kk}_page_" . request('page', 1);

        $nikUser = Cache::remember($cacheKey, 1296000, function () use ($id_kk) {
            return Nik::query()
                ->with('kartuKeluarga:id')
                ->select('id', 'nomor_nik', 'kk_id', 'name', 'jk', 'hubungan', 'agama', 'tempat_lahir', 'tanggal_lahir', 'pendidikan', 'pekerjaan', 'status', 'alamat', 'created_at', 'updated_at')
                ->where('kk_id', $id_kk)
                ->orderByDesc('id')
                ->paginate(10);
        });

        return NikResource::collection($nikUser);
    }
}
