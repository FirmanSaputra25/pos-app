@extends('layouts.app')

@section('content')
<div class="ml-50 p-8 max-w-screen-lg mx-auto w-full overflow-hidden">
    <h1 class="text-center text-2xl font-bold mb-4">Product List</h1>

    <!-- Kolom input pencarian -->
    <div class="mb-3">
        <input type="text" id="search-input" class="w-full p-2 border border-gray-300 rounded"
            placeholder="Search products...">
    </div>

    @if (Auth::user()->role === 'admin')
    <!-- Tombol untuk menambah produk hanya untuk admin -->
    <a href="{{ route('products.create') }}" class="bg-green-500 text-white px-4 py-2 rounded mb-3 inline-block">Add
        Product</a>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($products as $product)
        <div class="bg-white p-4 rounded shadow max-w-full">
            <!-- Menampilkan gambar produk -->
            @if($product->image_path)
            <div class="w-64 h-64 overflow-hidden rounded-lg">
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
                    <a href="{{ route('products.edit', $product->id) }}"
                        class="bg-yellow-500 text-white px-3 py-1 rounded">Edit</a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this product?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Delete</button>
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