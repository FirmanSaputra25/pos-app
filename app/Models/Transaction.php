<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Kolom-kolom yang bisa di-mass assign
    protected $fillable = [
        'total_price',
        'user_money',
        'status',
    ];

    protected $casts = [
        'products' => 'array',
    ];
    public function products()
    {
        return $this->belongsToMany(Product::class, 'transaction_product', 'transaction_id', 'product_id')
            ->withPivot('product_size_id', 'quantity'); // Jika perlu menyimpan size dan jumlah
    }
}