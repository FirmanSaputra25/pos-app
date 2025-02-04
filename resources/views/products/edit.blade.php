@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-semibold text-center mb-6">Edit Product</h1>
    <form action="{{ route('products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
            <input type="text"
                class="form-control w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                id="name" name="name" value="{{ $product->name }}" required>
        </div>

        <div class="form-group mb-4">
            <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
            <input type="number"
                class="form-control w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                id="stock" name="stock" value="{{ $product->stock }}" required>
        </div>

        <div class="form-group mb-4">
            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
            <input type="number"
                class="form-control w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                id="price" name="price" value="{{ $product->price }}" required>
        </div>

        <!-- Size Fields -->
        <div class="form-group mb-4">
            <label for="size" class="block text-sm font-medium text-gray-700">Size</label><br>
            <div id="size-fields">
                <!-- Existing sizes from the database or old input will be shown here -->
                @foreach ($product->sizes as $index => $size)
                <div class="input-group mb-4 flex space-x-2 items-center">
                    <input type="text"
                        class="form-control w-1/2 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        name="sizes[{{ $index }}][size]" value="{{ $size->size }}" placeholder="Size (e.g., 36)"
                        required>
                    <input type="number"
                        class="form-control w-1/4 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        name="sizes[{{ $index }}][stock]" value="{{ $size->stock }}" placeholder="Stock" min="1"
                        required>
                    <button type="button"
                        class="btn btn-danger px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 remove-size">Remove</button>
                </div>
                @endforeach
            </div>

            <button type="button" id="add-size"
                class="btn btn-primary w-full px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 mt-4">Add
                Size</button>
        </div>
        <button type="submit"
            class="btn btn-primary w-full px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 mt-4">Update</button>
    </form>
</div>
@endsection


@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk membuat input baru untuk size dan stock
    function createSizeInput(index) {
        const div = document.createElement('div');
        div.classList.add('input-group', 'mb-6', 'flex', 'space-x-2', 'items-center');

        // Input untuk Size
        const sizeInput = document.createElement('input');
        sizeInput.type = 'text';
        sizeInput.classList.add('form-control', 'w-1/2', 'px-4', 'py-2', 'border', 'rounded-md', 'focus:outline-none', 'focus:ring-2', 'focus:ring-blue-500');
        sizeInput.name = `sizes[${index}][size]`;
        sizeInput.placeholder = 'Size (e.g., 36)';
        sizeInput.required = true;

        // Input untuk Stock
        const stockInput = document.createElement('input');
        stockInput.type = 'number';
        stockInput.classList.add('form-control', 'w-1/4', 'px-4', 'py-2', 'border', 'rounded-md', 'focus:outline-none', 'focus:ring-2', 'focus:ring-blue-500');
        stockInput.name = `sizes[${index}][stock]`;
        stockInput.placeholder = 'Stock';
        stockInput.min = 1;
        stockInput.required = true;

        // Tombol Remove
        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.classList.add('btn', 'btn-danger', 'px-4', 'py-2', 'bg-red-500', 'text-white', 'rounded-md', 'hover:bg-red-600', 'remove-size');
        removeButton.textContent = 'Remove';

        // Menambahkan input dan tombol remove ke dalam div
        div.appendChild(sizeInput);
        div.appendChild(stockInput);
        div.appendChild(removeButton);

        return div;
    }

    // Event listener untuk tombol "Add Size"
    document.getElementById('add-size').addEventListener('click', function() {
        const sizeFields = document.getElementById('size-fields');
        const index = sizeFields.getElementsByClassName('input-group').length; // Mengambil indeks berikutnya
        const newSizeField = createSizeInput(index);
        sizeFields.appendChild(newSizeField);

        // Debugging: Cek apakah fungsi ini dijalankan
        console.log("Add Size button clicked!");
    });

    // Event delegation untuk menghapus size dengan konfirmasi
    document.getElementById('size-fields').addEventListener('click', function(event) {
        if (event.target && event.target.classList.contains('remove-size')) {
            // Konfirmasi sebelum menghapus
            if (confirm("Are you sure you want to remove this size?")) {
                event.target.closest('.input-group').remove();
                alert("Size has been removed.");
            }
        }
    });
});

</script>