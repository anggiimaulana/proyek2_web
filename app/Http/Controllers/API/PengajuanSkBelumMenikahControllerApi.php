<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSkBelumMenikah;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use App\Http\Resources\PengajuanSkBelumMenikahResource;

class PengajuanSkBelumMenikahControllerApi extends Controller
{
    public function index()
    {
        $skBelumMenikah = PengajuanSkBelumMenikah::with([
            'hubunganPengaju:id,jenis_hubungan',
            'jenisKelaminPengaju:id,jenis_kelamin',
            'agamaPengaju:id,nama_agama',
            'pekerjaanPengaju:id,nama_pekerjaan',
            'statusPerkawinanPengaju:id,status_perkawinan',
        ])->orderBy('id', 'desc')->paginate(5);

        return PengajuanSkBelumMenikahResource::collection($skBelumMenikah);
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
            'agama' => 'required',
            'pekerjaan' => 'required',
            'status_perkawinan' => 'required',
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
            // Upload file KK
            $file = $request->file('file_kk');
            $namaFile = uniqid() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/kk', $namaFile);

            // Simpan data detail
            $skBelumMenikah = PengajuanSkBelumMenikah::create([
                'hubungan' => $request->hubungan,
                'nama' => $request->nama,
                'nik' => $request->nik,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jk' => $request->jk,
                'agama' => $request->agama,
                'pekerjaan' => $request->pekerjaan,
                'status_perkawinan' => $request->status_perkawinan,
                'alamat' => $request->alamat,
                'file_kk' => $namaFile,
            ]);

            // Buat relasi ke tabel pengajuan umum
            $pengajuan = $skBelumMenikah->pengajuan()->create([
                'id_user_pengajuan' => 1,
                'id_admin' => null,
                'kategori_pengajuan' => 5,
                'detail_type' => PengajuanSkBelumMenikah::class,
                'status_pengajuan' => 1,
                'catatan' => null,
                'id_admin_updated' => 1,
                'id_kuwu_updated' => 1,
            ]);

            return response()->json([
                'error' => false,
                'message' => 'Pengajuan Surat Keterangan Belum Menikah berhasil ditambahkan.',
                'data' => [
                    'pengajuan' => $pengajuan,
                    'detail' => $skBelumMenikah,
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
            $skBelumMenikah = PengajuanSkBelumMenikah::with([
                'hubunganPengaju:id,jenis_hubungan',
                'jenisKelaminPengaju:id,jenis_kelamin',
                'agamaPengaju:id,nama_agama',
                'pekerjaanPengaju:id,nama_pekerjaan',
                'statusPerkawinanPengaju:id,status_perkawinan',
            ])->findOrFail($id);

            return new PengajuanSkBelumMenikahResource($skBelumMenikah);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Data Pengajuan Tidak Ditemukan',
            ], 404);
        }
    }
}
