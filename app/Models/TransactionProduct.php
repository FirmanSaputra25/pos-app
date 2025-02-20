<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionProduct extends Model
{
    use HasFactory;

    protected $table = 'transaction_product'; // Sesuaikan dengan nama tabel di DB
    protected $fillable = ['transaction_id', 'product_id', 'product_size_id', 'quantity', 'total_price'];

    public $timestamps = true;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}