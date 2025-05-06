<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengajuanSkUsahaResource;
use App\Models\PengajuanSkUsaha;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PengajuanSkUsahaControllerApi extends Controller
{
    public function index()
    {
        $skUsaha = PengajuanSkUsaha::with([
            'hubunganPengaju:id,jenis_hubungan',
            'jenisKelaminPengaju:id,jenis_kelamin',
            'statusPerkawinanPengaju:id,status_perkawinan',
            'pekerjaanPengaju:id,nama_pekerjaan',
        ])->orderBy('id', 'desc')->paginate(5);

        return PengajuanSkUsahaResource::collection($skUsaha);
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
            'status_perkawinan' => 'required',
            'pekerjaan' => 'required',
            'alamat' => 'required',
            'nama_usaha' => 'required',
            'file_ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validasi gagal.',
                'data' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $file = $request->file('file_ktp');
            $namaFile = uniqid() . '_' . $file->getClientOriginalName();
            $file->storeAs('uploads/kk', $namaFile);

            $sku = PengajuanSkUsaha::create([
                'hubungan' => $request->hubungan,
                'nama' => $request->nama,
                'nik' => $request->nik,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jk' => $request->jk,
                'status_perkawinan' => $request->status_perkawinan,
                'pekerjaan' => $request->pekerjaan,
                'alamat' => $request->alamat,
                'nama_usaha' => $request->nama_usaha,
                'file_ktp' => $namaFile,
            ]);

            $user = Auth::guard('client')->user();
            $pengajuan = $sku->pengajuan()->create([
                'id_user_pengajuan' => $user->id,
                'id_admin' => null,
                'kategori_pengajuan' => 8,
                'detail_type' => PengajuanSkUsaha::class,
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
                    'detail' => $sku,
                    'file_url' => asset('storage/uploads/kk/' . $namaFile),
                ],
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'data' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(string $id)
    {
        try {
            $sku = PengajuanSkUsaha::with([
                'hubunganPengaju:id,jenis_hubungan',
                'jenisKelaminPengaju:id,jenis_kelamin',
                'statusPerkawinanPengaju:id,status_perkawinan',
                'pekerjaanPengaju:id,nama_pekerjaan',
            ])->findOrFail($id);

            return new PengajuanSkUsahaResource($sku);
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
            $data = PengajuanSkUsaha::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'hubungan' => 'required',
                'nama' => 'required',
                'nik' => 'required',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required|date',
                'jk' => 'required',
                'status_perkawinan' => 'required',
                'pekerjaan' => 'required',
                'alamat' => 'required',
                'nama_usaha' => 'required',
                'file_ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validasi gagal.',
                    'data' => $validator->errors(),
                ], 422);
            }

            $pengajuan = $data->pengajuan;
            $namaFile = $data->file_ktp;

            if ($pengajuan && $pengajuan->status_pengajuan == 3 && !$request->hasFile('file_ktp')) {
                return response()->json([
                    'error' => true,
                    'message' => 'File KTP wajib di-upload ulang karena pengajuan ditolak.',
                ], 400);
            }

            if ($request->hasFile('file_ktp')) {
                if ($data->file_ktp && Storage::exists('uploads/kk/' . $data->file_ktp)) {
                    Storage::delete('uploads/kk/' . $data->file_ktp);
                }

                $file = $request->file('file_ktp');
                $namaFile = uniqid() . '_' . $file->getClientOriginalName();
                $file->storeAs('uploads/kk', $namaFile);
            }

            $data->update([
                'hubungan' => $request->hubungan,
                'nama' => $request->nama,
                'nik' => $request->nik,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jk' => $request->jk,
                'status_perkawinan' => $request->status_perkawinan,
                'pekerjaan' => $request->pekerjaan,
                'alamat' => $request->alamat,
                'nama_usaha' => $request->nama_usaha,
                'file_ktp' => $namaFile,
            ]);

            if ($pengajuan && $pengajuan->status_pengajuan == 3 && $request->hasFile('file_ktp')) {
                $pengajuan->update([
                    'status_pengajuan' => 5,
                    'catatan' => null,
                    'updated_at' => now(),
                ]);
            }

            return response()->json([
                'error' => false,
                'message' => 'Data pengajuan berhasil diperbarui.',
                'data' => new PengajuanSkUsahaResource($data),
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
