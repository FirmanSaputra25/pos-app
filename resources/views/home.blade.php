@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Jumbotron -->
    <div class="bg-blue-600 text-white rounded-lg p-8 mb-8 shadow-xl">
        <h1 class="text-4xl font-bold mb-4">Welcome to Our Application!</h1>
        <p class="text-xl mb-4">Explore our products and enjoy seamless transactions with our payment system.</p>
        <hr class="my-6 border-white">
        <p class="mb-4">Click the button below to view our products.</p>
        <a href="{{ url('/products') }}"
            class="bg-white text-blue-600 py-3 px-6 rounded-full font-semibold hover:bg-gray-100 transition duration-300">View
            Products</a>
    </div>

    <!-- Product Card Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($products as $product)
        <div
            class="bg-white rounded-lg shadow-lg overflow-hidden transition duration-300 transform hover:scale-105 hover:shadow-2xl">
            <!-- Gambar Produk -->
            @if($product->image_path)
            <div class="w-full h-64 overflow-hidden relative">
                <img src="{{ asset('storage/images/' . $product->image_path) }}" class="w-full h-full object-cover"
                    alt="Product Image">
                <div class="absolute inset-0 bg-gradient-to-t from-black opacity-50"></div>
            </div>
            @else
            <div class="w-full h-64 bg-gray-300 flex items-center justify-center text-gray-600">
                <p>No Image Available</p>
            </div>
            @endif

            <div class="p-6">
                <h5 class="text-2xl font-semibold mb-2">{{ $product->name }}</h5>
                <p class="text-lg text-gray-700 mb-4">{{ $product->description }}</p>
                <a href="{{ url('/products/' . $product->id) }}"
                    class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-300">View
                    Details</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection