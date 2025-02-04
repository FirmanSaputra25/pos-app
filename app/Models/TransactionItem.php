<?php
// App\Models\TransactionItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    // Tentukan relasi inverse jika perlu (TransactionItem belongs to Transaction)
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}