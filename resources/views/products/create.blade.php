@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Add New Product</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-light">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Product Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="size">Size</label>
                            <div id="size-fields">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="sizes[0][size]"
                                        placeholder="Size (e.g., 36)" required>
                                    <input type="number" class="form-control" name="sizes[0][stock]" placeholder="Stock"
                                        min="1" required>
                                    <button type="button" class="btn btn-danger remove-size">Remove</button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success mt-2" id="add-size">Add Size</button>
                        </div>
                        <div class="form-group mb-3">
                            <label for="image_path" class="form-label">Product Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                        <button type="submit" class="btn btn-success w-100">Save Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let sizeCount = 1;
    document.getElementById('add-size').addEventListener('click', function() {
        const newSizeField = document.createElement('div');
        newSizeField.classList.add('input-group', 'mb-2');
        newSizeField.innerHTML = `
            <input type="text" class="form-control" name="sizes[${sizeCount}][size]" placeholder="Size (e.g., 36)" required>
            <input type="number" class="form-control" name="sizes[${sizeCount}][stock]" placeholder="Stock" min="1" required>
            <button type="button" class="btn btn-danger remove-size">Remove</button>
        `;
        document.getElementById('size-fields').appendChild(newSizeField);
        sizeCount++;
    });

    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-size')) {
            e.target.closest('.input-group').remove();
        }
    });
</script>
@endsection