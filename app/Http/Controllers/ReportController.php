<?php

// app/Http/Controllers/ReportController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class ReportController extends Controller
{
    // Konstruktor untuk middleware

    public function salesReport()
    {
        // Ambil semua transaksi dengan relasi produk
        $transactions = Transaction::with('products')->get();

        // Hitung total pendapatan dari total_price setiap transaksi
        $totalSales = $transactions->sum(function ($transaction) {
            return $transaction->total_price;
        });

        // Kembalikan tampilan laporan transaksi
        return view('reports.sales_report', compact('transactions', 'totalSales'));
    }
}