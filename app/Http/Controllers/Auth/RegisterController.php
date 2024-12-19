<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // Menampilkan form registrasi
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Menangani proses registrasi
    public function register(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:user,admin', // Validasi role
        ]);

        // Cek apakah sudah ada user dengan role admin
        if ($request->role === 'admin' && User::where('role', 'admin')->exists()) {
            // Jika sudah ada, tampilkan error
            return redirect()->back()->withErrors(['role' => 'Sudah ada admin yang terdaftar. Hanya bisa ada satu admin.']);
        }

        // Membuat user baru dengan role
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, // Menyimpan role yang dipilih
        ]);

        // Login otomatis setelah registrasi
        Auth::login($user);

        // Redirect ke halaman login setelah registrasi
        return redirect()->route('login');
    }
}