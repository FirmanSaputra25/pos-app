@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Sales Report</h1>

    <!-- Menampilkan Total Pendapatan -->
    <h3>Total Sales: Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>

    <!-- Tabel Daftar Transaksi -->
    <table class="table table-bordered mt-4">
        <thead class="thead-dark">
            <tr>
                <th>Transaction ID</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Total Price</th>
                <th>Products Sold</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->created_at->format('d M Y') }}</td>
                <td>{{ optional($transaction->user)->name ?? 'User Not Found' }}</td>
                <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                <td>
                    <!-- Cek apakah ada produk dalam transaksi -->
                    @if($transaction->products && $transaction->products->isNotEmpty())
                    @foreach($transaction->products as $product)
                    {{ $product->name }}
                    <!-- Menampilkan hanya nama produk yang dibeli -->
                    @endforeach
                    @else
                    <p>No products found for this transaction.</p>
                    @endif
                </td>


            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Button untuk Export ke Excel atau PDF (Opsional) -->

</div>
@endsection