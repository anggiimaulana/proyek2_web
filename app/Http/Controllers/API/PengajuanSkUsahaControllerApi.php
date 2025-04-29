<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengajuanSkUsahaResource;
use App\Models\PengajuanSkUsaha;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class PengajuanSkUsahaControllerApi extends Controller
{
    public function index()
    {
        $skUsaha = PengajuanSkUsaha::with([
            'hubunganPengaju:id,jenis_hubungan',
            'jenisKelaminPengaju:id,jenis_kelamin',
            'statusPerkawinanPengaju:id,status_perkawinan',
            'pekerjaanPengaju:id,nama_pekerjaan',
        ])->orderBy('id', 'desc')->paginate(5);

        return PengajuanSkUsahaResource::collection($skUsaha);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hubungan' => 'required',
            'nama' => 'required',
            'nik' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'jk' => 'required',
            'status_perkawinan' => 'required',
            'pekerjaan' => 'required',
            'alamat' => 'required',
            'nama_usaha' => 'required',
            'file_ktp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validasi gagal ditambahkan.',
                'data' => $validator->errors(),
            ], HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $skUsaha = PengajuanSkUsaha::create($request->all());

            return response()->json([
                'error' => false,
                'message' => 'Pengajuan berhasil ditambahkan.',
                'data' => $skUsaha,
            ], HttpFoundationResponse::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Pengajuan gagal ditambahkan.',
                'data' => $e,
            ], HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function show(string $id)
    {
        try {
            $skUsaha = PengajuanSkUsaha::with([
                'hubunganPengaju:id,jenis_hubungan',
                'jenisKelaminPengaju:id,jenis_kelamin',
                'statusPerkawinanPengaju:id,status_perkawinan',
                'pekerjaanPengaju:id,nama_pekerjaan',
            ])->findOrFail($id);

            return new PengajuanSkUsahaResource($skUsaha);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Data Pengajuan Tidak Ditemukan',
            ], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $skUsaha = PengajuanSkUsaha::findOrFail($id);
            $skUsaha->update($request->all());

            return (new PengajuanSkUsahaResource($skUsaha->load([
                'hubunganPengaju:id,jenis_hubungan',
                'jenisKelaminPengaju:id,jenis_kelamin',
                'statusPerkawinanPengaju:id,status_perkawinan',
                'pekerjaanPengaju:id,nama_pekerjaan',
            ])))->additional([
                'error' => false,
                'message' => 'Pengajuan berhasil diperbarui'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Data Pengajuan tidak ditemukan.',
            ], HttpFoundationResponse::HTTP_NOT_FOUND);
        }
    }

    public function destroy(string $id)
    {
        try {
            $skUsaha = PengajuanSkUsaha::findOrFail($id);
            $skUsaha->delete();

            return response()->json([
                'error' => false,
                'message' => 'Data Pengajuan berhasil dihapus.',
            ], HttpFoundationResponse::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Data Pengajuan tidak ditemukan.',
            ], HttpFoundationResponse::HTTP_NOT_FOUND);
        }
    }
}
