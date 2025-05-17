<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kuwu;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class KuwuControllerApi extends Controller
{
    public function index()
    {
        $kuwu = Cache::remember('kuwu_list', 1296000, function () {
            return Kuwu::query()
                ->select('id', 'nip', 'nama', 'jk', 'status', 'agama', 'email')
                ->orderByDesc('id')
                ->get();
        });

        return response()->json([
            'error' => false,
            'message' => 'Data Akun Kuwu',
            'data' => $kuwu
        ], HttpFoundationResponse::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required|unique:kuwu,nip',
            'nama' => 'required',
            'jk' => 'required',
            'status' => 'required',
            'agama' => 'required',
            'email' => 'required|unique:kuwu,email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $kuwu = Kuwu::create($request->all());
            $response = [
                'error' => false,
                'message' => 'Data Akun Kuwu Berhasil Ditambahkan',
                'data' => $kuwu
            ];

            return response()->json($response, HttpFoundationResponse::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Data Akun Kuwu Gagal Ditambahkan',
            ]);
        }
    }

    public function show(string $id)
    {
        try {
            $kuwu = Kuwu::where('id', $id)->firstOrFail();
            return response()->json([
                'error' => false,
                'message' => 'Data Akun Kuwu',
                'data' => $kuwu,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Data Akun Kuwu Tidak Ditemukan',
            ], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $kuwu = Kuwu::findOrFail($id);
            $kuwu->update($request->all());

            return response()->json([
                'error' => false,
                'message' => 'Data Akun Kuwu Berhasil Diperbarui',
                'data' => $kuwu,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Data Akun Tidak Ditemukan',
            ], 404);
        }
    }

    public function destroy(string $id)
    {
        try {
            $kuwu = Kuwu::findOrFail($id);
            $kuwu->delete();
            return response()->json([
                'error' => false,
                'message' => 'Data Akun Kuwu Berhasil Dihapus',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Data Akun Tidak Ditemukan',
            ], 404);
        }
    }
}
