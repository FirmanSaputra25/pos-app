<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        // Mengambil data produk dari cart yang ada di session
        $cart = session()->get('cart', []);

        // Menghitung total harga
        $totalPrice = array_reduce($cart, function ($total, $sizes) {
            return $total + array_reduce($sizes, function ($subTotal, $item) {
                return $subTotal + ($item['price'] * $item['quantity']);
            }, 0);
        }, 0);
        $products = Product::all();

        return view('transactions.index', compact('cart', 'products', 'totalPrice'));
    }


    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'cart' => 'required|array',
            'total_price' => 'required|numeric',
            'user_money' => 'required|numeric',
        ]);

        // Ambil data dari request
        $cart = $request->input('cart');
        $totalPrice = $request->input('total_price');
        $userMoney = $request->input('user_money');

        // Simpan transaksi ke database
        $transaction = Transaction::create([
            'total_price' => $totalPrice,
            'user_money' => $userMoney,
        ]);

        // Menambahkan produk dan ukuran produk ke transaksi
        foreach ($cart as $cartItem) {
            // Validasi produk dan ukuran produk
            $product = Product::find($cartItem['product_id']);
            $productSize = ProductSize::find($cartItem['product_size_id']);

            if (!$product || !$productSize) {
                // Jika produk atau ukuran produk tidak ditemukan, beri respons error
                return back()->withErrors(['cart' => 'Invalid product or size selected.'])->withInput();
            }

            $quantity = $cartItem['quantity'];

            // Menambahkan data produk ke transaksi menggunakan pivot table
            $transaction->products()->attach($product->id, [
                'product_size_id' => $productSize->id,
                'quantity' => $quantity,
            ]);
        }

        // Pass the transaction data to the view
        return view('transactions.show', [
            'transaction' => $transaction,
            'cart' => $cart,
            'totalPrice' => $totalPrice,
        ])->with('success', 'Transaction successfully created.');
    }


    public function show($transactionId)
    {
        // Retrieve the transaction based on the ID
        $transaction = Transaction::findOrFail($transactionId);

        // Pass the transaction to the view
        return view('transactions.show', compact('transaction'));
    }
}