@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4 text-3xl font-bold">Payment Receipt</h1>

    <!-- Menampilkan Produk yang Dibeli -->
    <div class="card shadow-lg mb-4">
        <div class="card-header bg-blue-500 text-white p-4 rounded-t-lg">
            <h3 class="mb-0 text-xl font-semibold">Selected Products</h3>
        </div>
        <div class="card-body p-4">
            <table class="w-full border-collapse border border-gray-300 mt-3">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="p-2 border">Product Name</th>
                        <th class="p-2 border">Size</th>
                        <th class="p-2 border">Price</th>
                        <th class="p-2 border">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                    <tr class="text-center">
                        <td class="p-2 border">{{ $item->product->name }}</td>
                        <td class="p-2 border">{{ $item->productSize->size }}</td>
                        <td class="p-2 border">Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                        <td class="p-2 border">{{ $item->quantity }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Table of Products -->

    <!-- Total Harga dan Uang yang Dimasukkan -->
    <div class="card shadow-lg mb-4">
        <div class="card-header bg-gray-700 text-white p-4 rounded-t-lg">
            <h4 class="mb-0 text-lg font-semibold">Transaction Summary</h4>
        </div>
        <div class="card-body p-4">
            <div class="mb-4">
                <label for="total_price" class="block text-sm font-medium">Total Price</label>
                <input type="text" class="form-control block w-full px-3 py-2 border rounded-md bg-gray-100"
                    id="total_price" name="total_price" value="Rp 
                    {{ number_format($transaction->total_price, 0, ',', '.') }}" readonly>
            </div>
            <div class="mb-4">
                <label for="user_money" class="block text-sm font-medium">User Money</label>
                <input type="text" class="form-control block w-full px-3 py-2 border rounded-md bg-gray-100"
                    id="user_money" name="user_money" value="Rp 
                    {{ number_format($transaction->user_money, 0, ',', '.') }}" readonly>
            </div>
        </div>
    </div>

    <!-- Status Pembayaran -->
    <div class="card shadow-lg mt-4">
        <div class="card-body text-center">
            @if ($transaction->status == 'pending')
            <div class="flex items-center justify-center bg-yellow-100 text-yellow-800 p-4 rounded-lg shadow-md">
                <i class="fas fa-clock text-xl mr-2"></i>
                <span class="text-sm">Payment is still pending.</span>
            </div>
            @elseif ($transaction->status == 'paid')
            <div class="flex items-center justify-center bg-green-100 text-green-800 p-4 rounded-lg shadow-md">
                <i class="fas fa-check-circle text-xl mr-2"></i>
                <span class="text-sm">Payment successfully completed.</span>
            </div>
            @else
            <div class="flex items-center justify-center bg-red-100 text-red-800 p-4 rounded-lg shadow-md">
                <i class="fas fa-times-circle text-xl mr-2"></i>
                <span class="text-sm">Transaction failed. Please try again.</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Tombol untuk Kembali ke Daftar Transaksi atau Halaman Lain -->
    <div class="text-center mt-5">
        <a href="{{ route('transactions.index') }}" class="btn btn-primary px-6 py-2 rounded-md">Back to
            Transactions</a>
    </div>
</div>
@endsection