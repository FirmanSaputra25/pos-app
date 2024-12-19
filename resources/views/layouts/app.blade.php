<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Menggunakan Bootstrap 5 dari CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Menggunakan FontAwesome untuk Ikon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- File CSS yang telah dikompilasi -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <title>POS System</title>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">POS System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Menambahkan logika untuk menandai link yang aktif -->
                    <li class="nav-item {{ Request::routeIs('home') ? 'active' : '' }}">
                        <a href="{{ route('home') }}" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item {{ Request::routeIs('products.index') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('products.index') }}">Products</a>
                    </li>
                    <li class="nav-item {{ Request::routeIs('transactions.index') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('transactions.index') }}">Transactions</a>
                    </li>
                    <li class="nav-item {{ Request::is('reports') ? 'active' : '' }}">
                        <a class="nav-link" href="#">Reports</a>
                    </li>

                    <!-- Tombol Logout jika pengguna sudah login -->
                    <!-- Tombol Logout jika pengguna sudah login -->
                    @auth
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger nav-link">Logout</button>
                        </form>
                    </li>
                    @endauth

                    <!-- Jika pengguna belum login, tampilkan login dan register -->
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Container for Content -->
    <div class="container mt-5">
        <!-- Welcome Section or Heading -->
        <div class="row mb-4">
        </div>

        <!-- Content Section -->
        @yield('content')
    </div>

    @section('styles')
    <style>
        .navbar-nav .nav-item.active .nav-link {
            color: #007bff !important;
            /* Biru */
        }
    </style>
    @endsection

    <!-- Footer -->
    <footer class="bg-dark text-white py-3 mt-5">
        <div class="container text-center">
            <p>&copy; 2024 POS System. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- JS Files -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>