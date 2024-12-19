<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Kolom-kolom yang bisa di-mass assign
    protected $fillable = [
        'product_id',
        'quantity',
        'total_price',
    ];
    // Konversi products menjadi array atau objek saat diakses
    protected $casts = [
        'products' => 'array',
    ];
}