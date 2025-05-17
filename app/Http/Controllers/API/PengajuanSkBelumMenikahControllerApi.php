<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSkBelumMenikah;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use App\Http\Resources\PengajuanSkBelumMenikahResource;
use Illuminate\Support\Facades\Auth;

class PengajuanSkBelumMenikahControllerApi extends Controller
{
    public function index()
    {
        $skBelumMenikah = PengajuanSkBelumMenikah::with([
            'hubunganPengaju:id,jenis_hubungan',
            'idKkPengaju:id,nomor_kk',
            'idNikPengaju:id,nomor_nik',
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
            'hubungan' => 'required|integer',
            'kk_id' => 'required|integer',
            'nik_id' => 'required|integer',
            'nama' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jk' => 'required|integer',
            'agama' => 'required|integer',
            'pekerjaan' => 'required|integer',
            'status_perkawinan' => 'required|integer',
            'alamat' => 'required|string',
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
            $file->storeAs('uploads/kk', $namaFile);

            // Simpan data detail
            $skBelumMenikah = PengajuanSkBelumMenikah::create([
                'hubungan' => $request->hubungan,
                'nama' => $request->nama,
                'kk_id' => $request->kk_id,
                'nik_id' => $request->nik_id,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jk' => $request->jk,
                'agama' => $request->agama,
                'pekerjaan' => $request->pekerjaan,
                'status_perkawinan' => $request->status_perkawinan,
                'alamat' => $request->alamat,
                'file_kk' => 'uploads/kk/' . $namaFile,
            ]);

            $user = Auth::guard('client')->user();

            // Buat relasi ke tabel pengajuan umum
            $pengajuan = $skBelumMenikah->pengajuan()->create([
                'id_user_pengajuan' => $user->id,
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
                    'file_url' => asset('storage/uploads/kk/' . $namaFile),

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
                'idKkPengaju:id,nomor_kk',
                'idNikPengaju:id,nomor_nik',
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

    public function update(Request $request, string $id)
    {
        try {
            $data = PengajuanSkBelumMenikah::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'hubungan' => 'required|integer',
                'kk_id' => 'required|integer',
                'nik_id' => 'required|integer',
                'nama' => 'required|string',
                'tempat_lahir' => 'required|string',
                'tanggal_lahir' => 'required|date',
                'jk' => 'required|integer',
                'agama' => 'required|integer',
                'pekerjaan' => 'required|integer',
                'status_perkawinan' => 'required|integer',
                'alamat' => 'required|string',
                'file_kk' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validasi gagal.',
                    'data' => $validator->errors(),
                ], 422);
            }

            // Jika status pengajuan sebelumnya ditolak
            if ($data->pengajuan && $data->pengajuan->status_pengajuan == 3 && !$request->hasFile('file_kk')) {
                return response()->json([
                    'error' => true,
                    'message' => 'File KK wajib di-upload ulang karena pengajuan ditolak.',
                ], 400);
            }

            // Jika ada file baru
            if ($request->hasFile('file_kk')) {
                // Hapus file lama jika ada
                if ($data->file_kk && file_exists(storage_path('app/uploads/kk/' . $data->file_kk))) {
                    unlink(storage_path('app/uploads/kk/' . $data->file_kk));
                }

                $file = $request->file('file_kk');
                $namaFile = uniqid() . '_' . $file->getClientOriginalName();
                $file->storeAs('uploads/kk', $namaFile);
                $data->file_kk = 'uploads/kk/' . $namaFile;
            }

            $data->update([
                'hubungan' => $request->hubungan,
                'kk_id' => $request->kk_id,
                'nik_id' => $request->nik_id,
                'nama' => $request->nama,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jk' => $request->jk,
                'agama' => $request->agama,
                'pekerjaan' => $request->pekerjaan,
                'status_perkawinan' => $request->status_perkawinan,
                'alamat' => $request->alamat,
            ]);

            // Reset status pengajuan jika sebelumnya ditolak
            if ($data->pengajuan && $data->pengajuan->status_pengajuan == 3) {
                $data->pengajuan->update([
                    'status_pengajuan' => 5,
                    'catatan' => null,
                    'updated_at' => now(),
                ]);
            }

            return response()->json([
                'error' => false,
                'message' => 'Data pengajuan berhasil diperbarui.',
                'data' => new PengajuanSkBelumMenikahResource($data),
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
