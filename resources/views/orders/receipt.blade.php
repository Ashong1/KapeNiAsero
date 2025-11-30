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
        .totals { margin-top: 15px; text-align: right; }
        .footer { text-align: center; margin-top: 20px; font-size: 10px; }
        
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
            <div style="margin-top: 10px; border: 2px solid #000; padding: 5px; font-weight: bold; font-size: 16px;">
                {{ $order->order_type == 'take_out' ? 'TAKE OUT' : 'DINE IN' }}
            </div>
            <p>123 Coffee Street, Manila</p>
            <p>Date: {{ $order->created_at->format('M d, Y h:i A') }}</p>
            <p>Order #: {{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</p>
            <p>Cashier: {{ $order->user->name }}</p>
        </div>

        <div class="divider"></div>

        <table>
            @foreach($order->items as $item)
            <tr>
                <td class="qty">{{ $item->quantity }}x</td>
                <td>
                    {{ $item->product->name }}
                    @if(!empty($item->modifiers))
                        <div style="font-size: 10px; color: #666; font-style: italic;">
                            @if(isset($item->modifiers['sugar']) && $item->modifiers['sugar'] !== '100%')
                                Sugar: {{ $item->modifiers['sugar'] }}<br>
                            @endif
                            @if(isset($item->modifiers['ice']) && $item->modifiers['ice'] !== 'Normal')
                                Ice: {{ $item->modifiers['ice'] }}
                            @endif
                        </div>
                    @endif
                </td>
                <td class="price">P{{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
        </table>

        <div class="divider"></div>

        <div class="totals">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td class="price">P{{ number_format($order->subtotal, 2) }}</td>
                </tr>

                @if($order->discount_amount > 0)
                <tr>
                    <td>{{ $order->discount_name }}:</td>
                    <td class="price">-P{{ number_format($order->discount_amount, 2) }}</td>
                </tr>
                @endif

                <tr>
                    <td>VAT (12%):</td>
                    <td class="price">P{{ number_format($order->total_price - ($order->total_price / 1.12), 2) }}</td>
                </tr>

                <tr style="font-size: 16px; font-weight: bold;">
                    <td style="padding-top: 5px;">TOTAL:</td>
                    <td class="price" style="padding-top: 5px;">P{{ number_format($order->total_price, 2) }}</td>
                </tr>

                <tr><td colspan="2" style="height: 10px;"></td></tr>
                
                <tr>
                    <td>Cash:</td>
                    <td class="price">P{{ number_format($order->cash_tendered, 2) }}</td>
                </tr>
                <tr>
                    <td>Change:</td>
                    <td class="price">P{{ number_format($order->change_amount, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Thank you for your purchase!</p>
        </div>
    </div>
</body>
</html>