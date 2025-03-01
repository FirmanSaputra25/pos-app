@extends('layouts.app')

@section('content')
<div class="container mt-4 text-gray-800">
    <h1 class="text-center text-2xl font-bold mb-3">Transaction Details</h1>

    <!-- Produk yang Dibeli -->
    <div class="shadow-lg rounded-lg border p-3 bg-gray-50">
        <h3 class="bg-blue-500 text-white p-2 rounded-t-md font-semibold">Selected Products</h3>
        <div class="p-2">
            @foreach ($cart as $item)
            @foreach($item as $i)
            <div class="flex justify-between text-sm border-b py-1">
                <span>{{ $i['name'] }} (x{{ $i['quantity'] }}) - {{ $i['size'] }}</span>
                <span class="font-semibold">Rp {{ number_format($i['price'] * $i['quantity'], 0, ',', '.') }}</span>
            </div>
            @endforeach
            @endforeach
        </div>
    </div>

    <!-- Ringkasan Transaksi -->
    <div class="shadow-lg rounded-lg border p-3 bg-gray-50 mt-3">
        <h4 class="bg-gray-700 text-white p-2 rounded-t-md font-semibold">Transaction Summary</h4>
        <div class="p-2 text-sm">
            <div class="flex justify-between">
                <span>Total Price</span>
                <span class="font-semibold">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>User Money</span>
                <span class="font-semibold">Rp {{ number_format($transaction->user_money, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Change</span>
                <span class="font-semibold">Rp {{ number_format($transaction->user_money - $transaction->total_price, 0,
                    ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Status Pembayaran -->
    <div class="shadow-lg rounded-lg border p-3 bg-gray-50 mt-3 text-center">
        @if ($transaction->status == 'pending')
        <div class="text-yellow-800 bg-yellow-100 p-2 rounded-md">
            <i class="fas fa-clock"></i> Payment Pending
        </div>
        @elseif ($transaction->status == 'paid')
        <div class="text-green-800 bg-green-100 p-2 rounded-md">
            <i class="fas fa-check-circle"></i> Payment Completed
        </div>
        @else
        <div class="text-red-800 bg-red-100 p-2 rounded-md">
            <i class="fas fa-times-circle"></i> Transaction Failed
        </div>
        @endif
    </div>
    <div class="modal fade" id="endTransactionModal" tabindex="-1" aria-labelledby="endTransactionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-white rounded-lg shadow-lg p-6 animate-slideIn">
                <div class="modal-footer flex justify-center">
                    <form action="{{ route('end.transaction') }}" method="POST">
                        @csrf
                        <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                        <button type="submit"
                            class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 transition-all duration-300">
                            Yes, View Receipt
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .rounded-md {
        border-radius: 6px;
    }

    .p-2 {
        padding: 6px;
    }

    .text-sm {
        font-size: 14px;
    }
</style>
@endpush