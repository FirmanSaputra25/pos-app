@extends('layouts.app')

@section('content')
<div class="container">
    <div class="jumbotron mt-4 p-5 text-white bg-primary rounded">
        <h1 class="display-4">Welcome to Our Application!</h1>
        <p class="lead">Explore our products and enjoy seamless transactions with our payment system.</p>
        <hr class="my-4">
        <p>Click the button below to view our products.</p>
        <a class="btn btn-light btn-lg" href="{{ url('/products') }}" role="button">View Products</a>
    </div>

    <div class="row mt-5">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="https://via.placeholder.com/150" class="card-img-top" alt="Products">
                <div class="card-body">
                    <h5 class="card-title">Our Products</h5>
                    <p class="card-text">Browse through a wide range of products tailored to your needs.</p>
                    <a href="{{ url('/products') }}" class="btn btn-primary">Browse Products</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="https://via.placeholder.com/150" class="card-img-top" alt="About Us">
                <div class="card-body">
                    <h5 class="card-title">About Us</h5>
                    <p class="card-text">Learn more about our mission, vision, and values.</p>
                    <a href="#" class="btn btn-primary">Learn More</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="https://via.placeholder.com/150" class="card-img-top" alt="Contact Us">
                <div class="card-body">
                    <h5 class="card-title">Contact Us</h5>
                    <p class="card-text">Need help? Get in touch with our support team.</p>
                    <a href="#" class="btn btn-primary">Contact Now</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection