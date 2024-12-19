@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Transactions</h1>

    <!-- Form Pencarian Produk -->
    <div class="mb-4">
        <input type="text" class="form-control" id="search-input" placeholder="Search for products...">
    </div>

    <!-- Gambar Produk dan Tombol Pilih -->
    <div class="row row-cols-1 row-cols-md-3 g-4" id="product-list">
        @foreach ($products as $product)
        <div class="col-md-4 col-sm-6 mb-4 product-item" data-name="{{ strtolower($product->name) }}">
            <div class="card shadow-sm h-100">
                <!-- Gambar Produk dengan Fallback Gambar Default -->
                <img src="{{ file_exists(public_path('images/' . $product->id . '.jpg')) ? asset('images/' . $product->id . '.jpg') : 'https://via.placeholder.com/300x200.png?text=No+Image' }}"
                    alt="{{ $product->name }}" class="card-img-top img-fluid" style="height: 200px; object-fit: cover;">

                <!-- Informasi Produk -->
                <div class="card-body">
                    <h5 class="card-title" style="font-size: 16px;">{{ $product->name }}</h5>
                    <p class="card-text" style="font-size: 14px;">Price: Rp {{ number_format($product->price, 0, ',',
                        '.') }}</p>
                    <p class="card-text" style="font-size: 14px;">Stock: {{ $product->stock }}</p>

                    <!-- Tombol Add to Cart -->
                    <button class="btn btn-sm btn-success add-to-cart" data-id="{{ $product->id }}"
                        data-name="{{ $product->name }}" data-price="{{ $product->price }}"
                        data-stock="{{ $product->stock }}" style="font-size: 12px;">+ Add to Cart</button>
                </div>
            </div>
        </div>
        @endforeach

    </div>

    <!-- Produk yang Dipilih -->
    <h3 class="mt-5">Selected Products</h3>
    <div id="selected-products"></div>

    <!-- Form Submit untuk Pembayaran -->
    <form action="{{ route('transactions.index') }}" method="POST" id="transaction-form">
        @csrf
        <input type="hidden" name="products" id="selected-products-input">
        <div class="form-group mt-3">
            <label for="total_price">Total Price</label>
            <input type="text" class="form-control" id="total_price" name="total_price" required readonly>
        </div>
        <button type="submit" class="btn btn-primary btn-sm mt-3">Proceed to Payment</button>
    </form>
</div>

<script>
    const selectedProducts = [];

    // Fungsi untuk format harga dalam format Rupiah
    function formatRupiah(number) {
        return "Rp " + number.toLocaleString('id-ID');
    }

    // Fungsi untuk menampilkan produk yang dipilih
    function updateSelectedProducts() {
        const selectedProductsContainer = document.getElementById('selected-products');
        const totalPriceElement = document.getElementById('total_price');
        selectedProductsContainer.innerHTML = ''; // Clear previous selected products
        let totalPrice = 0;
        selectedProducts.forEach(product => {
            const productElement = document.createElement('div');
            productElement.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'mb-3');
            productElement.innerHTML = `
                <span>${product.name} (x${product.quantity})</span>
                <span>${formatRupiah(product.totalPrice)}</span> <!-- Format harga sebagai Rupiah -->
                <button class="btn btn-danger btn-sm remove-product" data-id="${product.id}">Remove</button>
            `;
            selectedProductsContainer.appendChild(productElement);
            totalPrice += product.totalPrice;
        });
        totalPriceElement.value = formatRupiah(totalPrice); // Format total price menjadi Rupiah

        // Update hidden input untuk produk yang dipilih
        document.getElementById('selected-products-input').value = JSON.stringify(selectedProducts);
    }

    // Menambahkan produk ke keranjang
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function () {
            const product = {
                id: this.getAttribute('data-id'),
                name: this.getAttribute('data-name'),
                price: parseFloat(this.getAttribute('data-price')),
                stock: parseInt(this.getAttribute('data-stock')),
                quantity: 1, // Set default quantity
                totalPrice: parseFloat(this.getAttribute('data-price'))
            };

            // Cek apakah produk sudah ada di keranjang
            const existingProduct = selectedProducts.find(p => p.id === product.id);
            if (existingProduct) {
                // Jika ada, tambahkan quantity
                if (existingProduct.quantity < existingProduct.stock) {
                    existingProduct.quantity++;
                    existingProduct.totalPrice = existingProduct.quantity * existingProduct.price;
                }
            } else {
                // Jika belum ada, tambahkan produk baru ke keranjang
                selectedProducts.push(product);
            }

            // Update daftar produk yang dipilih
            updateSelectedProducts();
        });
    });

    // Menghapus produk dari keranjang
    document.getElementById('selected-products').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-product')) {
            const productId = e.target.getAttribute('data-id');
            const index = selectedProducts.findIndex(p => p.id === productId);
            if (index !== -1) {
                selectedProducts.splice(index, 1); // Remove the product
                updateSelectedProducts(); // Update display
            }
        }
    });

    // Submit form
    document.getElementById('transaction-form').addEventListener('submit', function () {
        if (selectedProducts.length === 0) {
            alert('Please add products to the cart.');
            return false; // Prevent form submission
        }

        // Attach the selected products to the form before submission
        document.getElementById('selected-products-input').value = JSON.stringify(selectedProducts);
    });

    // Fitur pencarian produk
    document.getElementById('search-input').addEventListener('input', function () {
        const query = this.value.toLowerCase();
        const products = document.querySelectorAll('.product-item');
        products.forEach(product => {
            const name = product.getAttribute('data-name');
            if (name.includes(query)) {
                product.style.display = '';
            } else {
                product.style.display = 'none';
            }
        });
    });
</script>
@endsection