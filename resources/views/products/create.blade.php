@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10 px-4">
    <h1 class="text-center text-2xl font-bold mb-6">Add New Product</h1>
    <div class="flex justify-center">
        <div class="w-full max-w-lg bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Product Information</h2>
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                    <input type="text" id="name" name="name" required
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                </div>
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                    <input type="number" id="price" name="price" required
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                </div>
                <div>
                    <label for="size" class="block text-sm font-medium text-gray-700">Size</label>
                    <div id="size-fields" class="space-y-2">
                        <div class="flex space-x-2">
                            <input type="text" name="sizes[0][size]" placeholder="Size (e.g., 36)" required
                                class="w-1/2 px-3 py-2 border rounded-lg">
                            <input type="number" name="sizes[0][stock]" placeholder="Stock" min="1" required
                                class="w-1/2 px-3 py-2 border rounded-lg">
                            <button type="button"
                                class="bg-red-500 text-white px-3 py-2 rounded-lg remove-size">Remove</button>
                        </div>
                    </div>
                    <button type="button" class="mt-2 bg-green-500 text-white px-4 py-2 rounded-lg" id="add-size">Add
                        Size</button>
                </div>
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Product Image</label>
                    <input type="file" id="image" name="image" class="w-full mt-1 px-3 py-2 border rounded-lg">
                    @if ($errors->has('image'))
                    <div class="text-red-500 text-sm mt-2">
                        {{ $errors->first('image') }}
                    </div>
                    @endif

                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">Save
                    Product</button>
            </form>
        </div>
    </div>
</div>

<script>
    let sizeCount = 1;
    document.getElementById('add-size').addEventListener('click', function() {
        const newSizeField = document.createElement('div');
        newSizeField.classList.add('flex', 'space-x-2');
        newSizeField.innerHTML = `
            <input type="text" name="sizes[${sizeCount}][size]" placeholder="Size (e.g., 36)" required class="w-1/2 px-3 py-2 border rounded-lg">
            <input type="number" name="sizes[${sizeCount}][stock]" placeholder="Stock" min="1" required class="w-1/2 px-3 py-2 border rounded-lg">
            <button type="button" class="bg-red-500 text-white px-3 py-2 rounded-lg remove-size">Remove</button>
        `;
        document.getElementById('size-fields').appendChild(newSizeField);
        sizeCount++;
    });

    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-size')) {
            e.target.closest('div').remove();
        }
    });
</script>
@endsection