<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    {{-- <script src="https://cdn.jsdelivr.net/npm/lucide@latest"></script> --}}
    <title>POS System</title>
</head>

<body class="bg-gray-100 flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 h-screen text-white p-5 fixed flex flex-col">
        <h1 class="text-2xl font-bold mb-6 text-center flex items-center justify-center space-x-2">
            <i data-lucide="store"></i>
            <span>POS System</span>
        </h1>

        <!-- User Info -->
        @if(auth()->check())
        <div class="mb-6 p-4 bg-gray-700 rounded flex items-center space-x-3">
            <img src="{{ auth()->user()->profile_photo ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=4A5568&color=ffffff&size=100' }}"
                alt="User Avatar" class="w-12 h-12 rounded-full object-cover bg-gray-600">

            <div>
                <p class="text-lg font-semibold">{{ auth()->user()->name }}</p>
                <p class="text-sm text-gray-300 capitalize">{{ auth()->user()->role }}</p>
            </div>
        </div>
        @endif

        <!-- Menu -->
        <ul class="flex-1">
            <li class="mb-4">
                <a href="{{ route('home') }}"
                    class="flex items-center py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('home') ? 'bg-gray-700' : '' }}">
                    <i data-lucide="home" class="mr-2"></i> Home
                </a>
            </li>
            <li class="mb-4">
                <a href="{{ route('products.index') }}"
                    class="flex items-center py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('products.index') ? 'bg-gray-700' : '' }}">
                    <i data-lucide="box" class="mr-2"></i> Products
                </a>
            </li>
            <li class="mb-4">
                <a href="{{ route('transactions.index') }}"
                    class="flex items-center py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('transactions.index') ? 'bg-gray-700' : '' }}">
                    <i data-lucide="credit-card" class="mr-2"></i> Transactions
                </a>
            </li>
            @if(auth()->check() && auth()->user()->role === 'admin')
            <li class="mb-4">
                <a href="{{ route('sales.report') }}"
                    class="flex items-center py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('sales.report') ? 'bg-gray-700' : '' }}">
                    <i data-lucide="bar-chart" class="mr-2"></i> Reports
                </a>
            </li>
            @endif
            <li class="mb-4">
                <a href="{{ route('cart.index') }}"
                    class="flex items-center py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('cart.index') ? 'bg-gray-700' : '' }}">
                    <i data-lucide="shopping-cart" class="mr-2"></i> Cart
                </a>
            </li>
        </ul>

        <!-- Auth Buttons -->
        @auth
        <div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center w-full text-left py-2 px-4 rounded hover:bg-red-600">
                    <i data-lucide="log-out" class="mr-2"></i> Logout
                </button>
            </form>
        </div>
        @endauth
        @guest
        <div>
            <a href="{{ route('login') }}"
                class="flex items-center py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('login') ? 'bg-gray-700' : '' }}">
                <i data-lucide="log-in" class="mr-2"></i> Login
            </a>
            <a href="{{ route('register') }}"
                class="flex items-center py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('register') ? 'bg-gray-700' : '' }}">
                <i data-lucide="user-plus" class="mr-2"></i> Register
            </a>
        </div>
        @endguest
    </aside>
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Main Content -->
    <main class="ml-64 p-10 w-full">
        @yield('content')
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>