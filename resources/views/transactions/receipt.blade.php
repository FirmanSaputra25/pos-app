<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $transaction->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Hide buttons when printing */
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <!-- Invoice Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Invoice</h1>
                <p class="text-gray-500">Transaction ID: #{{ $transaction->id }}</p>
                <p class="text-gray-500">Date: {{ $transaction->created_at->format('d-m-Y H:i') }}</p>
            </div>
            <div>
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/ac/No_image_available.svg" alt="Company Logo"
                    class="w-24">
            </div>
        </div>

        <!-- Product Details -->
        <h3 class="text-lg font-semibold mt-6 mb-2">Product Details</h3>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="border border-gray-300 px-4 py-2 text-left">Product</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Size</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Price</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Qty</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                <tr class="bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2">{{ $item->product->name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $item->productSize->size }}</td>
                    <td class="border border-gray-300 px-4 py-2">Rp{{ number_format($item->product->price, 0, ',', '.')
                        }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->quantity }}</td>
                    <td class="border border-gray-300 px-4 py-2">Rp{{ number_format($item->product->price *
                        $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div class="bg-gray-50 p-4 rounded-lg text-right mt-4">
            <p class="text-lg font-semibold text-gray-700">Total Price: <span class="text-gray-900">Rp{{
                    number_format($transaction->total_price, 0, ',', '.') }}</span></p>
            <p class="text-lg font-semibold text-gray-700">Customer Payment: <span class="text-gray-900">Rp{{
                    number_format($transaction->user_money, 0, ',', '.') }}</span></p>
            <p class="text-lg font-semibold text-green-600">Change: <span class="text-gray-900">Rp{{
                    number_format($transaction->user_money - $transaction->total_price, 0, ',', '.') }}</span></p>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center text-sm text-gray-500">
            <p>Thank you for shopping with us!</p>
            <p>For support, contact <span class="font-semibold text-gray-700">support@example.com</span></p>
        </div>

        <!-- Buttons -->
        <div class="mt-6 text-center no-print">
            <button onclick="window.print()"
                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none">Print
                Invoice</button>
            <a href="{{ route('home') }}"
                class="bg-gray-500 text-white px-6 py-2 rounded-md ml-4 hover:bg-gray-600">Back to Home</a>
        </div>
    </div>
</body>

</html>