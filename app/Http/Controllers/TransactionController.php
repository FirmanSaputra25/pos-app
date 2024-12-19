<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        // Mengambil data produk dari cart yang ada di session
        $cart = session()->get('cart', []);
        $products = Product::all();
        return view('transactions.index', compact('cart', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $selectedProducts = [];
        $totalPrice = 0;

        foreach ($request->products as $productData) {
            $product = Product::find($productData['id']);

            // Tambahkan produk yang dipilih ke array selectedProducts
            $selectedProducts[] = [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => $productData['quantity'],
                'totalPrice' => $productData['quantity'] * $product->price,
            ];

            // Hitung total harga
            $totalPrice += $productData['quantity'] * $product->price;
        }

        // Simpan ke session atau database
        session(['selected_products' => $selectedProducts, 'total_price' => $totalPrice]);

        return redirect()->route('transactions.index')->with('success', 'Transaction processed successfully.');
    }
}