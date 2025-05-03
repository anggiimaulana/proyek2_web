<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengajuanSkPekerjaanResource;
use App\Models\PengajuanSkPekerjaan;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

            $skPekerjaan = PengajuanSkPekerjaan::create([
                'hubungan' => $request->hubungan,
                'nik' => $request->nik,
                'nama' => $request->nama,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jk' => $request->jk,
                'status_perkawinan' => $request->status_perkawinan,
                'pekerjaan_terdahulu' => $request->pekerjaan_terdahulu,
                'pekerjaan_sekarang' => $request->pekerjaan_sekarang,
                'alamat' => $request->alamat,
                'file_kk' => $namaFile,
            ]);

            $pengajuan = $skPekerjaan->pengajuan()->create([
                'id_user_pengajuan' => 1,
                'id_admin' => null,
                'kategori_pengajuan' => 6,
                'detail_type' => PengajuanSkPekerjaan::class,
                'status_pengajuan' => 1,
                'catatan' => null,
                'id_admin_updated' => 1,
                'id_kuwu_updated' => 1,
            ]);

            return response()->json([
                'error' => false,
                'message' => 'Pengajuan Surat Keterangan Pekerjaan berhasil ditambahkan.',
                'data' => [
                    'pengajuan' => $pengajuan,
                    'detail' => $skPekerjaan,
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
}
