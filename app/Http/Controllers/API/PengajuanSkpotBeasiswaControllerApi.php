<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengajuanSkpotBeasiswaResource;
use App\Models\PengajuanSkpotBeasiswa;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class PengajuanSkpotBeasiswaControllerApi extends Controller
{
    public function index()
    {
        $skpotBeasiswa = PengajuanSkpotBeasiswa::with([
            'hubunganPengaju:id,jenis_hubungan',
            'jkPpengaju:id,jenis_kelamin',
            'agamaPpengaju:id,nama_agama',
            'penghasilanPengaju:id,rentang_penghasilan',
        ])->orderBy('id', 'desc')->paginate(5);

        return PengajuanSkpotBeasiswaResource::collection($skpotBeasiswa);
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
            'agama' => 'required',
            'nama_ortu' => 'required',
            'penghasilan' => 'required',
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
            $skpotBeasiswa = PengajuanSkpotBeasiswa::create($request->all());

            return response()->json([
                'error' => false,
                'message' => 'Pengajuan Surat Keterangan Potensi Beasiswa berhasil ditambahkan.',
                'data' => $skpotBeasiswa,
            ], HttpFoundationResponse::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat menambahkan data.',
                'data' => $e,
            ], HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function show(string $id)
    {
        try {
            $skpotBeasiswa = PengajuanSkpotBeasiswa::with([
                'hubunganPengaju:id,jenis_hubungan',
                'jkPpengaju:id,jenis_kelamin',
                'agamaPpengaju:id,nama_agama',
                'penghasilanPengaju:id,rentang_penghasilan',
            ])->findOrFail($id);

            return new PengajuanSkpotBeasiswaResource($skpotBeasiswa);
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
            $skpotBeasiswa = PengajuanSkpotBeasiswa::findOrFail($id);
            $skpotBeasiswa->update($request->all());

            return (new PengajuanSkpotBeasiswaResource($skpotBeasiswa->load([
                'hubunganPengaju:id,jenis_hubungan',
                'jkPpengaju:id,jenis_kelamin',
                'agamaPpengaju:id,nama_agama',
                'penghasilanPengaju:id,rentang_penghasilan',
            ])))->additional([
                'error' => false,
                'message' => 'Pengajuan berhasil diperbarui'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Data Pengajuan tidak ditemukan.',
            ]);
        }
    }

    public function destroy(string $id)
    {
        try {
            $skpotBeasiswa = PengajuanSkpotBeasiswa::findOrFail($id);
            $skpotBeasiswa->delete();

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
