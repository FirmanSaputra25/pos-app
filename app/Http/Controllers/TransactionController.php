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

        // Validasi: jika cart kosong, arahkan ke halaman yang sesuai dengan pesan error
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Please add some items.');
        }

        // Menghitung total harga
        $totalPrice = array_reduce($cart, function ($total, $sizes) {
            return $total + array_reduce($sizes, function ($subTotal, $item) {
                return $subTotal + ($item['price'] * $item['quantity']);
            }, 0);
        }, 0);

        // Validasi: jika total harga 0, tampilkan error
        if ($totalPrice == 0) {
            return redirect()->route('cart.index')->with('error', 'Total price cannot be zero.');
        }

        // Mengambil semua produk (jika diperlukan untuk halaman transaksi)
        $products = Product::all();

        // Menampilkan halaman dengan data cart dan total harga
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
    public function endTransaction(Request $request)
    {
        // Menghapus cart dari session atau database
        session()->forget('cart'); // Jika cart disimpan dalam session

        // Mengupdate status transaksi menjadi selesai atau kosong
        $transaction = Transaction::findOrFail($request->transaction_id); // Sesuaikan dengan ID transaksi yang digunakan
        $transaction->status = 'paid'; // Atau status lain yang sesuai
        $transaction->save();

        // Redirect ke halaman utama
        return redirect('home')->with('success', 'Transaction Completed');
    }

    public function show($transactionId)
    {
        // Retrieve the transaction based on the ID
        $transaction = Transaction::findOrFail($transactionId);

        // Pass the transaction to the view
        return view('transactions.show', compact('transaction'));
    }
}