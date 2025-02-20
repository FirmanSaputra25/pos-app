<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionProduct;  // Pastikan ini ada
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Ambil bulan dan tahun dari request, jika tidak ada gunakan bulan & tahun saat ini
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));

        // Ambil transaksi berdasarkan bulan dan tahun dari tabel transaction_product
        $transactionProducts = TransactionProduct::whereHas('transaction', function ($query) use ($year, $month) {
            $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
        })->with(['product', 'transaction.user'])  // Mengambil relasi product dan user
            ->get();

        // Hitung total pendapatan dari transaksi yang difilter
        $totalSales = $transactionProducts->sum('total_price');

        return view('reports.sales_report', compact('transactionProducts', 'totalSales'));
    }

    public function salesReport(Request $request)
    {
        return $this->index($request); // Gunakan index() untuk mengambil laporan
    }
}