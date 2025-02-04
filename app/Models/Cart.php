<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    // Jika menggunakan session, tidak perlu mengatur relasi
    protected $fillable = ['user_id', 'total_price'];

    // Method untuk mendapatkan item cart (dalam bentuk array)
    public function getItems()
    {
        return session('cart', []); // Ambil data cart dari session
    }
}