@extends('layouts.app')


@section('content')
<div class="container mt-10">
    <h1 class="mb-6 text-center text-3xl font-extrabold text-blue-600">Your Cart</h1>

    <!-- Tampilkan pesan konfirmasi jika ada -->
    @if(session('confirmation'))
    <div class="alert alert-warning p-4 bg-yellow-200 text-yellow-800 rounded-lg mb-4">
        {{ session('confirmation') }}
        <form action="{{ url('cart/remove/'.$productId.'/'.$sizeId) }}" method="GET" class="inline-block">
            @csrf
            <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-lg mt-2">Yes, Remove it</button>
            <a href="{{ url('cart') }}" class="bg-gray-600 text-white py-2 px-4 rounded-lg mt-2">Cancel</a>
        </form>
    </div>
    @endif

    <!-- Tampilkan pesan error atau sukses jika ada -->
    @if(session('success'))
    <div class="alert alert-success p-4 bg-green-200 text-green-800 rounded-lg mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger p-4 bg-red-200 text-red-800 rounded-lg mb-4">
        {{ session('error') }}
    </div>
    @endif

    <div class="overflow-x-auto bg-white shadow-lg rounded-lg mb-6">
        <table class="min-w-full table-auto text-left">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="py-3 px-4 text-sm font-semibold">Name</th>
                    <th class="py-3 px-4 text-sm font-semibold">Size</th>
                    <th class="py-3 px-4 text-sm font-semibold">Price</th>
                    <th class="py-3 px-4 text-sm font-semibold">Quantity</th>
                    <th class="py-3 px-4 text-sm font-semibold">Action</th>
                </tr>
            </thead>
            <tbody>
                @php $totalPrice = 0; @endphp
                @foreach($cart as $productId => $sizes)
                @foreach($sizes as $sizeId => $item)
                @php $totalPrice += $item['price'] * $item['quantity']; @endphp
                <tr class="border-t border-gray-200 mb-6">
                    <td class="py-3 px-4">{{ $item['name'] }}</td>
                    <td class="py-3 px-4">{{ $item['size'] }}</td>
                    <td class="py-3 px-4">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                    <td class="py-3 px-4">{{ $item['quantity'] }}</td>
                    <td class="py-3 px-4">
                        <form action="{{ url('cart/remove/'.$productId.'/'.$sizeId) }}" method="GET"
                            class="inline-block">
                            @csrf
                            <button type="submit"
                                class="bg-red-500 text-white py-1 px-4 rounded-lg text-sm">Remove</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Total Belanja -->
    <div class="flex justify-end mb-6">
        <h4 class="text-xl text-green-600 font-bold">Total Belanja: Rp {{ number_format($totalPrice, 0, ',', '.') }}
        </h4>
    </div>

    <!-- Tombol Proceed to Payment -->
    <div class="flex justify-end">
        <form action="{{ route('transactions.index') }}" method="GET">
            <input type="hidden" name="total_price" value="{{ $totalPrice }}">
            <button type="submit"
                class="bg-green-600 text-white py-3 px-6 rounded-lg shadow-lg hover:bg-green-700 transition duration-300">Proceed
                to Payment</button>
        </form>
    </div>
</div>
@endsection