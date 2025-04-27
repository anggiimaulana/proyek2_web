<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class ClientControllerApi extends Controller
{
    public function index()
    {
        $client = Client::orderBy('created_at', 'desc')->paginate(5);
        $response = [
            'error' => false,
            'message' => 'Data Akun Masyarakat',
            'data' => $client,
        ];
        return response()->json($response, HttpFoundationResponse::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'nik' => 'required|unique:clients,nik',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'jk' => 'required',
            'agama' => 'required',
            'pendidikan' => 'required',
            'pekerjaan' => 'required',
            'alamat' => 'required',
            'status' => 'required',
            'nomor_telepon' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $client = Client::create($request->all());

            $response = [
                'error' => false,
                'message' => 'Data Akun Masyarakat Berhasil Ditambahkan',
                'data' => $client
            ];

            return response()->json($response, HttpFoundationResponse::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Data Akun Masyarakat Gagal Ditambahkan',
            ]);
        }
    }

    public function show(string $id)
    {
        try {
            $client = Client::where('id', $id)->firstOrFail();
            return response()->json([
                'error' => false,
                'message' => 'Data Akun Masyarakat',
                'data' => $client,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Data Akun Tidak Ditemukan',
            ], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->update($request->all());

            return response()->json([
                'error' => false,
                'message' => 'Data Akun Masyarakat Berhasil Diperbarui',
                'data' => $client,
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
            $client = Client::findOrFail($id);
            $client->delete();

            return response()->json([
                'error' => false,
                'message' => 'Data Akun Berhasil Dihapus',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Data Akun Tidak Ditemukan',
            ], 404);
        }
    }
}
