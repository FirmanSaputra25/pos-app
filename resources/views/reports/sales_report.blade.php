@extends('layouts.app')

@section('content')
<div class="container mt-10 px-4">
    <h1 class="text-3xl font-semibold text-center mb-6">Sales Report</h1>

    <!-- Menampilkan Total Pendapatan -->
    <h3 class="text-xl font-medium text-gray-800 mb-4">Total Sales: Rp {{ number_format($totalSales, 0, ',', '.') }}
    </h3>

    <!-- Tabel Daftar Transaksi -->
    <table class="min-w-full table-auto bg-white shadow-md rounded-lg">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="px-4 py-2 text-left">Transaction ID</th>
                <th class="px-4 py-2 text-left">Date</th>
                <th class="px-4 py-2 text-left">Customer</th>
                <th class="px-4 py-2 text-left">Total Price</th>
                <th class="px-4 py-2 text-left">Products Sold</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $transaction->id }}</td>
                <td class="px-4 py-2">{{ $transaction->created_at->format('d M Y') }}</td>
                <td class="px-4 py-2">{{ optional($transaction->user)->name ?? 'User Not Found' }}</td>
                <td class="px-4 py-2">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                <td class="px-4 py-2">
                    <!-- Cek apakah produk ada dan tidak kosong -->
                    @if($transaction->products && $transaction->products->isNotEmpty())
                    <ul>
                        @foreach($transaction->products as $product)
                        <li>
                            {{ $product->name }}
                            (Qty: {{ $product->pivot->quantity }})
                            (Rp {{ number_format($product->pivot->price, 0, ',', '.') }})
                            - Total: Rp {{ number_format($product->pivot->quantity * $product->pivot->price, 0, ',',
                            '.') }}
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-gray-500">No products found for this transaction.</p>
                    @endif
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Button untuk Export ke Excel atau PDF (Opsional) -->
    <div class="mt-6 text-center">
        <button class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none">Export to
            Excel</button>
        <button class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 focus:outline-none ml-4">Export
            to PDF</button>
    </div>
</div>
@endsection