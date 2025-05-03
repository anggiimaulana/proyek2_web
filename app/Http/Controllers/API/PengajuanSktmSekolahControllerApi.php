<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengajuanSktmSekolahResource;
use App\Models\PengajuanSktmSekolah;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class PengajuanSktmSekolahControllerApi extends Controller
{
    public function index()
    {
        $sktm = PengajuanSktmSekolah::with([
            'hubunganPengaju:id,jenis_hubungan',
            'jenisKelaminPengaju:id,jenis_kelamin',
            'agamaPengaju:id,nama_agama',
            'pekerjaanPengaju:id,nama_pekerjaan',
        ])->orderBy('id', 'desc')->paginate(5);

        return PengajuanSktmSekolahResource::collection($sktm);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hubungan' => 'required',
            'nama' => 'required',
            'nik' => 'required',
            'tempat_lahir_ortu' => 'required',
            'tanggal_lahir_ortu' => 'required|date',
            'pekerjaan' => 'required',
            'nama_anak' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'jk' => 'required',
            'agama' => 'required',
            'asal_sekolah' => 'required',
            'kelas' => 'required',
            'alamat' => 'required',
            'file_kk' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validasi gagal.',
                'data' => $validator->errors(),
            ], HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $file = $request->file('file_kk');
            $namaFile = uniqid() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/kk', $namaFile);

            $sktm = PengajuanSktmSekolah::create([
                'hubungan' => $request->hubungan,
                'nama' => $request->nama,
                'nik' => $request->nik,
                'tempat_lahir_ortu' => $request->tempat_lahir_ortu,
                'tanggal_lahir_ortu' => $request->tanggal_lahir_ortu,
                'pekerjaan' => $request->pekerjaan,
                'nama_anak' => $request->nama_anak,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jk' => $request->jk,
                'agama' => $request->agama,
                'asal_sekolah' => $request->asal_sekolah,
                'kelas' => $request->kelas,
                'alamat' => $request->alamat,
                'file_kk' => $namaFile,
            ]);

            $pengajuan = $sktm->pengajuan()->create([
                'id_user_pengajuan' => 1,
                'id_admin' => null,
                'kategori_pengajuan' => 2,
                'detail_type' => PengajuanSktmSekolah::class,
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
            $sktm = PengajuanSktmSekolah::with([
                'hubunganPengaju:id,jenis_hubungan',
                'jenisKelaminPengaju:id,jenis_kelamin',
                'agamaPengaju:id,nama_agama',
                'pekerjaanPengaju:id,nama_pekerjaan',
            ])->findOrFail($id);

            return new PengajuanSktmSekolahResource($sktm);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Data Pengajuan Tidak Ditemukan',
            ], 404);
        }
    }
}
