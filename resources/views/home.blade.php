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

    /* Hero Section */
    .hero {
        background: linear-gradient(135deg, #2563eb, #4f46e5);
        color: white;
        padding: 4rem 2rem;
        text-align: center;
        border-radius: 10px;
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
    }

    .hero h1 {
        font-size: 2.5rem;
        font-weight: bold;
    }

    .hero p {
        font-size: 1.2rem;
        margin-bottom: 1.5rem;
    }

    .hero .btn {
        background: white;
        color: #2563eb;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: bold;
        transition: all 0.3s ease-in-out;
    }

    .hero .btn:hover {
        background: #f0f0f0;
    }

    /* Grid Produk */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-top: 2rem;
    }

    .product-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s ease-in-out;
    }

    .product-card:hover {
        transform: scale(1.05);
    }

    .product-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .product-card .info {
        padding: 1rem;
        text-align: center;
    }

    .product-card h5 {
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .product-card p {
        font-size: 1rem;
        color: #666;
        margin-bottom: 1rem;
    }

    .product-card .price {
        font-size: 1.2rem;
        font-weight: bold;
        color: #2563eb;
    }

    .product-card .btn {
        display: block;
        margin-top: 1rem;
        background: #2563eb;
        color: white;
        padding: 0.5rem;
        border-radius: 5px;
        transition: 0.3s;
    }

    .product-card .btn:hover {
        background: #1d4ed8;
    }
</style>

<div class="container mx-auto px-4 py-6 animate-fade-in">
    <!-- Hero Section -->
    <div class="hero">
        <h1>Welcome to Our POS Application!</h1>
        <p>Explore our products and enjoy seamless transactions with multiple payment options.</p>
        <a href="{{ url('/products') }}" class="btn">View Products</a>
    </div>

    <!-- Product Grid -->
    <div class="product-grid">
        @foreach ($products as $product)
        <div class="product-card">
            @if($product->image_path)
            <img src="{{ asset('storage/images/' . $product->image_path) }}" alt="{{ $product->name }}">
            @else
            <div class="w-full h-48 bg-gray-300 flex items-center justify-center text-gray-600">
                <p>No Image Available</p>
            </div>
            @endif
            <div class="info">
                <h5>{{ $product->name }}</h5>
                <p>{{ $product->description }}</p>
                <div class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                <a href="{{ url('/products/' . $product->id) }}" class="btn">View Details</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection