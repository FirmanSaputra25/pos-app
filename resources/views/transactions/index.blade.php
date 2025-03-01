@extends('layouts.app')

@section('content')
<style>
    /* Container Styling */
    .container-custom {
        max-width: 800px;
        margin: auto;
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
    }

    /* Header Styling */
    .header-title {
        font-size: 24px;
        font-weight: bold;
        color: #2563eb;
        text-align: center;
        margin-bottom: 1rem;
    }

    /* Table Styling */
    table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 8px;
        overflow: hidden;
    }

    thead {
        background: #3b82f6;
        color: white;
    }

    th,
    td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        text-align: center;
    }

    tbody tr:hover {
        background: #f1f5f9;
    }

    /* Input Styling */
    input[type="number"] {
        width: 100%;
        padding: 10px;
        border: 2px solid #ccc;
        border-radius: 8px;
        transition: border-color 0.3s;
    }

    input[type="number"]:focus {
        border-color: #2563eb;
        outline: none;
        box-shadow: 0px 0px 10px rgba(37, 99, 235, 0.3);
    }

    /* Button Styling */
    .btn-custom {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        text-transform: uppercase;
        transition: all 0.3s ease;
    }

    .btn-green {
        background: #22c55e;
        color: white;
    }

    .btn-green:hover {
        background: #16a34a;
    }

    .btn-disabled {
        background: #94a3b8;
        cursor: not-allowed;
    }

    /* Message Styling */
    .message-box {
        padding: 10px;
        border-radius: 8px;
        font-weight: bold;
        text-align: center;
    }

    .message-warning {
        background: #facc15;
        color: #78350f;
    }

    .message-success {
        background: #22c55e;
        color: white;
    }
</style>

<div class="container-custom">
    <h1 class="header-title">Transaction Details</h1>

    <!-- Produk yang Dipilih -->
    <div class="bg-gray-100 p-4 rounded-lg shadow mb-4">
        <h2 class="text-lg font-semibold text-blue-600">Selected Products</h2>
        <div class="overflow-x-auto">
            <table>
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
    <div class="bg-gray-100 p-4 rounded-lg shadow mb-4 text-center">
        <h2 class="text-lg font-semibold text-gray-700">Total Price</h2>
        <p class="text-2xl text-red-600 font-bold">Rp {{ number_format($totalPrice, 0, ',', '.') }}</p>
    </div>

    <!-- Input Uang Pengguna -->
    <div class="bg-gray-100 p-4 rounded-lg shadow mb-4">
        <h2 class="text-lg font-semibold text-gray-700">Enter Your Money</h2>
        <input type="number" id="user_money" name="user_money" required placeholder="Enter your money">
    </div>

    <!-- Pesan jika ada kekurangan atau kelebihan -->
    <div id="message" class="mt-2"></div>

    <!-- Form untuk Submit Pembayaran -->
    <form action="{{ route('transactions.store') }}" method="POST" id="transaction-form">
        @csrf
        <input type="hidden" name="cart" value="{{ json_encode($cart) }}">
        <input type="hidden" name="total_price" value="{{ $totalPrice }}">
        <input type="hidden" name="user_money" id="user_money_value" value="">
        <br>
        <button type="submit" id="submit-btn" class="btn-custom btn-green" disabled>
            Proceed to Payment
        </button>
    </form>
</div>

<script>
    document.getElementById('transaction-form').addEventListener('submit', function (e) {
        var userMoney = document.getElementById('user_money').value;
        document.getElementById('user_money_value').value = userMoney;
        if (parseFloat(userMoney) < {{ $totalPrice }}) {
            e.preventDefault();
            alert('Insufficient money to proceed with the transaction.');
        }
    });

    const totalPrice = {{ $totalPrice }};
    document.getElementById('user_money').addEventListener('input', function () {
        const userMoney = parseFloat(this.value) || 0;
        const messageElement = document.getElementById('message');
        const submitButton = document.getElementById('submit-btn');
        
        if (userMoney < totalPrice) {
            messageElement.innerHTML = `<div class='message-box message-warning'>You need Rp ${totalPrice - userMoney} more to complete the payment.</div>`;
            submitButton.disabled = true;
        } else if (userMoney > totalPrice) {
            messageElement.innerHTML = `<div class='message-box message-success'>You have Rp ${userMoney - totalPrice} excess. Please check again.</div>`;
            submitButton.disabled = false;
        } else {
            messageElement.innerHTML = `<div class='message-box message-success'>You have entered the correct amount. Proceed with payment.</div>`;
            submitButton.disabled = false;
        }
    });
</script>
@endsection