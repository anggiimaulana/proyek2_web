<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengajuanSkpotBeasiswaResource;
use App\Models\PengajuanSkpotBeasiswa;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $file->storeAs('/uploads/kk', $namaFile);

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
            $user = Auth::guard('client')->user();

            // Buat relasi ke tabel pengajuan umum
            $pengajuan = $skpotBeasiswa->pengajuan()->create([
                'id_user_pengajuan' => $user->id,
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

    public function update(Request $request, string $id)
    {
        try {
            $data = PengajuanSkpotBeasiswa::findOrFail($id);

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
                'file_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
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
                $data->file_kk = $namaFile;
            }

            // Update data lainnya
            $data->update([
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
                'data' => new PengajuanSkpotBeasiswaResource($data),
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
