<?php

namespace App\Imports;

use App\Models\TransactionProduct;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TransactionProductImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new TransactionProduct([
            'transaction_id'   => $row['transaction_id'],
            'product_id'       => $row['product_id'],
            'product_size_id'  => $row['product_size_id'],
            'quantity'         => $row['quantity'],
            'total_price'      => $row['total_price'],
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }
}