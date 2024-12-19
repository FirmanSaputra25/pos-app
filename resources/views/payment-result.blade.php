@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Payment Result</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h5>Total Price</h5>
                        <p>Total Price: Rp {{ number_format($totalPrice, 0, ',', '.') }}</p>


                    </div>
                    <div class="mb-3">
                        <h5>Amount Paid</h5>
                        <p class="lead text-info">{{ number_format($payment, 0, ',', '.') }}</p>
                    </div>
                    <div class="mb-3">
                        <h5>Change</h5>
                        <p class="lead text-danger">{{ number_format($change, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('transactions.index') }}" class="btn btn-outline-primary">Back to Payment</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection