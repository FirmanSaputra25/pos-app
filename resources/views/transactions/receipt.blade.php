<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $transaction->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        .invoice-container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
        }

        h1 {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .invoice-header,
        .invoice-footer {
            text-align: center;
        }

        .invoice-info {
            border-top: 2px solid #333;
            padding-top: 10px;
            margin-bottom: 15px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }

        .info-item span {
            font-weight: bold;
            color: #222;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #f1f1f1;
        }

        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }

            .invoice-container {
                box-shadow: none;
                border: none;
                padding: 10px;
                max-width: 100%;
            }

            .no-print {
                display: none;
            }

            @page {
                margin: 10mm;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h1>Invoice</h1>
            <p>Transaction ID: #{{ $transaction->id }}</p>
            <p>Date: {{ $transaction->created_at->format('d-m-Y H:i') }}</p>
            <p class="text-lg font-semibold">
                {{ strtoupper(auth()->user()->name) }} - {{ strtoupper(auth()->user()->role) }}
            </p>

        </div>

        <h3 class="text-lg font-semibold">Product Details</h3>
        @if ($items->isNotEmpty())
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th class="text-center border px-4 py-2">Product</th>
                    <th class="text-center border px-4 py-2">Size</th>
                    <th class="text-center border px-4 py-2">Quantity</th>
                    <th class="text-center border px-4 py-2">Price</th>
                    <th class="text-center border px-4 py-2">Total Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                <tr>
                    <td class="text-center border px-4 py-2">{{ $item->product_name }}</td>
                    <td class="text-center border px-4 py-2">{{ $item->product_size }}</td>
                    <td class="text-center border px-4 py-2">{{ $item->quantity }}</td>
                    <td class="text-center border px-4 py-2">Rp {{ number_format($item->product_price, 0, ',', '.') }}
                    </td>
                    <td class="text-center border px-4 py-2">Rp {{ number_format($item->product_price * $item->quantity,
                        0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @else
        <p>No items found in this transaction.</p>
        @endif

        <div class="invoice-info">
            <p>Total Price: <strong>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</strong></p>
            <p>Customer Payment: <strong>Rp {{ number_format($transaction->user_money, 0, ',', '.') }}</strong></p>
            <p>Change: <strong>Rp {{ number_format($transaction->user_money - $transaction->total_price, 0, ',', '.')
                    }}</strong></p>
        </div>

        <div class="invoice-footer">
            <p>Thank you for shopping with us!</p>
            <p>For support, contact <strong>firman@gmail.com</strong></p>
        </div>

        <div class="text-center no-print mt-4">
            <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded-md">Print Invoice</button>
            <a href="{{ route('home') }}" class="bg-gray-500 text-white px-6 py-2 rounded-md ml-4">Back to Home</a>
        </div>
    </div>
</body>

</html>