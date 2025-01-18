@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center text-primary mb-4 font-weight-bold">Transaction Details</h1>

    <!-- Produk yang Dipilih -->
    <div class="card shadow-sm mb-2">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Selected Products</h4>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Size</th>
                        <th>Price</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $productId => $sizes)
                    @foreach($sizes as $sizeId => $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['size'] }}</td>
                        <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                        <td>{{ $item['quantity'] }}</td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Total Harga -->
    <div class="card shadow-sm mb-2">
        <div class="card-body text-center">
            <h4 class="text-muted">Total Price</h4>
            <p class="h3 text-danger font-weight-bold">
                Rp {{ number_format($totalPrice, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Input Uang Pengguna -->
    <div class="card shadow-sm mb-2">
        <div class="card-body">
            <h4 class="text-muted">Enter Your Money</h4>
            <input type="number" class="form-control shadow-sm rounded" id="user_money" name="user_money" required
                placeholder="Enter your money" style="border-color: #00aaff;">
        </div>
    </div>

    <!-- Pesan jika ada kekurangan atau kelebihan -->
    <div id="message" class="mt-2"></div>

    <!-- Form untuk Submit Pembayaran -->
    <form action="{{ route('transactions.store') }}" method="POST" id="transaction-form">
        @csrf
        <input type="hidden" name="cart" value="{{ json_encode($cart) }}">
        <input type="hidden" name="total_price" value="{{ $totalPrice }}">
        <input type="hidden" name="user_money" id="user_money_value" value="">

        <button type="submit" id="submit-btn" class="btn btn-lg btn-success btn-block mt-4 py-3 shadow-sm"
            style="font-size: 18px; transition: background-color 0.3s ease; border-radius: 30px;" disabled>
            Proceed to Payment
        </button>
    </form>

    <script>
        document.getElementById('transaction-form').addEventListener('submit', function (e) {
            var userMoney = document.getElementById('user_money').value;
            document.getElementById('user_money_value').value = userMoney;

            // Cek apakah uang cukup
            if (parseFloat(userMoney) < {{ $totalPrice }}) {
                e.preventDefault();  // Mencegah form disubmit
                alert('Insufficient money to proceed with the transaction.');
            }
        });

        // Menghitung total harga
        const totalPrice = {{ $totalPrice }};
        
        // Menangani input uang dari pengguna
        document.getElementById('user_money').addEventListener('input', function () {
            const userMoney = parseFloat(this.value) || 0;
            const messageElement = document.getElementById('message');
            const submitButton = document.getElementById('submit-btn');
            
            if (userMoney < totalPrice) {
                messageElement.innerHTML = `<div class="alert alert-warning fade show" role="alert" style="border-radius: 20px; background-color: #ffcc00;">You need Rp ${totalPrice - userMoney} more to complete the payment.</div>`;
                submitButton.disabled = true;  // Nonaktifkan tombol submit
            } else if (userMoney > totalPrice) {
                messageElement.innerHTML = `<div class="alert alert-success fade show" role="alert" style="border-radius: 20px; background-color: #28a745;">You have Rp ${userMoney - totalPrice} excess. Please check again.</div>`;
                submitButton.disabled = false;  // Aktifkan tombol submit
            } else {
                messageElement.innerHTML = `<div class="alert alert-success fade show" role="alert" style="border-radius: 20px; background-color: #28a745;">You have entered the correct amount. Proceed with payment.</div>`;
                submitButton.disabled = false;  // Aktifkan tombol submit
            }
        });
    </script>
</div>
@endsection