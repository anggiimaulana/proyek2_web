<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class BeritaControllerApi extends Controller
{
    public function index()
    {
        $berita = Berita::select('id', 'judul', 'kategori', 'isi', 'created_at', 'penulis', 'gambar')->orderByDesc('id')->get();

        $berita->transform(function ($item) {
            $item->gambar = url('storage/' . $item->gambar);
            return $item;
        });

        return response()->json([
            'error' => false,
            'message' => 'Data Berita',
            'data' => $berita,
        ], HttpFoundationResponse::HTTP_OK);
    }

    public function showHome()
    {
        $berita = Berita::select('id', 'judul', 'kategori', 'isi', 'created_at', 'penulis', 'gambar')->orderByDesc('id')->limit(3)->get();
        $berita->transform(function ($item) {
            $item->gambar = url('storage/' . $item->gambar);
            return $item;
        });

        return response()->json([
            'error' => false,
            'message' => 'Data Berita',
            'data' => $berita,
        ], HttpFoundationResponse::HTTP_OK);
    }

    public function show(string $id)
    {
        $item = Berita::select('id', 'judul', 'kategori', 'isi', 'created_at', 'penulis', 'gambar')->findOrFail($id);
        $item->gambar = url('storage/' . $item->gambar);

        return response()->json([
            'error' => false,
            'message' => 'Data Berita',
            'data' => $item,
        ]);
    }
}
