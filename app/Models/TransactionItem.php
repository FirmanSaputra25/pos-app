<?php
// App\Models\TransactionItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $table = 'transaction_items'; // Sesuaikan dengan nama tabel

    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'total_price',
    ];
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function productSize()
    {
        return $this->belongsTo(ProductSize::class);
    }


    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id');
    }
}