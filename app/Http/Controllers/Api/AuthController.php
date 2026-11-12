<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //1. enpdoint regis owner hewan (/api/v1/register)
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'whatsapp_number' => 'required|string|max:15',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'whatsapp_number' => $request->whatsapp_number,
            'password' => Hash::make($request->password),//password dihash bycript, memastikan tidak disimpan dalam bentuk plaintext tapi string hash unik
            'role' => 'pet_owner',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi akun berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer', //tipe token untuk mengakses protected resource tanpa autentikasi tambahan atau tanda tangan kriptografi
            'data' => $user
        ], 201);
    }

    //2. endpoint login (/api/v1/login)
    public function login (Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Kredensial yang Anda masukkan salah (Unauthorized)'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'role' => $user->role
        ]);
    }

    //3. endpoint logout (/api/v1/logout)
    public function logout (Request $request) {
        //menghapus token aktif pengguna saat ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Berhasil logout'
        ]);
    }
}
