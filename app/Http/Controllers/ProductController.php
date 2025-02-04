<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('productSizes')->paginate(10);
        $product_sizes = Product::all();
        return view('products.index', compact('products'));
    }


    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'sizes.*.size' => 'required|string',
            'sizes.*.stock' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,bmp,tiff|max:2048',
        ]);

        // Simpan data produk
        $product = new Product;
        $product->name = $request->name;
        $product->price = $request->price;

        // Hitung total stok
        $product->stock = array_sum(array_column($request->sizes, 'stock'));

        // Simpan gambar (jika ada)
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $product->image_path = basename($imagePath); // Simpan hanya nama file
        }

        $product->save();

        // Simpan ukuran dan stok ke tabel product_sizes
        if ($request->has('sizes')) {
            foreach ($request->sizes as $sizeData) {
                $product->sizes()->create([
                    'size' => $sizeData['size'],
                    'stock' => $sizeData['stock'],
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        // Daftar ukuran sepatu yang tersedia
        $sizes = ['36', '37', '38', '39', '40', '41', '42', '43'];

        return view('products.edit', compact('product', 'sizes'));
    }
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Hitung stok total baru berdasarkan ukuran yang ada
        $totalStock = 0;
        foreach ($request->sizes as $sizeData) {
            $totalStock += $sizeData['stock'];
        }

        // Update data produk
        $product->update([
            'name' => $request->name,
            'stock' => $totalStock, // Update stok total produk
            'price' => $request->price,
        ]);

        // Hapus semua ukuran sebelumnya dan simpan yang baru
        $product->productSizes()->delete();

        foreach ($request->sizes as $sizeData) {
            $product->productSizes()->create([
                'size' => $sizeData['size'],
                'stock' => $sizeData['stock'],
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }



    public function show($id)
    {
        $product = Product::findOrFail($id);
        $productSizes = $product->productSizes; // Pastikan relasi ini sudah benar
        return view('products.show', compact('product', 'productSizes'));
    }



    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}