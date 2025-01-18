<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use PhpParser\JsonDecoder;

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
        $request->validate([
            'cart' => 'required|string',
            'total_price' => 'required|numeric',
            'user_money' => 'required|numeric',
        ]);
        $cart = json_decode($request->input('cart'), true);
        $totalPrice = $request->input('total_price');
        $userMoney = $request->input('user_money');
        $transaction = Transaction::create([
            'total_price' => $totalPrice,
            'user_money' => $userMoney,
            'status' => 'paid'
        ]);
        foreach ($cart as $productId => $sizes) {
            $product = Product::find($productId);
            if (!$product) {
                return back()->withErrors(['cart' => 'Invalid product selected.'])->withInput();
            }
            foreach ($sizes as $sizeId => $details) {
                $productSize = ProductSize::find($sizeId);
                if (!$productSize) {
                    return back()->withErrors(['cart' => 'Invalid size selected.'])->withInput();
                }
                $quantity = (int) $details['quantity'];
                if ($quantity > $productSize->stock) {
                    return back()->withErrors(['cart' => 'Insufficient stock for product ' . $product->name . ' with size ' . $details['size'] . '.'])->withInput();
                }
                $transaction->products()->attach($product->id, [
                    'product_size_id' => $productSize->id,
                    'quantity' => $quantity,

                ]);
                $productSize->decrement('stock', $quantity);
            }
        }
        $transaction = Transaction::find($transaction->id);
        return view('transactions.show', [
            'transaction' => $transaction,
            'cart' => $cart,
            'totalPrice' => $totalPrice,
        ])->with('success', 'Transaction successfully created.');
    }

    public function show($transactionId)
    {
        // Retrieve the transaction based on the ID
        $transaction = Transaction::findOrFail($transactionId);

        // Pass the transaction to the view
        return view('transactions.show', compact('transaction'));
    }
}