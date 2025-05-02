<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengajuanSktmBeasiswaResource;
use App\Models\PengajuanSktmBeasiswa;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class PengajuanSktmBeasiswaControllerApi extends Controller
{
    public function index()
    {
        $sktmbeasiswa = PengajuanSktmBeasiswa::with([
            'hubunganPengaju:id,jenis_hubungan',
            'jenisKelaminPengaju:id,jenis_kelamin',
            'agamaPengaju:id,nama_agama',
            'pekerjaanAnakPengaju:id,nama_pekerjaan',
            'pekerjaanOrtuPengaju:id,nama_pekerjaan',
        ])->orderBy('id', 'desc')->paginate(5);

        return PengajuanSktmBeasiswaResource::collection($sktmbeasiswa);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hubungan' => 'required',
            'nama_anak' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'suku' => 'required',
            'jk' => 'required',
            'agama' => 'required',
            'pekerjaan_anak' => 'required',
            'nama' => 'required',
            'nama_ibu' => 'required',
            'pekerjaan_ortu' => 'required',
            'alamat' => 'required',
            'file_kk' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Pengajuan gagal ditambahkan.',
                'data' => $validator->errors(),
            ], HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $sktmbeasiswa = PengajuanSktmBeasiswa::create($request->all());

            return response()->json([
                'error' => false,
                'message' => 'Pengajuan berhasil ditambahkan.',
                'data' => $sktmbeasiswa,
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
            $sktmbeasiswa = PengajuanSktmBeasiswa::with([
                'hubunganPengaju:id,jenis_hubungan',
                'jenisKelaminPengaju:id,jenis_kelamin',
                'agamaPengaju:id,nama_agama',
                'pekerjaanAnakPengaju:id,nama_pekerjaan',
                'pekerjaanOrtuPengaju:id,nama_pekerjaan',
            ])->findOrFail($id);

            return new PengajuanSktmBeasiswaResource($sktmbeasiswa);
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
            $sktmbeasiswa = PengajuanSktmBeasiswa::findOrFail($id);
            $sktmbeasiswa->update($request->all());

            return (new PengajuanSktmBeasiswaResource($sktmbeasiswa->load([
                'hubunganPengaju:id,jenis_hubungan',
                'jenisKelaminPengaju:id,jenis_kelamin',
                'agamaPengaju:id,nama_agama',
                'pekerjaanAnakPengaju:id,nama_pekerjaan',
                'pekerjaanOrtuPengaju:id,nama_pekerjaan',
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
            $sktmbeasiswa = PengajuanSktmBeasiswa::findOrFail($id);
            $sktmbeasiswa->delete();

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
