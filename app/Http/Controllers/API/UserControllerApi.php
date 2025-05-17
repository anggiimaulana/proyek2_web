<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class UserControllerApi extends Controller
{
    public function index()
    {
        $users = Cache::remember('users_list', 1296000, function () {
            return User::query()
                ->select('id', 'nip', 'nama', 'jk', 'status', 'agama', 'email')
                ->orderByDesc('id')
                ->get();
        });
        return response()->json([
            'error' => false,
            'message' => 'Data Akun Admin',
            'data' => $users
        ], HttpFoundationResponse::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required|unique:users,nip',
            'nama' => 'required',
            'jk' => 'required',
            'status' => 'required',
            'agama' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $user = User::create($request->all());
            $response = [
                'error' => false,
                'message' => 'Data Akun Admin Berhasil Ditambahkan',
                'data' => $user
            ];

            return response()->json($response, HttpFoundationResponse::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Data Akun Admin Gagal Ditambahkan',
            ], HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function show(string $id)
    {
        try {
            $user = User::where('id', $id)->firstOrFail();
            return response()->json([
                'error' => false,
                'message' => 'Data Akun Admin',
                'data' => $user
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Data Akun Admin Tidak Ditemukan',
            ], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update($request->all());

            return response()->json([
                'error' => false,
                'message' => 'Data Akun Admin Berhasil Diperbarui',
                'data' => $user,
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
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json([
                'error' => false,
                'message' => 'Data Akun User Berhasil Dihapus',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Data Akun Tidak Ditemukan',
            ], 404);
        }
    }
}
