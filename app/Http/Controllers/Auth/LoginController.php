<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
// Controller yang benar


class LoginController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login'); // Menampilkan halaman login
    }

    // Melakukan login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Proses autentikasi
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            // Jika login berhasil, redirect ke halaman home
            return redirect()->route('home'); // Redirect ke halaman home setelah login
        }

        // Jika login gagal, kembali dengan pesan error
        return Redirect::back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }
}