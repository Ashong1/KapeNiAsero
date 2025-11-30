<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
        .summary { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Kape Ni Asero - Sales Report</h2>
        <p>Period: {{ $startDate }} to {{ $endDate }}</p>
    </div>

    <div class="summary">
        <strong>Total Sales:</strong> ₱{{ number_format($totalSales, 2) }} <br>
        <strong>Total Orders:</strong> {{ $totalOrders }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Staff</th>
                <th>Mode</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $order->user->name ?? 'N/A' }}</td>
                <td>{{ strtoupper($order->payment_mode) }}</td>
                <td>₱{{ number_format($order->total_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>