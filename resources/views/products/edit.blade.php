@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Product</h1>
    <form action="{{ route('products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
        </div>

        <div class="form-group">
            <label for="stock">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" value="{{ $product->stock }}" required>
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ $product->price }}" required>
        </div>

        <!-- Size Fields -->
        <div class="form-group">
            <label for="size">Size</label><br>
            <div id="size-fields">
                <!-- Existing sizes from the database or old input will be shown here -->
                @foreach ($product->sizes as $index => $size)
                <div class="input-group mb-2">
                    <input type="text" class="form-control" name="sizes[{{ $index }}][size]" value="{{ $size->size }}"
                        placeholder="Size (e.g., 36)" required>
                    <input type="number" class="form-control" name="sizes[{{ $index }}][stock]"
                        value="{{ $size->stock }}" placeholder="Stock" min="1" required>
                    <button type="button" class="btn btn-danger remove-size">Remove</button>
                </div>
                @endforeach
            </div>

            <button type="button" id="add-size" class="btn btn-primary">Add Size</button>
        </div>
        <br>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk membuat input baru untuk size dan stock
    function createSizeInput(index) {
        const div = document.createElement('div');
        div.classList.add('input-group', 'mb-2');

        // Input untuk Size
        const sizeInput = document.createElement('input');
        sizeInput.type = 'text';
        sizeInput.classList.add('form-control');
        sizeInput.name = `sizes[${index}][size]`;
        sizeInput.placeholder = 'Size (e.g., 36)';
        sizeInput.required = true;

        // Input untuk Stock
        const stockInput = document.createElement('input');
        stockInput.type = 'number';
        stockInput.classList.add('form-control');
        stockInput.name = `sizes[${index}][stock]`;
        stockInput.placeholder = 'Stock';
        stockInput.min = 1;
        stockInput.required = true;

        // Tombol Remove
        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.classList.add('btn', 'btn-danger', 'remove-size');
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