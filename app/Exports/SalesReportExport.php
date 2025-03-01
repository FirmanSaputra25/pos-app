<?php

namespace App\Exports;

use App\Models\TransactionProduct;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class SalesReportExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        // Default ke bulan ini jika tidak ada input tanggal
        $this->startDate = $startDate ?: Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = $endDate ?: Carbon::now()->endOfMonth()->toDateString();
    }

    public function collection()
    {
        return TransactionProduct::whereHas('transaction', function ($query) {
            $query->whereDate('created_at', '>=', $this->startDate)
                ->whereDate('created_at', '<=', $this->endDate);
        })->with(['product', 'transaction'])
            ->get()
            ->map(function ($transactionProduct) {
                return [
                    'Transaction ID' => $transactionProduct->transaction_id,
                    'Date' => $transactionProduct->transaction->created_at->format('d M Y'),
                    'Product Name' => $transactionProduct->product->name,
                    'Price' => $transactionProduct->product->price,
                    'Quantity' => $transactionProduct->quantity,
                    'Total Price' => $transactionProduct->product->price * $transactionProduct->quantity,
                ];
            });
    }

    public function headings(): array
    {
        return ['Transaction ID', 'Date', 'Product Name', 'Price', 'Quantity', 'Total Price'];
    }
}
