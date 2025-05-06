<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $client = Client::where('nomor_telepon', $request->nomor_telepon)->first();

            if (! $client || ! Hash::check($request->password, $client->password)) {
                return response()->json(['message' => 'Nomor telepon atau password salah.'], 401);
            }

            $token = $client->createToken('auth_token')->plainTextToken;

            return response()->json([
                'error' => false,
                'message' => 'Login Berhasil',
                'data' => [
                    'token' => $token,
                    'client' => $client,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat login.',
                'details' => $e->getMessage() // untuk debugging, hapus di production
            ], 500);
        }
    }

    public function logout()
    {
        auth('client')->user()->tokens->delete();
        return response()->json([
            'error' => false,
            'message' => 'Logout Berhasil',
        ]);
    }
}
