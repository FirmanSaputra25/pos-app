<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function showForm()
    {
        return view('payment-result'); // Menampilkan form pembayaran
    }
    public function create(Request $request)
    {
        // Ambil data produk yang dipilih dan total harga dari form
        $products = json_decode($request->input('products'), true);
        $totalPrice = 0;

        // Hitung total harga dari produk yang dipilih
        foreach ($products as $product) {
            $totalPrice += $product['totalPrice'];
        }

        // Tampilkan form pembayaran
        return view('payment', compact('totalPrice'));
    }

    public function store(Request $request)
    {
        // Validasi pembayaran
        $request->validate([
            'payment' => 'required|numeric',
            'total_price' => 'required|numeric',
        ]);

        // Ambil total harga dan jumlah uang yang diberikan
        $totalPrice = $request->input('total_price');
        $payment = $request->input('payment');

        // Cek apakah uang yang diberikan cukup
        if ($payment < $totalPrice) {
            // Jika uang kurang, beri pesan kesalahan
            return redirect()->back()->withErrors(['payment' => 'Uang yang diberikan tidak cukup untuk transaksi.']);
        }

        // Hitung kembalian
        $change = $payment - $totalPrice;

        // Proses pembayaran (misalnya simpan ke database atau tampilkan hasil)
        return view('payment-result', [
            'totalPrice' => $totalPrice,
            'payment' => $payment,
            'change' => $change
        ]);
    }
    public function createPaymentPage()
    {
        $selectedProducts = session('selected_products', []);
        $totalPrice = session('total_price', 0);

        return view('payment.create', compact('selectedProducts', 'totalPrice'));
    }
}