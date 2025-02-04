<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <title>POS System</title>
</head>

<body class="bg-gray-100 flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 h-screen text-white p-5 fixed">
        <h1 class="text-2xl font-bold mb-6">POS System</h1>
        <ul>
            <li class="mb-4">
                <a href="{{ route('home') }}"
                    class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('home') ? 'bg-gray-700' : '' }}">
                    Home
                </a>
            </li>
            <li class="mb-4">
                <a href="{{ route('products.index') }}"
                    class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('products.index') ? 'bg-gray-700' : '' }}">
                    Products
                </a>
            </li>
            <li class="mb-4">
                <a href="{{ route('transactions.index') }}"
                    class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('transactions.index') ? 'bg-gray-700' : '' }}">
                    Transactions
                </a>
            </li>
            @if(auth()->check() && auth()->user()->role === 'admin')
            <li class="mb-4">
                <a href="{{ route('sales.report') }}"
                    class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('sales.report') ? 'bg-gray-700' : '' }}">
                    Reports
                </a>
            </li>
            @endif
            <li class="mb-4">
                <a href="{{ route('cart.index') }}"
                    class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('cart.index') ? 'bg-gray-700' : '' }}">
                    Cart
                </a>
            </li>
            @auth
            <li class="mb-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="block w-full text-left py-2 px-4 rounded hover:bg-red-600">Logout</button>
                </form>
            </li>
            @endauth
            @guest
            <li class="mb-4">
                <a href="{{ route('login') }}"
                    class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('login') ? 'bg-gray-700' : '' }}">
                    Login
                </a>
            </li>
            <li class="mb-4">
                <a href="{{ route('register') }}"
                    class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('register') ? 'bg-gray-700' : '' }}">
                    Register
                </a>
            </li>
            @endguest
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 p-10 w-full">
        @yield('content')
    </main>
</body>

</html>