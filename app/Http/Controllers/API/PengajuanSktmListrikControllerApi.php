<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengajuanSktmListrikResource;
use App\Models\PengajuanSktmListrik;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $file->storeAs('/uploads/kk', $namaFile);

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

            $user = Auth::guard('client')->user();

            // Buat relasi ke tabel pengajuan umum
            $pengajuan = $sktm->pengajuan()->create([
                'id_user_pengajuan' => $user->id,
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

    public function update(Request $request, string $id)
    {
        try {
            $data = PengajuanSktmListrik::findOrFail($id);

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
                'data' => new PengajuanSktmListrikResource($data),
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
