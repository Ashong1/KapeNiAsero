<!DOCTYPE html>
<html>
<head>
    <title>Receipt #{{ $order->id }}</title>
    <style>
        body { font-family: 'Courier New', monospace; font-size: 14px; color: #333; }
        .container { width: 100%; max-width: 400px; margin: 0 auto; padding: 10px; border: 1px dashed #ccc; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 12px; }
        .divider { border-bottom: 1px dashed #333; margin: 10px 0; }
        .item { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .item span { display: inline-block; }
        .totals { margin-top: 15px; text-align: right; }
        .footer { text-align: center; margin-top: 20px; font-size: 10px; }
        
        /* Table for better alignment in PDF */
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; }
        .qty { width: 30px; }
        .price { text-align: right; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Kape Ni Asero</h2>
            <p>123 Coffee Street, Manila</p>
            <p>Tel: (02) 8123-4567</p>
            <p>Date: {{ $order->created_at->format('M d, Y h:i A') }}</p>
            <p>Order #: {{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</p>
            <p>Cashier: {{ $order->user->name }}</p>
        </div>

        <div class="divider"></div>

        <table>
            @foreach($order->items as $item)
            <tr>
                <td class="qty">{{ $item->quantity }}x</td>
                <td>{{ $item->product->name }}</td>
                <td class="price">P{{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
        </table>

        <div class="divider"></div>

        <div class="totals">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td class="price">P{{ number_format($order->total_price / 1.12, 2) }}</td>
                </tr>
                <tr>
                    <td>VAT (12%):</td>
                    <td class="price">P{{ number_format($order->total_price - ($order->total_price / 1.12), 2) }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; font-size: 16px;">TOTAL:</td>
                    <td class="price" style="font-weight: bold; font-size: 16px;">P{{ number_format($order->total_price, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Thank you for your purchase!</p>
            <p>This serves as your official receipt.</p>
        </div>
    </div>
</body>
</html>