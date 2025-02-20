<?php

namespace App\Exports;

use App\Models\TransactionProduct;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesReportExport implements FromCollection, WithHeadings
{
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return TransactionProduct::whereHas('transaction', function ($query) {
            if ($this->month) {
                $query->whereMonth('created_at', $this->month);
            }
            if ($this->year) {
                $query->whereYear('created_at', $this->year);
            }
        })->get()->map(function ($transactionProduct) {
            return [
                $transactionProduct->transaction_id,
                $transactionProduct->transaction->created_at->format('d M Y'),
                'Rp ' . number_format($transactionProduct->total_price, 0, ',', '.'),
                $transactionProduct->product->name,
                $transactionProduct->quantity,
            ];
        });
    }

    public function headings(): array
    {
        return ['Transaction ID', 'Date', 'Total Price', 'Product', 'Quantity'];
    }
}