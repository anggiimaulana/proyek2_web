<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengajuanSkpPengajuanBantuanResource;
use App\Models\PengajuanSkpPengajuanBantuan;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class PengajuanSkpPengajuanBantuanControllerApi extends Controller
{
    public function index()
    {
        $skpPengajuanBantuan = PengajuanSkpPengajuanBantuan::with([
            'hubunganPengaju:id,jenis_hubungan',
            'jenisKelaminPengaju:id,jenis_kelamin',
            'agamaPengaju:id,nama_agama',
            'pekerjaanPengaju:id,nama_pekerjaan',
            'kategoriBantuan:id,nama_kategori',
        ])->orderBy('id', 'desc')->paginate(5);

        return PengajuanSkpPengajuanBantuanResource::collection($skpPengajuanBantuan);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hubungan' => 'required',
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'jk' => 'required',
            'agama' => 'required',
            'alamat' => 'required',
            'pekerjaan' => 'required',
            'kategori_bantuan' => 'required',
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
            $skpPengajuanBantuan = PengajuanSkpPengajuanBantuan::create($request->all());

            return response()->json([
                'error' => false,
                'message' => 'Pengajuan berhasil ditambahkan.',
                'data' => $skpPengajuanBantuan,
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
            $skpPengajuanBantuan = PengajuanSkpPengajuanBantuan::with([
                'hubunganPengaju:id,jenis_hubungan',
                'jenisKelaminPengaju:id,jenis_kelamin',
                'agamaPengaju:id,nama_agama',
                'pekerjaanPengaju:id,nama_pekerjaan',
                'kategoriBantuan:id,nama_kategori',
            ])->findOrFail($id);

            return new PengajuanSkpPengajuanBantuanResource($skpPengajuanBantuan);
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
            $skpPengajuanBantuan = PengajuanSkpPengajuanBantuan::findOrFail($id);
            $skpPengajuanBantuan->update($request->all());

            return (new PengajuanSkpPengajuanBantuanResource($skpPengajuanBantuan->load([
                'hubunganPengaju:id,jenis_hubungan',
                'jenisKelaminPengaju:id,jenis_kelamin',
                'agamaPengaju:id,nama_agama',
                'pekerjaanPengaju:id,nama_pekerjaan',
                'kategoriBantuan:id,nama_kategori',
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
            $skpPengajuanBantuan = PengajuanSkpPengajuanBantuan::findOrFail($id);
            $skpPengajuanBantuan->delete();

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
