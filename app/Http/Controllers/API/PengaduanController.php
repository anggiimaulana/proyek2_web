<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class PengaduanController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis_layanan' => 'required|string',
            'keluhan' => 'required|string',
            'lokasi' => 'required|string',
            'kategori' => 'required|string',
            'gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validasi gagal.',
                'data' => $validator->errors(),
            ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $path = $request->file('gambar')->store('pengaduan', 'public');
            $user = $request->user(); // dari sanctum

            $pengaduan = Pengaduan::create([
                'client_id' => $user->id,
                'kategori' => $request->kategori,
                'jenis_layanan' => $request->jenis_layanan,
                'keluhan' => $request->keluhan,
                'lokasi' => $request->lokasi,
                'gambar' => $path,
                'status' => 'Menunggu',
            ]);

            return response()->json([
                'error' => false,
                'message' => 'Pengaduan berhasil dikirim.',
                'data' => $pengaduan,
            ], HttpResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat menyimpan pengaduan.',
                'data' => $e->getMessage(),
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function showByUser($id_user)
    {
        try {
            $pengaduan = Pengaduan::with('client:id') // pastikan relasi client() ada di model
                ->where('client_id', $id_user)
                ->orderBy('id', 'desc')
                ->paginate(5);

            return response()->json([
                'error' => false,
                'message' => 'Data pengaduan berhasil diambil.',
                'data' => $pengaduan,
            ], HttpResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Gagal mengambil data pengaduan.',
                'data' => $e->getMessage(),
            ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
