<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use HasFactory;


class Transaction extends Model
{
    public $timestamps = true;
    // Kolom-kolom yang bisa di-mass assign
    protected $fillable = [
        'total_price',
        'user_money',
        'status',
        'user_id'
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
        return $this->hasManyThrough(
            Product::class, // Model tujuan
            TransactionItem::class, // Model perantara
            'transaction_id', // Foreign key di transaction_items yang menghubungkan ke transactions
            'id', // Primary key di products
            'id', // Primary key di transactions
            'product_id' // Foreign key di transaction_items yang menghubungkan ke products
        );
    }
    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }
    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id', 'id');
    }
}