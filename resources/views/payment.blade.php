@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Payment</h1>

    <form action="{{ route('payment.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="total_price">Total Price</label>
            <input type="number" class="form-control" id="total_price" name="total_price" value="{{ $totalPrice }}"
                readonly>
        </div>
        <div class="form-group">
            <label for="payment">Amount Paid</label>
            <input type="number" class="form-control" id="payment" name="payment" required>
        </div>
        <div class="form-group">
            <label for="change">Change</label>
            <input type="number" class="form-control" id="change" name="change" readonly>
        </div>
        <button type="submit" class="btn btn-primary">Submit Payment</button>
    </form>
</div>

<script>
    // Menghitung kembalian
    document.getElementById('payment').addEventListener('input', function() {
        const totalPrice = parseFloat(document.getElementById('total_price').value);
        const payment = parseFloat(this.value);

        if (payment >= totalPrice) {
            const change = payment - totalPrice;
            document.getElementById('change').value = change.toFixed(2);
        } else {
            document.getElementById('change').value = '';
        }
    });
</script>
@endsection