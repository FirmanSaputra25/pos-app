@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
    <h1 class="text-center text-blue-600 text-2xl font-bold mb-6">Transaction Details</h1>

    <!-- Produk yang Dipilih -->
    <div class="bg-gray-100 p-4 rounded-lg shadow mb-4">
        <h2 class="text-lg font-semibold text-blue-600">Selected Products</h2>
        <div class="overflow-x-auto">
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
                    @foreach($cart as $productId => $sizes)
                    @foreach($sizes as $sizeId => $item)
                    <tr class="text-center">
                        <td class="p-2 border">{{ $item['name'] }}</td>
                        <td class="p-2 border">{{ $item['size'] }}</td>
                        <td class="p-2 border">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                        <td class="p-2 border">{{ $item['quantity'] }}</td>
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
        <input type="number" id="user_money" name="user_money" required placeholder="Enter your money"
            class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 mt-2">
    </div>

    <!-- Pesan jika ada kekurangan atau kelebihan -->
    <div id="message" class="mt-2"></div>

    <!-- Form untuk Submit Pembayaran -->
    <form action="{{ route('transactions.store') }}" method="POST" id="transaction-form">
        @csrf
        <input type="hidden" name="cart" value="{{ json_encode($cart) }}">
        <input type="hidden" name="total_price" value="{{ $totalPrice }}">
        <input type="hidden" name="user_money" id="user_money_value" value="">

        <button type="submit" id="submit-btn"
            class="w-full bg-green-500 text-white font-semibold py-3 rounded-lg shadow mt-4 transition duration-300 hover:bg-green-600 disabled:bg-gray-400 disabled:cursor-not-allowed"
            disabled>
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
            messageElement.innerHTML = `<div class='p-3 rounded-lg bg-yellow-400 text-black text-center'>You need Rp ${totalPrice - userMoney} more to complete the payment.</div>`;
            submitButton.disabled = true;
        } else if (userMoney > totalPrice) {
            messageElement.innerHTML = `<div class='p-3 rounded-lg bg-green-500 text-white text-center'>You have Rp ${userMoney - totalPrice} excess. Please check again.</div>`;
            submitButton.disabled = false;
        } else {
            messageElement.innerHTML = `<div class='p-3 rounded-lg bg-green-500 text-white text-center'>You have entered the correct amount. Proceed with payment.</div>`;
            submitButton.disabled = false;
        }
    });
</script>
@endsection