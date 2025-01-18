@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Transaction Details</h1>

    <!-- Menampilkan Produk yang Dibeli -->
    <div class="card shadow-lg mb-4">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Selected Products</h3>
        </div>
        <div class="card-body">
            @foreach ($cart as $item)
            @foreach($item as $i)
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span>{{ $i['name'] }} (x{{ $i['quantity'] }}) - Size: {{ $i['size'] }}</span>
                <span>Rp {{ number_format($i['price'] * $i['quantity'], 0, ',', '.') }}</span>
            </div>
            @endforeach
            @endforeach
        </div>
    </div>

    <!-- Total Harga dan Uang yang Dimasukkan -->
    <div class="card shadow-lg mb-4">
        <div class="card-header bg-secondary text-white">
            <h4 class="mb-0">Transaction Summary</h4>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="total_price">Total Price</label>
                <input type="text" class="form-control" id="total_price" name="total_price" value="Rp 
                    {{ number_format($transaction->total_price, 0, ',', '.') }}" readonly>
            </div>
            <div class="form-group">
                <label for="user_money">User Money</label>
                <input type="text" class="form-control" id="user_money" name="user_money" value="Rp 
                    {{ number_format($transaction->user_money, 0, ',', '.') }}" readonly>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    @if ($transaction->status == 'pending')
    <div class="mb-4">
        <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 50%"></div>
        </div>
        <small class="text-muted">Processing payment...</small>
    </div>
    @endif

    <!-- End Transaction Button (Modal Trigger) -->
    <div class="d-flex justify-content-center">
        <button class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#endTransactionModal">End
            Transaction</button>
    </div>

    <!-- Modal for Transaction Confirmation -->
    <div class="modal fade" id="endTransactionModal" tabindex="-1" aria-labelledby="endTransactionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="endTransactionModalLabel">Confirm End Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to complete this transaction? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('end.transaction') }}" method="POST">
                        @csrf
                        <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                        <button type="submit" class="btn btn-danger">Yes, End Transaction</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Menampilkan Status Pembayaran -->
    <div class="card shadow-lg mt-4">
        <div class="card-body text-center">
            @if ($transaction->status == 'pending')
            <div class="alert alert-warning">
                <i class="fas fa-clock"></i> Payment is still pending.
            </div>
            @elseif ($transaction->status == 'paid')
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> Payment successfully completed.
            </div>
            @else
            <div class="alert alert-danger">
                <i class="fas fa-times-circle"></i> Transaction failed. Please try again.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-header {
        font-size: 1.25rem;
    }

    .form-control[readonly] {
        background-color: #f8f9fa;
    }

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