@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Shopping Cart</h1>

    @if(count($cart) > 0)
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cart as $productId => $details)
            <tr>
                <td>{{ $details['name'] }}</td>
                <td>Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                <td>{{ $details['quantity'] }}</td>
                <td>Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('transactions.create') }}" class="btn btn-primary">Proceed to Checkout</a>
    @else
    <p>Your cart is empty.</p>
    @endif
</div>
@endsection