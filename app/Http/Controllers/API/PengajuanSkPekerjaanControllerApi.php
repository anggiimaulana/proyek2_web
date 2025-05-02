<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengajuanSkPekerjaanResource;
use App\Models\PengajuanSkPekerjaan;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class PengajuanSkPekerjaanControllerApi extends Controller
{
    public function index()
    {
        $skPekerjaan = PengajuanSkPekerjaan::with([
            'hubunganPengaju:id,jenis_hubungan',
            'jenisKelaminPengaju:id,jenis_kelamin',
            'statusPerkawinanPengaju:id,status_perkawinan',
            'pekerjaanTerdahuluPengaju:id,nama_pekerjaan',
            'pekerjaanSekarangPengaju:id,nama_pekerjaan',
        ])->orderBy('id', 'desc')->paginate(5);

        return PengajuanSkPekerjaanResource::collection($skPekerjaan);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hubungan' => 'required',
            'nik' => 'required',
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'jk' => 'required',
            'status_perkawinan' => 'required',
            'pekerjaan_terdahulu' => 'required',
            'pekerjaan_sekarang' => 'required',
            'alamat' => 'required',
            'file_kk' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validasi gagal ditambahkan.',
                'data' => $validator->errors(),
            ], HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $skPekerjaan = PengajuanSkPekerjaan::create($request->all());

            return response()->json([
                'error' => false,
                'message' => 'Pengajuan berhasil ditambahkan.',
                'data' => $skPekerjaan,
            ], HttpFoundationResponse::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat menambahkan data.',
            ], HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function show(string $id)
    {
        try {
            $skPekerjaan = PengajuanSkPekerjaan::with([
                'hubunganPengaju:id,jenis_hubungan',
                'jenisKelaminPengaju:id,jenis_kelamin',
                'agamaPengaju:id,nama_agama',
                'statusPerkawinanPengaju:id,status_perkawinan',
                'pekerjaanTerdahuluPengaju:id,nama_pekerjaan',
                'pekerjaanSekarangPengaju:id,nama_pekerjaan',
            ])->findOrFail($id);

            return new PengajuanSkPekerjaanResource($skPekerjaan);
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
            $skPekerjaan = PengajuanSkPekerjaan::findOrFail($id);
            $skPekerjaan->update($request->all());

            return (new PengajuanSkPekerjaanResource($skPekerjaan->load([
                'hubunganPengaju:id,jenis_hubungan',
                'jenisKelaminPengaju:id,jenis_kelamin',
                'agamaPengaju:id,nama_agama',
                'statusPerkawinanPengaju:id,status_perkawinan',
                'pekerjaanTerdahuluPengaju:id,nama_pekerjaan',
                'pekerjaanSekarangPengaju:id,nama_pekerjaan',
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
            $skPekerjaan = PengajuanSkPekerjaan::findOrFail($id);
            $skPekerjaan->delete();

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
