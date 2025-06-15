<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengajuanResource;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class PengajuanControllerApi extends Controller
{
    public function index()
    {
        $pengajuan = Pengajuan::with([
            'userPengajuan:id',
            'kategoriPengajuan:id,nama_kategori',
            'statusPengajuan:id,status',
        ])->orderBy('updated_at', 'desc')->paginate(10);

        return PengajuanResource::collection($pengajuan);
    }

    public function showByUser($id_user)
    {
        $pengajuan = Pengajuan::with([
            'userPengajuan:id',
            'kategoriPengajuan:id,nama_kategori',
            'statusPengajuan:id,status',
        ])
            ->where('id_user_pengajuan', $id_user)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return PengajuanResource::collection($pengajuan);
    }

    public function destroy($id)
    {
        // Validasi input id harus angka dan harus ada di db
        if (!is_numeric($id)) {
            return response()->json([
                'message' => 'ID harus berupa angka.'
            ], HttpFoundationResponse::HTTP_BAD_REQUEST);
        }

        $pengajuan = Pengajuan::find($id);

        if (!$pengajuan) {
            return response()->json([
                'message' => 'Data pengajuan tidak ditemukan.'
            ], HttpFoundationResponse::HTTP_NOT_FOUND);
        }

        try {
            // Hapus pengajuan, model event deleting akan otomatis hapus parent morph juga
            $pengajuan->delete();

            return response()->json([
                'message' => 'Pengajuan berhasil dihapus.'
            ], HttpFoundationResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus pengajuan.',
                'error' => $e->getMessage()
            ], HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
