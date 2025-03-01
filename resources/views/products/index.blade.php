@extends('layouts.app')

@section('content')
<style>
    /* Animasi Fade In */
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.8s ease-out;
    }

    /* Kontainer */
    .container-custom {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
    }

    /* Input Pencarian */
    #search-input {
        border-radius: 8px;
        border: 2px solid #ccc;
        transition: border-color 0.3s ease-in-out;
    }

    #search-input:focus {
        border-color: #4f46e5;
        outline: none;
        box-shadow: 0px 0px 10px rgba(79, 70, 229, 0.3);
    }

    /* Kartu Produk */
    .product-card {
        border-radius: 12px;
        background: white;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.15);
    }

    /* Gambar Produk */
    .product-image {
        border-radius: 12px;
        overflow: hidden;
    }

    .product-image img {
        transition: transform 0.3s ease;
    }

    .product-image:hover img {
        transform: scale(1.1);
    }

    /* Tombol */
    .btn {
        border-radius: 8px;
        padding: 8px 14px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-green {
        background: #22c55e;
        color: white;
    }

    .btn-green:hover {
        background: #16a34a;
    }

    .btn-yellow {
        background: #facc15;
        color: black;
    }

    .btn-yellow:hover {
        background: #eab308;
    }

    .btn-red {
        background: #ef4444;
        color: white;
    }

    .btn-red:hover {
        background: #dc2626;
    }

    /* Responsif */
    @media (max-width: 768px) {
        .container-custom {
            padding: 1rem;
        }

        .text-2xl {
            font-size: 1.5rem;
        }
    }
</style>

<div class="container-custom mx-auto w-full overflow-hidden">
    <h1 class="text-center text-2xl font-bold mb-4">Product List</h1>

    <!-- Kolom input pencarian -->
    <div class="mb-3">
        <input type="text" id="search-input" class="w-full p-2 border border-gray-300 rounded"
            placeholder="Search products...">
    </div>

    @if (Auth::user()->role === 'admin')
    <!-- Tombol untuk menambah produk hanya untuk admin -->
    <a href="{{ route('products.create') }}" class="btn btn-green mb-3 inline-block">Add Product</a>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($products as $product)
        <div class="product-card p-4 animate-fade-in">
            <!-- Menampilkan gambar produk -->
            @if($product->image_path)
            <div class="product-image w-64 h-64 overflow-hidden rounded-lg">
                <img src="{{ asset('storage/images/' . $product->image_path) }}" class="w-full h-full object-cover"
                    alt="Product Image">
            </div>
            @else
            <p>No Image Available</p>
            @endif

            <div class="mt-4">
                <h5 class="text-lg font-semibold">
                    <a href="{{ route('products.show', $product->id) }}" class="text-blue-500 hover:underline">{{
                        $product->name }}</a>
                </h5>

                <p class="text-gray-700">Sizes:
                    @foreach ($product->productSizes as $productSize)
                    {{ $productSize->size }} (Stock: {{ $productSize->stock }}){{ $loop->last ? '' : ', ' }}
                    @endforeach
                </p>

                <p class="text-gray-700 font-bold">Price: Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                @if (Auth::user()->role === 'admin')
                <div class="flex space-x-2 mt-2">
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-yellow">Edit</a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this product?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-red">Delete</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-5">
        {{ $products->links() }}
    </div>
</div>

<!-- Script untuk Pencarian -->
<script>
    document.getElementById('search-input').addEventListener('input', function () {
        const query = this.value.toLowerCase();
        const products = document.querySelectorAll('.grid > div');
        products.forEach(product => {
            const name = product.querySelector('h5 a').textContent.toLowerCase();
            product.style.display = name.includes(query) ? '' : 'none';
        });
    });
</script>
@endsection