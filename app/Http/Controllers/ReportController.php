<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionProduct;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Ambil start_date dan end_date dari request, jika kosong pakai bulan ini
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Query transaksi dengan filter tanggal
        $query = TransactionProduct::whereHas('transaction', function ($q) use ($startDate, $endDate) {
            $q->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate);
        })->with(['product', 'transaction.user'])->get();

        // Hitung total pendapatan
        $totalSales = $query->sum(fn($t) => $t->product->price * $t->quantity);

        return view('reports.sales_report', [
            'transactionProducts' => $query,
            'totalSales' => $totalSales,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    public function salesReport(Request $request)
    {
        return $this->index($request);
    }
}
