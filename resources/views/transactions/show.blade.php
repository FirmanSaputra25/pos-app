@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Transaction Details</h1>

    <!-- Menampilkan Produk yang Dibeli -->
    <h3 class="mt-3">Selected Products</h3>
    @foreach ($cart as $item)
    @foreach($item as $i)
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span>{{ $i['name'] }} (x{{ $i['quantity'] }}) - Size: {{ $i['size'] }}</span>
        <span>Rp {{ number_format($i['price'] * $i['quantity'], 0, ',', '.') }}</span>
    </div>
    @endforeach


    @endforeach

    <!-- Total Harga dan Uang yang Dimasukkan -->
    <div class="form-group mt-3">
        <label for="total_price">Total Price</label>
        <input type="text" class="form-control" id="total_price" name="total_price" value="Rp 
            {{ number_format($transaction->total_price, 0, ',', '.') }}" readonly>
    </div>

    <div class="form-group mt-3">
        <label for="user_money">User Money</label>
        <input type="text" class="form-control" id="user_money" name="user_money" value="Rp 
            {{ number_format($transaction->user_money, 0, ',', '.') }}" readonly>
    </div>

    <!-- Menampilkan Status Pembayaran -->
    <h4 class="mt-3">Status: {{ ucfirst($transaction->status) }}</h4>
    @if ($transaction->status == 'pending')
    <div class="alert alert-warning">Payment is still pending.</div>
    @elseif ($transaction->status == 'paid')
    <div class="alert alert-success">Payment successfully completed.</div>
    @else
    <div class="alert alert-danger">Transaction failed. Please try again.</div>
    @endif
</div>
@endsection