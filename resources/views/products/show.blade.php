@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg mt-5">
    <!-- Menampilkan alert jika ada pesan sukses atau error -->
    @if(session('success'))
    <script>
        window.onload = function () {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 4000
            });
        };
    </script>
    @elseif(session('error'))
    <script>
        window.onload = function () {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 4000
            });
        };
    </script>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @if($product->image_path)
        <img src="{{ asset('storage/images/' . $product->image_path) }}" class="w-full h-96 object-cover rounded-lg"
            alt="Product Image" style="object-fit: cover;">
        @else
        <p>No Image Available</p>
        @endif

        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h1>
            <p class="text-lg text-gray-600 mt-2"><strong>Price:</strong> Rp {{ number_format($product->price, 0, ',',
                '.') }}</p>

            <!-- Deskripsi produk -->
            <p class="mt-4 text-gray-700"><strong>Description:</strong></p>
            <p class="text-gray-600">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque urna et
                risus egestas, vel
                aliquam neque venenatis.</p>

            <!-- Pilihan ukuran -->
            <form action="{{ url('cart/' . $product->id) }}" method="POST" class="mt-4">
                @csrf
                <div class="mb-3">
                    <label for="size" class="block font-semibold">Select Size:</label>
                    <select name="size" id="size" class="w-full p-2 border border-gray-300 rounded" required>
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
                <div class="mb-3">
                    <label for="quantity" class="block font-semibold">Select Quantity:</label>
                    <input type="number" name="quantity" id="quantity" class="w-full p-2 border border-gray-300 rounded"
                        value="1" min="1" required>
                    <small class="text-gray-500">Max stock: <span id="max-stock">0</span></small>
                </div>

                <button type="submit" class="w-full bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Add to
                    Cart</button>
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