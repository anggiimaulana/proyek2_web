<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengajuanSktmBeasiswaResource;
use App\Models\PengajuanSktmBeasiswa;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class PengajuanSktmBeasiswaControllerApi extends Controller
{
    public function index()
    {
        $sktmbeasiswa = PengajuanSktmBeasiswa::with([
            'hubunganPengaju:id,jenis_hubungan',
            'idKkPengaju:id,nomor_kk',
            'idNikPengaju:id,nomor_nik',
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
            'hubungan' => 'required|integer',
            'nik_id' => 'required|integer',
            'kk_id' => 'required|integer',
            'nama_anak' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'suku' => 'required|string',
            'jk' => 'required|integer',
            'agama' => 'required|integer',
            'pekerjaan_anak' => 'required|integer',
            'nama' => 'required|string',
            'nama_ibu' => 'required|string',
            'pekerjaan_ortu' => 'required|integer',
            'alamat' => 'required|string',
            'file_kk' => 'required|file|mimes:pdf,jpg,jpeg,png',
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
            $file->storeAs('/uploads/kk', $namaFile);

            $sktm = PengajuanSktmBeasiswa::create([
                'hubungan' => $request->hubungan,
                'kk_id' => $request->kk_id,
                'nik_id' => $request->nik_id,
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
                'file_kk' => 'uploads/kk/' . $namaFile,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $user = Auth::guard('client')->user();

            // Buat relasi ke tabel pengajuan umum
            $pengajuan = $sktm->pengajuan()->create([
                'id_user_pengajuan' => $user->id,
                'id_admin' => null,
                'kategori_pengajuan' => 2,
                'url_file' => '/file/preview/sktm-beasiswa/' . $sktm->id,
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
            $sktmbeasiswa = PengajuanSktmBeasiswa::with([
                'hubunganPengaju:id,jenis_hubungan',
                'idKkPengaju:id,nomor_kk',
                'idNikPengaju:id,nomor_nik',
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

    public function update(Request $request, string $id)
    {
        try {
            $data = PengajuanSktmBeasiswa::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'hubungan' => 'required',
                'nik_id' => 'required',
                'kk_id' => 'required',
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
                'file_kk' => 'required|file|mimes:pdf,jpg,jpeg,png',
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
                'kk_id' => $request->kk_id,
                'nik_id' => $request->nik_id,
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
                'data' => new PengajuanSktmBeasiswaResource($data),
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
