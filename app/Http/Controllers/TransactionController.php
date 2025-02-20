<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // Menampilkan halaman transaksi
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

    // Menyimpan transaksi baru
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

        // Membuat transaksi baru
        $transaction = Transaction::create([
            'total_price' => $totalPrice,
            'user_money' => $userMoney,
            'status' => 'paid'
        ]);

        // Menambahkan produk dan ukuran yang dibeli ke dalam transaksi
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

                // Ambil total_price dari transaction jika ada
                $transactionTotalPrice = DB::table('transactions')->where('id', $transaction->id)->value('total_price');

                DB::table('transaction_product')->insert([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'product_size_id' => $productSize->id,
                    'quantity' => $quantity,
                    'total_price' => $transactionTotalPrice ?? 0, // Gunakan total_price dari transaksi atau 0 jika null
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);


                // Mengurangi stok produk
                $productSize->decrement('stock', $quantity);
            }
        }

        return view('transactions.show', [
            'transaction' => $transaction,
            'cart' => $cart,
            'totalPrice' => $totalPrice,
        ])->with('success', 'Transaction successfully created.');
    }

    // Menyelesaikan transaksi dan menghapus cart dari session
    public function endTransaction(Request $request)
    {
        // Menghapus cart dari session
        session()->forget('cart');

        // Mengupdate status transaksi menjadi 'paid'
        $transaction = Transaction::findOrFail($request->transaction_id);
        $transaction->status = 'paid';
        $transaction->save();

        // Redirect ke halaman struk pembayaran
        return redirect()->route('transactions.receipt', ['transactionId' => $transaction->id])
            ->with('success', 'Transaction Completed');
    }

    // Menampilkan detail transaksi
    public function show($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        return view('transactions.show', compact('transaction'));
    }

    // Menampilkan struk pembayaran transaksi
    public function showReceipt($transactionId)
    {
        // Ambil transaksi berdasarkan ID dan relasi dengan item transaksi serta produk
        $transaction = Transaction::with('transactionItems.product', 'transactionItems.productSize')->findOrFail($transactionId);

        // Ambil produk yang ada dalam transaksi
        $items = $transaction->transactionItems;

        // Kirimkan data transaksi dan item ke view
        return view('transactions.receipt', compact('transaction', 'items'));
    }
}