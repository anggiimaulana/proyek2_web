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
            'file_kk' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Pengajuan gagal ditambahkan.',
                'data' => $validator->errors(),
            ], HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $file = $request->file('file_kk');
            $namaFile = uniqid() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/kk', $namaFile);

            $sktm = PengajuanSktmBeasiswa::create([
                'hubungan' => $request->hubungan,
                'nama_anak' => $request->nama_anak,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'suku' => $request->suku,
                'jk' => $request->jk,
                'agama' => $request->agama,
                'pekerjaan_anak' => $request->pekerjaan_anak,
                'nama' => $request->nama,
                'nama_ibu' => $request->nama_ibu,
                'pekerjaan_ortu' => $request->pekerjaan_ortu,
                'alamat' => $request->alamat,
                'file_kk' => $namaFile,
            ]);

            $pengajuan = $sktm->pengajuan()->create([
                'id_user_pengajuan' => 1,
                'id_admin' => null,
                'kategori_pengajuan' => 2,
                'detail_type' => PengajuanSktmBeasiswa::class,
                'status_pengajuan' => 1,
                'catatan' => null,
                'id_admin_updated' => 1,
                'id_kuwu_updated' => 1,
            ]);

            return response()->json([
                'error' => false,
                'message' => 'Pengajuan dan file berhasil disimpan.',
                'data' => [
                    'pengajuan' => $pengajuan,
                    'detail' => $sktm,
                    'file_url' => asset('storage/kk/' . $namaFile),
                ],
            ], HttpFoundationResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'data' => $e->getMessage(),
            ], HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
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
}
