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
            'pekerjaanPengaju:id,nama_pekerjaan',
            'penghasilanPengaju:id,rentang_penghasilan',
            'agamaPengaju:id,nama_agama',
            'jenisKelaminPengaju:id,jenis_kelamin',
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
            'agama' => 'required',
            'jk' => 'required',
            'umur' => 'required|numeric',
            'pekerjaan' => 'required',
            'penghasilan' => 'required',
            'nama_pln' => 'required',
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

            $sktm = PengajuanSktmListrik::create([
                'hubungan' => $request->hubungan,
                'nama' => $request->nama,
                'nik' => $request->nik,
                'alamat' => $request->alamat,
                'agama' => $request->agama,
                'jk' => $request->jk,
                'umur' => $request->umur,
                'pekerjaan' => $request->pekerjaan,
                'penghasilan' => $request->penghasilan,
                'nama_pln' => $request->nama_pln,
                'file_kk' => $namaFile,
            ]);

            $pengajuan = $sktm->pengajuan()->create([
                'id_user_pengajuan' => 1,
                'id_admin' => null,
                'kategori_pengajuan' => 1, 
                'detail_type' => PengajuanSktmListrik::class,
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
            $sktmListrik = PengajuanSktmListrik::with([
                'hubunganPengaju:id,jenis_hubungan',
                'pekerjaanPengaju:id,nama_pekerjaan',
                'penghasilanPengaju:id,rentang_penghasilan',
                'agamaPengaju:id,nama_agama',
                'jenisKelaminPengaju:id,jenis_kelamin',
            ])->findOrFail($id);

            return new PengajuanSktmListrikResource($sktmListrik);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Data Pengajuan Tidak Ditemukan',
            ], 404);
        }
    }
}
