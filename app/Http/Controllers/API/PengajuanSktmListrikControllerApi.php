<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengajuanSktmListrikResource;
use App\Models\PengajuanSktmListrik;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class PengajuanSktmListrikControllerApi extends Controller
{
    public function index()
    {
        $sktmListrik = PengajuanSktmListrik::with([
            'hubunganPengaju:id,jenis_hubungan',
            'pekerjaan:id,nama_pekerjaan',
            'penghasilan:id,rentang_penghasilan',
        ])->orderBy('id', 'desc')->paginate(5);

        return PengajuanSktmListrikResource::collection($sktmListrik);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hubungan' => 'required',
            'nama' => 'required',
            'nik' => 'required',
            'alamat' => 'required',
            'pekerjaan' => 'required',
            'penghasilan' => 'required',
            'nama_pln' => 'required',
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
            $sktmListrik = PengajuanSktmListrik::create($request->all());

            return response()->json([
                'error' => false,
                'message' => 'Pengajuan berhasil ditambahkan.',
                'data' => $sktmListrik,
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
            $sktmListrik = PengajuanSktmListrik::with([
                'hubunganPengaju:id,jenis_hubungan',
                'pekerjaan:id,nama_pekerjaan',
                'penghasilan:id,rentang_penghasilan',
            ])->findOrFail($id);

            return new PengajuanSktmListrikResource($sktmListrik);
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
            $sktmListrik = PengajuanSktmListrik::findOrFail($id);
            $sktmListrik->update($request->all());

            return (new PengajuanSktmListrikResource($sktmListrik->load([
                'hubunganPengaju:id,jenis_hubungan',
                'pekerjaan:id,nama_pekerjaan',
                'penghasilan:id,rentang_penghasilan',
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
            $sktmListrik = PengajuanSktmListrik::findOrFail($id);
            $sktmListrik->delete();

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
