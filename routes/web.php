<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Auth;

// Route utama yang akan menampilkan halaman login
Route::get('/', function () {
    return view('auth.login'); // Pastikan file view login berada di resources/views/auth/login.blade.php
});

Route::get('/home', function () {
    return view('home'); // Mengarah ke resources/views/home/home.blade.php
})->name('home');

Route::resource('products', ProductController::class);
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::post('/cart/{productId}', [CartController::class, 'add'])->name('cart.add');

Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');

Route::post('payment', [PaymentController::class, 'store'])->name('payment.store');
Route::get('/payment/create', [PaymentController::class, 'create'])->name('payment.create');
Route::post('/payment/create', [PaymentController::class, 'create'])->name('payment.create');
Route::get('/payment/create', [PaymentController::class, 'showForm'])->name('payment.create');
Route::post('/payment', [PaymentController::class, 'store'])->name('payment.store');
Route::post('/payment-result', [PaymentController::class, 'result'])->name('payment.result');

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

// Menggunakan route logout yang benar
Route::post('/logout', function () {
    Auth::logout(); // Melakukan logout
    return redirect('/'); // Redirect ke halaman utama atau halaman yang diinginkan
})->name('logout');

// Menggunakan route register
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);