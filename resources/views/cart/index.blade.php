@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4 text-center text-primary font-weight-bold">Your Cart</h1>

    <!-- Tampilkan pesan konfirmasi jika ada -->
    @if(session('confirmation'))
    <div class="alert alert-warning">
        {{ session('confirmation') }}
        <form action="{{ url('cart/remove/'.$productId.'/'.$sizeId) }}" method="GET" class="d-inline-block">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm mt-2">Yes, Remove it</button>
            <a href="{{ url('cart') }}" class="btn btn-secondary btn-sm mt-2">Cancel</a>
        </form>
    </div>
    @endif

    <!-- Tampilkan pesan error atau sukses jika ada -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="card shadow-lg p-3 mb-5 bg-white rounded">
        <table class="table table-striped table-hover">
            <thead class="bg-dark text-white">
                <tr>
                    <th>Name</th>
                    <th>Size</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php $totalPrice = 0; @endphp
                @foreach($cart as $productId => $sizes)
                @foreach($sizes as $sizeId => $item)
                @php $totalPrice += $item['price'] * $item['quantity']; @endphp
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['size'] }}</td>
                    <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                    <td>
                        <span>{{ $item['quantity'] }}</span>
                    </td>
                    <td>
                        <!-- Tombol Remove dengan modal -->
                        <button type="button" class="btn btn-danger btn-sm mt-2" data-bs-toggle="modal"
                            data-bs-target="#removeModal-{{ $productId }}-{{ $sizeId }}">
                            Remove
                        </button>
                    </td>
                </tr>
                @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Total Belanja -->
    <h4 class="text-right text-success font-weight-bold">Total Belanja: Rp {{ number_format($totalPrice, 0, ',', '.') }}
    </h4>

    <!-- Tombol Proceed to Payment -->
    <div class="d-flex justify-content-end mt-4">
        <form action="{{ route('transactions.index') }}" method="GET">
            <input type="hidden" name="total_price" value="{{ $totalPrice }}">
            <button type="submit" class="btn btn-success btn-lg shadow-lg">Proceed to Payment</button>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Remove untuk setiap item -->
@foreach($cart as $productId => $sizes)
@foreach($sizes as $sizeId => $item)
<div class="modal fade" id="removeModal-{{ $productId }}-{{ $sizeId }}" tabindex="-1"
    aria-labelledby="removeModalLabel-{{ $productId }}-{{ $sizeId }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeModalLabel-{{ $productId }}-{{ $sizeId }}">Confirm Remove Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to remove {{ $item['name'] }} (Size: {{ $item['size'] }}) from your cart? This
                action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ url('cart/remove/'.$productId.'/'.$sizeId) }}" method="GET">
                    @csrf
                    <button type="submit" class="btn btn-danger">Yes, Remove</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endforeach

@endsection