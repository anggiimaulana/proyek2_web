<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengajuanSktmSekolahResource;
use App\Models\PengajuanSktmSekolah;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class PengajuanSktmSekolahControllerApi extends Controller
{
    public function index()
    {
        $sktm = PengajuanSktmSekolah::with([
            'idKkPengaju:id,nomor_kk',
            'idNikPengaju:id,nomor_nik',
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
            'hubungan' => 'required|integer',
            'nik_id' => 'required|integer',
            'kk_id' => 'required|integer',
            'nama' => 'required|string',
            'tempat_lahir_ortu' => 'required|string',
            'tanggal_lahir_ortu' => 'required|date',
            'pekerjaan' => 'required|integer',
            'nama_anak' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jk' => 'required|integer',
            'agama' => 'required|integer',
            'asal_sekolah' => 'required|string',
            'kelas' => 'required|string',
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
            $file = $request->file('file_kk');
            $namaFile = uniqid() . '_' . $file->getClientOriginalName();
            $file->storeAs('/uploads/kk', $namaFile);

            $sktm = PengajuanSktmSekolah::create([
                'hubungan' => $request->hubungan,
                'nik_id' => $request->nik_id,
                'kk_id' => $request->kk_id,
                'nama' => $request->nama,
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
                'file_kk' => 'uploads/kk/' . $namaFile,
            ]);

            $user = Auth::guard('client')->user();

            // Buat relasi ke tabel pengajuan umum
            $pengajuan = $sktm->pengajuan()->create([
                'id_user_pengajuan' => $user->id,
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
            $sktm = PengajuanSktmSekolah::with([
                'idKkPengaju:id,nomor_kk',
                'idNikPengaju:id,nomor_nik',
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

    public function update(Request $request, string $id)
    {
        try {
            $data = PengajuanSktmSekolah::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'hubungan' => 'required|integer',
                'nik_id' => 'required|integer',
                'kk_id' => 'required|integer',
                'nama' => 'required|string',
                'tempat_lahir_ortu' => 'required|string',
                'tanggal_lahir_ortu' => 'required|date',
                'pekerjaan' => 'required|integer',
                'nama_anak' => 'required|string',
                'tempat_lahir' => 'required|string',
                'tanggal_lahir' => 'required|date',
                'jk' => 'required|integer',
                'agama' => 'required|integer',
                'asal_sekolah' => 'required|string',
                'kelas' => 'required|string',
                'alamat' => 'required|string',
                'file_kk' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
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

            // Reset status pengajuan jika sebelumnya ditolak
            $pengajuan = $data->pengajuan;
            if ($pengajuan && $pengajuan->status_pengajuan == 3 && $request->hasFile('file_kk')) {
                $pengajuan->update([
                    'status_pengajuan' => 5,
                    'catatan' => null,
                    'updated_at' => now(),
                ]);
            }

            return response()->json([
                'error' => false,
                'message' => 'Data pengajuan berhasil diperbarui.',
                'data' => new PengajuanSktmSekolahResource($data),
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
