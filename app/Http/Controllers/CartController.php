<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Cart; // Pastikan ini diimpor untuk menggunakan Cart

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function store(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $cart = session()->get('cart', []);

        // Pastikan size yang dipilih valid
        $sizeId = $request->size;
        $productSize = ProductSize::find($sizeId);

        if (!$productSize) {
            session()->flash('error', 'Size not found!');
            return redirect()->back();
        }

        // Periksa apakah kuantitas yang diminta melebihi stok
        if ($request->quantity > $productSize->stock) {
            session()->flash('error', 'Not enough stock for the selected size!');
            return redirect()->back();
        }

        // Cek apakah cart[$productId] sudah berupa array
        if (!isset($cart[$productId])) {
            $cart[$productId] = [];
        }

        // Tambahkan produk ke keranjang dengan size yang dipilih
        if (isset($cart[$productId][$sizeId])) {
            $cart[$productId][$sizeId]['quantity'] += $request->quantity; // Menggunakan kuantitas yang dipilih
        } else {
            $cart[$productId][$sizeId] = [
                "name" => $product->name,
                "price" => $product->price,
                "quantity" => $request->quantity, // Menggunakan kuantitas yang dipilih
                "size" => $productSize->size,
                "size_id" => $sizeId,
                "stock" => $productSize->stock
            ];
        }

        session()->put('cart', $cart);

        // Simpan pesan sukses
        session()->flash('success', 'Product added to cart!');
        return redirect()->back();
    }


    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $sizeId = $request->input('size_id');
        $quantity = $request->input('quantity');

        // Ambil produk dan ukuran berdasarkan ID
        $product = Product::find($productId);
        $size = ProductSize::find($sizeId);

        // Ambil cart dari session atau buat array kosong jika tidak ada
        $cart = session()->get('cart', []);

        // Periksa apakah produk sudah ada di cart
        if (isset($cart[$productId][$sizeId])) {
            $cart[$productId][$sizeId]['quantity'] += $quantity; // Jika ada, tambah kuantitas
        } else {
            // Jika tidak ada, tambahkan produk baru ke cart
            $cart[$productId][$sizeId] = [
                'name' => $product->name,
                'size' => $size->size,
                'price' => $size->price,
                'quantity' => $quantity
            ];
        }

        // Debug: Cek cart setelah ditambahkan produk
        dd($cart);

        // Simpan kembali cart ke session
        session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }


    public function checkout()
    {
        // Mendapatkan data dari session atau request (seperti produk yang ada di keranjang)
        $cart = session()->get('cart');
        $totalPrice = 0;
        $userMoney = 100000; // Contoh, ini bisa diambil dari input pengguna

        // Hitung total harga dari produk yang ada di keranjang
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        // Membuat transaksi baru
        $transaction = new Transaction();
        $transaction->total_price = $totalPrice;
        $transaction->user_money = $userMoney;
        $transaction->status = 'completed'; // Status transaksi selesai
        $transaction->save();

        // Menyimpan produk yang dibeli
        foreach ($cart as $productId => $item) {
            $transaction->products()->attach($productId, [
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Hapus keranjang setelah transaksi selesai
        session()->forget('cart');

        // Redirect ke halaman sukses atau laporan
        return redirect()->route('transactions.success');
    }



    public function update(Request $request, $productId)
    {
        // Mendapatkan data keranjang belanja dari session
        $cart = session()->get('cart', []);

        // Mendapatkan sizeId dari request
        $sizeId = $request->size;

        // Memastikan bahwa item yang diminta ada dalam keranjang
        if (isset($cart[$productId][$sizeId])) {
            // Mengambil item yang ada dalam keranjang
            $cartItem = $cart[$productId][$sizeId];

            // Mencari data ProductSize untuk produk dan ukuran tertentu
            $productSize = ProductSize::find($cartItem['size_id']);

            // Memastikan produk ukuran yang ditemukan ada dan stok tersedia
            if (!$productSize) {
                return redirect()->back()->with('error', 'Product size not found!');
            }

            // Memeriksa apakah kuantitas yang diminta melebihi stok yang tersedia
            if ($request->quantity > $productSize->stock) {
                // Mengembalikan pesan error jika stok tidak cukup
                return redirect()->back()->with('error', 'Not enough stock for the selected size!');
            }

            // Jika stok cukup, perbarui kuantitas di keranjang
            $cart[$productId][$sizeId]['quantity'] = $request->quantity;

            // Menyimpan kembali keranjang yang sudah diperbarui ke session
            session()->put('cart', $cart);

            // Mengembalikan ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'Cart updated successfully!');
        }

        // Jika item tidak ditemukan di keranjang, kembalikan pesan error
        return redirect()->back()->with('error', 'Product not found in cart!');
    }

    public function removeItem($productId, $sizeId)
    {
        // Ambil cart dari session
        $cart = session()->get('cart', []);

        // Cek apakah produk ada dalam cart
        if (isset($cart[$productId])) {
            // Cek apakah size ada dalam produk yang dipilih
            if (isset($cart[$productId][$sizeId])) {
                unset($cart[$productId][$sizeId]); // Hapus item berdasarkan size
                if (empty($cart[$productId])) {
                    unset($cart[$productId]); // Jika semua size produk sudah dihapus, hapus produk tersebut
                }
                session()->put('cart', $cart);
                return redirect()->back()->with('success', 'Item removed successfully.');
            }
        }

        return redirect()->back()->with('error', 'Item not found in cart.');
    }

    public function remove($id, $size)
    {
        $cart = session()->get('cart', []);

        // Memeriksa apakah produk dan ukuran ada di dalam keranjang
        if (isset($cart[$id][$size])) {
            unset($cart[$id][$size]);  // Menghapus produk berdasarkan ukuran

            // Jika setelah dihapus produk ukuran tertentu kosong, hapus produk tersebut dari keranjang
            if (empty($cart[$id])) {
                unset($cart[$id]);
            }

            session()->put('cart', $cart);  // Menyimpan perubahan kembali ke session

            return redirect()->back()->with('success', 'Product removed successfully!');
        }

        return redirect()->back()->with('error', 'Product size not found in cart!');
    }
    public function showCart()
    {
        // Mengambil data keranjang dari session
        $cart = session()->get('cart', []);

        // Periksa dan perbarui stok untuk setiap item di keranjang
        foreach ($cart as &$product) {
            foreach ($product as &$item) {
                // Ambil stok produk berdasarkan product_id dan size_id
                $productSize = ProductSize::where('product_id', $item['product_id'])
                    ->where('size_id', $item['size_id'])
                    ->first();

                // Periksa apakah productSize ditemukan dan set stoknya
                if ($productSize) {
                    $item['stock'] = $productSize->stock; // Simpan stok produk yang benar
                } else {
                    $item['stock'] = 0; // Jika stok tidak ditemukan, set stok ke 0
                }
            }
        }

        // Perbarui session dengan data keranjang yang sudah diperbarui
        session()->put('cart', $cart);

        // Kirim data keranjang ke view
        return view('cart.index', compact('cart'));
    }
}