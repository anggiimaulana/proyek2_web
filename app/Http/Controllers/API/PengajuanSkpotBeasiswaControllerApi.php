<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengajuanSkpotBeasiswaResource;
use App\Models\PengajuanSkpotBeasiswa;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class PengajuanSkpotBeasiswaControllerApi extends Controller
{
    public function index()
    {
        $skpotBeasiswa = PengajuanSkpotBeasiswa::with([
            'hubunganPengaju:id,jenis_hubungan',
            'pekerjaanPengaju:id,nama_pekerjaan',
            'jkPengaju:id,jenis_kelamin',
            'agamaPengaju:id,nama_agama',
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
            'pekerjaan' => 'required',
            'alamat' => 'required',
            'penghasilan' => 'required',
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
            // Upload file
            $file = $request->file('file_kk');
            $namaFile = uniqid() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/kk', $namaFile);

            // Simpan data detail
            $skpotBeasiswa = PengajuanSkpotBeasiswa::create([
                'hubungan' => $request->hubungan,
                'nik' => $request->nik,
                'nama' => $request->nama,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jk' => $request->jk,
                'agama' => $request->agama,
                'nama_ortu' => $request->nama_ortu,
                'pekerjaan' => $request->pekerjaan,
                'alamat' => $request->alamat,
                'penghasilan' => $request->penghasilan,
                'file_kk' => $namaFile,
            ]);

            // Buat relasi ke pengajuan umum
            $pengajuan = $skpotBeasiswa->pengajuan()->create([
                'id_user_pengajuan' => 1,
                'id_admin' => null,
                'kategori_pengajuan' => 7,
                'detail_type' => PengajuanSkpotBeasiswa::class,
                'status_pengajuan' => 1,
                'catatan' => null,
                'id_admin_updated' => 1,
                'id_kuwu_updated' => 1,
            ]);

            return response()->json([
                'error' => false,
                'message' => 'Pengajuan Surat Keterangan Potensi Beasiswa berhasil ditambahkan.',
                'data' => [
                    'pengajuan' => $pengajuan,
                    'detail' => $skpotBeasiswa,
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
            $skpotBeasiswa = PengajuanSkpotBeasiswa::with([
                'hubunganPengaju:id,jenis_hubungan',
                'jkPengaju:id,jenis_kelamin',
                'pekerjaanPengaju:id,nama_pekerjaan',
                'agamaPengaju:id,nama_agama',
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
}
