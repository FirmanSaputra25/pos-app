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
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'transaction_product', 'transaction_id', 'product_id')
            ->withPivot('product_size_id', 'quantity', 'total_price'); // Jika perlu menyimpan size dan jumlah
    }
    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class); // Pastikan model TransactionItem ada
    }
}