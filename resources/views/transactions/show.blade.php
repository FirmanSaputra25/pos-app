@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4 text-3xl font-bold">Transaction Details</h1>

    <!-- Menampilkan Produk yang Dibeli -->
    <div class="card shadow-lg mb-4">
        <div class="card-header bg-blue-500 text-white p-4 rounded-t-lg">
            <h3 class="mb-0 text-xl font-semibold">Selected Products</h3>
        </div>
        <div class="card-body p-4">
            @foreach ($cart as $item)
            @foreach($item as $i)
            <div class="flex justify-between items-center mb-3">
                <span>{{ $i['name'] }} (x{{ $i['quantity'] }}) - Size: {{ $i['size'] }}</span>
                <span>Rp {{ number_format($i['price'] * $i['quantity'], 0, ',', '.') }}</span>
            </div>
            @endforeach
            @endforeach
        </div>
    </div>

    <!-- Total Harga dan Uang yang Dimasukkan -->
    <div class="card shadow-lg mb-4">
        <div class="card-header bg-gray-700 text-white p-4 rounded-t-lg">
            <h4 class="mb-0 text-lg font-semibold">Transaction Summary</h4>
        </div>
        <div class="card-body p-4">
            <div class="mb-4">
                <label for="total_price" class="block text-sm font-medium">Total Price</label>
                <input type="text" class="form-control block w-full px-3 py-2 border rounded-md bg-gray-100"
                    id="total_price" name="total_price"
                    value="Rp {{ number_format($transaction->total_price, 0, ',', '.') }}" readonly>
            </div>
            <div class="mb-4">
                <label for="user_money" class="block text-sm font-medium">User Money</label>
                <input type="text" class="form-control block w-full px-3 py-2 border rounded-md bg-gray-100"
                    id="user_money" name="user_money"
                    value="Rp {{ number_format($transaction->user_money, 0, ',', '.') }}" readonly>
            </div>
            <div class="mb-4">
                <label for="change" class="block text-sm font-medium">Change</label>
                <input type="text" class="form-control block w-full px-3 py-2 border rounded-md bg-gray-100" id="change"
                    name="change"
                    value="Rp {{ number_format($transaction->user_money - $transaction->total_price, 0, ',', '.') }}"
                    readonly>
            </div>
        </div>

    </div>

    <!-- Progress Bar -->
    @if ($transaction->status == 'pending')
    <div class="mb-4">
        <div class="progress bg-gray-200 h-2 rounded-full">
            <div class="progress-bar bg-blue-600 h-2 rounded-full" style="width: 50%"></div>
        </div>
        <small class="text-muted text-sm">Processing payment...</small>
    </div>
    @endif

    <!-- End Transaction Button (Modal Trigger) -->
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
</div>
<br>
<!-- Modal for Transaction Confirmation -->
<!-- Modal End Transaction -->
<div class="modal fade" id="endTransactionModal" tabindex="-1" aria-labelledby="endTransactionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-white rounded-lg shadow-lg">
            <!-- Modal Footer -->
            <div class="modal-footer p-5 flex justify-center">
                <form action="{{ route('end.transaction') }}" method="POST">
                    @csrf
                    <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                    <button type="submit"
                        class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600">
                        Yes, View Receipt
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>




<!-- Menampilkan Status Pembayaran -->

@endsection

@push('styles')
<style>
    .btn-danger {
        width: 200px;
    }

    .modal-content {
        animation: slideIn 0.5s ease;
    }

    @keyframes slideIn {
        from {
            transform: translateY(-50%);
        }

        to {
            transform: translateY(0);
        }
    }
</style>
@endpush