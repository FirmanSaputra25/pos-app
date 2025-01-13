@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Product List</h1>

    <!-- Kolom input pencarian -->
    <div class="mb-3">
        <input type="text" id="search-input" class="form-control" placeholder="Search products...">
    </div>

    @if (Auth::user()->role === 'admin')
    <!-- Tombol untuk menambah produk hanya untuk admin -->
    <a href="{{ route('products.create') }}" class="btn btn-success mb-3">Add Product</a>
    @endif

    <div class="row">
        <!-- Pastikan $products ada dan memiliki data -->`
        @foreach ($products as $product)
        <div class="col-md-4 product-item" data-name="{{ strtolower($product->name) }}">
            <div class="card mb-3">
                <!-- Menampilkan gambar produk -->
                @if ($product->image_path)
                <img src="{{ asset('storage/products/' . $product->image_path) }}" class="card-img-top"
                    alt="{{ $product->name }}">
                @else
                <img src="{{ asset('images/default-product.png') }}" class="card-img-top" alt="default product image">
                @endif

                <div class="card-body">
                    <h5 class="card-title">
                        <a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a>
                    </h5>
                    <p class="card-text">Stock: {{ $product->stock }}</p>

                    <!-- Menampilkan ukuran produk -->
                    <p class="card-text">Sizes:
                        @foreach ($product->productSizes as $productSize)
                        {{ $productSize->size }} (Stock: {{ $productSize->stock }}){{ $loop->last ? '' : ', ' }}
                        @endforeach
                    </p>

                    <p class="card-text">Price: Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                    @if (Auth::user()->role === 'admin')
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach

    </div>

    {{ $products->links() }}
</div>

<!-- Script untuk Pencarian -->
<script>
    document.getElementById('search-input').addEventListener('input', function () {
        const query = this.value.toLowerCase();
        const products = document.querySelectorAll('.product-item');
        products.forEach(product => {
            const name = product.getAttribute('data-name');
            if (name.includes(query)) {
                product.style.display = '';
            } else {
                product.style.display = 'none';
            }
        });
    });
</script>

@endsection