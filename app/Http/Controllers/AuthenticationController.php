<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();

        // mengecek apakah user ada
        // dd($user);

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // pemberian token untuk user
        return $user->createToken('user login')->plainTextToken;
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function me(Request $request)
    {
        // Penggunaan Auth::user() disarankan untuk menampilkan data user yang sedang login, seperti dalam halaman profil atau halaman pengaturan
        return response()->json(Auth::user());

        // Penggunaan $request->user() disarankan untuk memproses data yang dikirimkan oleh pengguna, seperti dalam halaman formulir atau halaman checkout
        // return response()->json($request->user());
    }
}
