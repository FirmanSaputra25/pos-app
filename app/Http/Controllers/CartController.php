<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Cart; // Pastikan ini diimpor untuk menggunakan Cart

class CartController extends Controller
{
    public function add(Request $request, $productId)
    {
        // Cek apakah produk ditemukan
        $product = Product::findOrFail($productId);

        // Cek apakah ukuran yang dipilih ada dan stoknya mencukupi
        $size = $product->sizes()->where('id', $request->size)->first();

        if (!$size || $size->stock <= 0) {
            return redirect()->back()->with('error', 'The selected size is out of stock.');
        }

        // Menambahkan item ke cart
        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
            'attributes' => [
                'size' => $size->size
            ]
        ]);

        // Redirect ke halaman transaksi setelah menambahkan produk ke keranjang
        return redirect()->route('transactions.index');
    }
}