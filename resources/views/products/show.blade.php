@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Menampilkan alert jika ada pesan sukses atau error -->
    @if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    @elseif(session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <!-- Menampilkan gambar produk -->
            <img src="https://images.unsplash.com/photo-1593642532973-d31b6557fa68" class="img-fluid"
                alt="Product Image">
        </div>

        <div class="col-md-6">
            <h1>{{ $product->name }}</h1>
            <p><strong>Price:</strong> Rp {{ number_format($product->price, 0, ',', '.') }}</p>

            <!-- Deskripsi produk -->
            <p><strong>Description:</strong></p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque urna et risus egestas, vel
                aliquam neque venenatis.</p>

            <!-- Pilihan ukuran -->
            <form action="{{ url('cart/' . $product->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="size"><strong>Select Size:</strong></label>
                    <select name="size" id="size" class="form-control" required>
                        @foreach ($product->productSizes as $size)
                        @if ($size->stock > 0)
                        <option value="{{ $size->id }}" data-stock="{{ $size->stock }}">
                            {{ $size->size }} (Stock: {{ $size->stock }})
                        </option>
                        @endif
                        @endforeach
                    </select>
                </div>

                <!-- Input untuk memilih quantity -->
                <div class="form-group">
                    <label for="quantity"><strong>Select Quantity:</strong></label>
                    <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" required>
                    <small class="text-muted">Max stock: <span id="max-stock">0</span></small>
                </div>

                <button type="submit" class="btn btn-success mt-3">Add to Cart</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Update the max stock value dynamically based on selected size
    document.getElementById('size').addEventListener('change', function () {
        var selectedOption = this.options[this.selectedIndex];
        var stock = selectedOption.getAttribute('data-stock');
        
        // Update the max value for the quantity input
        document.getElementById('quantity').setAttribute('max', stock);
        document.getElementById('max-stock').textContent = stock;
    });

    // Set the max stock value when the page loads based on the initially selected size
    window.onload = function() {
        var selectedOption = document.getElementById('size').options[document.getElementById('size').selectedIndex];
        var stock = selectedOption.getAttribute('data-stock');
        
        document.getElementById('quantity').setAttribute('max', stock);
        document.getElementById('max-stock').textContent = stock;
    };
</script>
@endsection