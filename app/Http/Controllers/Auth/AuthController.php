<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'nomor_telepon' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            $client = Client::where('nomor_telepon', $request->nomor_telepon)->first();

            if (! $client || ! Hash::check($request->password, $client->password)) {
                return response()->json([
                    'error' => true,
                    'message' => 'Nomor telepon atau password salah.',
                ], 401);
            }

            // Hapus semua token lama
            $client->tokens()->delete();

            $token = $client->createToken('auth_token')->plainTextToken;

            $dataClient = $client->only(['kk_id', 'nama_kepala_keluarga', 'nomor_telepon']);

            return response()->json([
                'error' => false,
                'message' => 'Login Berhasil',
                'token' => $token,
                'client_id' => $client->id,
                'kk_id' => $client->kk_id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat login.',
                'details' => $e->getMessage(), // Hapus di production
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->tokens()->delete();
            return response()->json([
                'error' => false,
                'message' => 'Logout Berhasil',
            ]);
        }

        return response()->json([
            'error' => true,
            'message' => 'Tidak ada user yang login.',
        ], 401);
    }
}
