@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <!-- Menampilkan gambar dari URL eksternal -->
            <img src="https://images.unsplash.com/photo-1593642532973-d31b6557fa68" class="img-fluid"
                alt="Product Image">
        </div>

        <div class="col-md-6">
            <h1>{{ $product->name }}</h1>
            <p><strong>Price:</strong> Rp {{ number_format($product->price, 0, ',', '.') }}</p>

            <!-- Deskripsi produk -->
            <p><strong>Description:</strong></p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque urna et risus egestas, vel
                aliquam neque venenatis. Suspendisse potenti. Etiam vel dui ut ante fermentum gravida. Vivamus non
                ligula erat. Integer sollicitudin suscipit purus, at tempor mi venenatis a. Nulla facilisi. Cras at ex
                ac nisi maximus dictum. Integer non malesuada dui.</p>

            <!-- Pilihan ukuran -->
            <form action="{{ url('cart/' . $product->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="size"><strong>Select Size:</strong></label>
                    <select name="size" id="size" class="form-control" required>
                        @foreach ($product->sizes as $size)
                        @if ($size->stock > 0)
                        <option value="{{ $size->id }}">{{ $size->size }} (Stock: {{ $size->stock }})</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-success mt-3">Add to Cart</button>
            </form>
        </div>
    </div>
</div>
@endsection