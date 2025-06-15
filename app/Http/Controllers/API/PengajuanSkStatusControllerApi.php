<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengajuanSkStatusResource;
use App\Models\PengajuanSkStatus;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class PengajuanSkStatusControllerApi extends Controller
{
    public function index()
    {
        $skStatus = PengajuanSkStatus::with([
            'hubunganPengaju:id,jenis_hubungan',
            'idNikPengaju:id,nomor_nik',
            'idKkPengaju:id,nomor_kk',
            'jenisKelaminPengaju:id,jenis_kelamin',
            'agamaPengaju:id,nama_agama',
            'statusPerkawinanPengaju:id,status_perkawinan',
            'pekerjaanPengaju:id,nama_pekerjaan',
        ])->orderBy('id', 'desc')->paginate(5);

        return PengajuanSkStatusResource::collection($skStatus);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hubungan' => 'required|integer',
            'nik_id' => 'required|integer',
            'kk_id' => 'required|integer',
            'nama' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jk' => 'required|integer',
            'agama' => 'required|integer',
            'pekerjaan' => 'required|integer',
            'status_perkawinan' => 'required|integer',
            'alamat' => 'required|string',
            'file_kk' => 'required|file|mimes:pdf,jpg,jpeg,png',
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
            $file->storeAs('/uploads/kk', $namaFile);

            $skStatus = PengajuanSkStatus::create([
                'hubungan' => $request->hubungan,
                'nik_id' => $request->nik_id,
                'kk_id' => $request->kk_id,
                'nama' => $request->nama,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jk' => $request->jk,
                'agama' => $request->agama,
                'pekerjaan' => $request->pekerjaan,
                'status_perkawinan' => $request->status_perkawinan,
                'alamat' => $request->alamat,
                'file_kk' => 'uploads/kk/' . $namaFile,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $user = Auth::guard('client')->user();

            // Buat relasi ke tabel pengajuan umum
            $pengajuan = $skStatus->pengajuan()->create([
                'id_user_pengajuan' => $user->id,
                'id_admin' => null,
                'kategori_pengajuan' => 4,
                'url_file' => '/file/preview/sks/' . $skStatus->id,
                'detail_type' => PengajuanSkStatus::class,
                'status_pengajuan' => 1,
                'catatan' => null,
                'id_admin_updated' => 1,
                'id_kuwu_updated' => 1,
            ]);

            return response()->json([
                'error' => false,
                'message' => 'Pengajuan berhasil ditambahkan.',
                'data' => [
                    'pengajuan' => $pengajuan,
                    'detail' => $skStatus,
                    'file_url' => asset('storage/uploads/kk/' . $namaFile),
                ],
            ], HttpFoundationResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Pengajuan gagal ditambahkan.',
                'data' => $e->getMessage(),
            ], HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(string $id)
    {
        try {
            $skStatus = PengajuanSkStatus::with([
                'hubunganPengaju:id,jenis_hubungan',
                'idNikPengaju:id,nomor_nik',
                'idKkPengaju:id,nomor_kk',
                'jenisKelaminPengaju:id,jenis_kelamin',
                'agamaPengaju:id,nama_agama',
                'statusPerkawinanPengaju:id,status_perkawinan',
                'pekerjaanPengaju:id,nama_pekerjaan',
            ])->findOrFail($id);

            return new PengajuanSkStatusResource($skStatus);
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
            $data = PengajuanSkStatus::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'hubungan' => 'required|integer',
                'nama' => 'required|string',
                'nik_id' => 'required|integer',
                'kk_id' => 'required|integer',
                'tempat_lahir' => 'required|string',
                'tanggal_lahir' => 'required|date',
                'jk' => 'required|integer',
                'agama' => 'required|integer',
                'pekerjaan' => 'required|integer',
                'status_perkawinan' => 'required|integer',
                'alamat' => 'required|string',
                'file_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validasi gagal.',
                    'data' => $validator->errors(),
                ], 422);
            }

            // Cek jika status pengajuan ditolak, wajib upload ulang file
            if ($data->pengajuan && $data->pengajuan->status_pengajuan == 3) {
                if (!$request->hasFile('file_kk')) {
                    return response()->json([
                        'error' => true,
                        'message' => 'File KK wajib di-upload ulang karena pengajuan ditolak.',
                    ], 400);
                }
            }

            // Cek dan ganti file jika diupload ulang
            if ($request->hasFile('file_kk')) {
                // Hapus file lama
                if ($data->file_kk && file_exists(storage_path('app/uploads/kk/' . $data->file_kk))) {
                    unlink(storage_path('app/uploads/kk/' . $data->file_kk));
                }

                $file = $request->file('file_kk');
                $namaFile = uniqid() . '_' . $file->getClientOriginalName();
                $file->storeAs('uploads/kk', $namaFile);
                $data->file_kk = 'uploads/kk/' . $namaFile;
            }

            // Update data lainnya
            $data->update([
                'hubungan' => $request->hubungan,
                'nik_id' => $request->nik_id,
                'kk_id' => $request->kk_id,
                'nama' => $request->nama,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jk' => $request->jk,
                'agama' => $request->agama,
                'pekerjaan' => $request->pekerjaan,
                'status_perkawinan' => $request->status_perkawinan,
                'alamat' => $request->alamat,
                'updated_at' => now(),
            ]);

            // Reset status pengajuan jika sebelumnya ditolak
            $pengajuan = $data->pengajuan;
            if ($pengajuan && $pengajuan->status_pengajuan == 3 && $request->hasFile('file_kk')) {
                $pengajuan->update([
                    'status_pengajuan' => 5,
                    'catatan' => $data->catatan,
                    'updated_at' => now(),
                ]);
            }

            return response()->json([
                'error' => false,
                'message' => 'Data pengajuan berhasil diperbarui.',
                'data' => new PengajuanSkStatusResource($data),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Data tidak ditemukan.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat memperbarui data.',
                'data' => $e->getMessage(),
            ], 500);
        }
    }
}
