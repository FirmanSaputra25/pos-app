@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10 px-4">
    <h1 class="text-3xl font-semibold text-center mb-6">Sales Report</h1>

    <!-- Container untuk Filter dan Export -->
    <div class="flex flex-wrap justify-between items-center mb-6">
        <!-- Form Pilihan Tanggal -->
        <form action="{{ route('sales.report') }}" method="GET" class="flex flex-wrap items-center space-x-2">
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="border p-2 rounded-md">
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="border p-2 rounded-md">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                Filter
            </button>
        </form>
        <!-- Tombol Export Excel -->
        <a href="{{ route('sales.export', ['start_date' => request('start_date', $startDate), 'end_date' => request('end_date', $endDate)]) }}"
            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">
            Export to Excel
        </a>

    </div>

    <!-- Menampilkan Total Pendapatan -->
    <div class="bg-gray-100 p-4 rounded-md shadow-md mb-4 text-center">
        <h3 class="text-xl font-medium text-gray-800">
            Total Sales: <span class="font-bold text-green-700">Rp {{ number_format($totalSales, 0, ',', '.') }}</span>
        </h3>
    </div>

    <!-- Tabel -->
    <div class="overflow-x-auto">
        <table class="w-full bg-white shadow-md rounded-lg border border-gray-200">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-4 py-2 text-left">Transaction ID</th>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Price</th>
                    <th class="px-4 py-2 text-left">Products Sold</th>
                    <th class="px-4 py-2 text-center">Quantity</th>
                    <th class="px-4 py-2 text-right">Total Price</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactionProducts as $transactionProduct)
                <tr class="border-t hover:bg-gray-100">
                    <td class="px-4 py-2">{{ $transactionProduct->transaction_id }}</td>
                    <td class="px-4 py-2">{{ $transactionProduct->transaction->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($transactionProduct->product->price, 0, ',', '.') }}</td>
                    <td class="px-4 py-2">{{ $transactionProduct->product->name }}</td>
                    <td class="px-4 py-2 text-center">{{ $transactionProduct->quantity }}</td>
                    <td class="px-4 py-2 text-right">
                        Rp {{ number_format($transactionProduct->product->price * $transactionProduct->quantity, 0, ',',
                        '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-2 text-center text-gray-500">No sales data available for the selected
                        date range.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection