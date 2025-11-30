<!DOCTYPE html>
<html>
<head>
    <title>Receipt #{{ $order->id }}</title>
    <style>
        body { font-family: 'Courier New', monospace; font-size: 12px; color: #000; }
        .container { width: 100%; max-width: 300px; margin: 0 auto; padding: 5px; }
        
        .header { text-align: center; margin-bottom: 10px; }
        .header h2 { margin: 0; font-size: 16px; font-weight: bold; text-transform: uppercase; }
        .header p { margin: 2px 0; }
        
        .order-type { 
            margin: 10px auto; 
            border: 2px solid #000; 
            padding: 5px; 
            font-weight: bold; 
            font-size: 14px; 
            text-align: center;
            width: fit-content;
        }

        .divider { border-bottom: 1px dashed #000; margin: 10px 0; }
        
        .customer-info { margin-bottom: 10px; text-align: left; }
        
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; padding: 2px 0; }
        .qty { width: 25px; text-align: left; }
        .item-name { text-align: left; }
        .price { text-align: right; }
        
        .totals { margin-top: 10px; }
        .totals table tr td:first-child { text-align: left; }
        
        .footer { text-align: center; margin-top: 20px; font-size: 10px; }
        .footer p { margin: 2px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Kape Ni Asero</h2>
            <p>123 Coffee Street, Manila</p>
            <p>TIN: 000-000-000-000 VAT</p> <p>Tel: (02) 8123-4567</p>     <div class="order-type">
                {{ $order->order_type == 'take_out' ? 'TAKE OUT' : 'DINE IN' }}
            </div>

            <div style="text-align: left; margin-top: 10px;">
                <p>Date: {{ $order->created_at->format('M d, Y h:i A') }}</p>
                <p>Order #: {{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</p>
                <p>Cashier: {{ $order->user->name }}</p>
                <p>Payment Mode: {{ ucfirst($order->payment_mode ?? 'Cash') }}</p>
            </div>
        </div>

        <div class="divider"></div>

        <div class="customer-info">
            <p>Customer: ________________________</p>
            <p>Address: _________________________</p>
            <p>TIN: _____________________________</p>
        </div>

        <div class="divider"></div>

        <table>
            @foreach($order->items as $item)
            <tr>
                <td class="qty">{{ $item->quantity }}x</td>
                <td class="item-name">
                    {{ $item->product->name }}
                    @if(!empty($item->modifiers))
                        <div style="font-size: 10px; color: #555; font-style: italic;">
                            @if(isset($item->modifiers['sugar']) && $item->modifiers['sugar'] !== '100%')
                                - Sugar: {{ $item->modifiers['sugar'] }}<br>
                            @endif
                            @if(isset($item->modifiers['ice']) && $item->modifiers['ice'] !== 'Normal')
                                - Ice: {{ $item->modifiers['ice'] }}
                            @endif
                        </div>
                    @endif
                </td>
                <td class="price">{{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
        </table>

        <div class="divider"></div>

        <div class="totals">
            <table>
                <tr>
                    <td>Subtotal</td>
                    <td class="price">{{ number_format($order->subtotal, 2) }}</td>
                </tr>

                @if($order->discount_amount > 0)
                <tr>
                    <td>Less: {{ $order->discount_name }}</td>
                    <td class="price">-{{ number_format($order->discount_amount, 2) }}</td>
                </tr>
                @endif
                
                <tr><td colspan="2" style="height: 5px;"></td></tr>

                @php
                    // Basic calculation assuming inclusive VAT
                    // For official senior citizen logic, the controller should handle vat-exempt base calculation
                    $vatRate = 1.12;
                    $vatableSales = $order->total_price / $vatRate;
                    $vatAmount = $order->total_price - $vatableSales;
                @endphp

                <tr>
                    <td>Vatable Sales</td>
                    <td class="price">{{ number_format($vatableSales, 2) }}</td>
                </tr>
                <tr>
                    <td>VAT Amount (12%)</td>
                    <td class="price">{{ number_format($vatAmount, 2) }}</td>
                </tr>
                <tr>
                    <td>VAT Exempt Sales</td>
                    <td class="price">0.00</td> </tr>
                <tr>
                    <td>Zero Rated Sales</td>
                    <td class="price">0.00</td>
                </tr>

                <tr><td colspan="2" class="divider"></td></tr>

                <tr style="font-size: 16px; font-weight: bold;">
                    <td>TOTAL AMOUNT</td>
                    <td class="price">P{{ number_format($order->total_price, 2) }}</td>
                </tr>
                
                <tr><td colspan="2" style="height: 10px;"></td></tr>

                <tr>
                    <td>Cash Tendered</td>
                    <td class="price">{{ number_format($order->cash_tendered, 2) }}</td>
                </tr>
                <tr>
                    <td>Change</td>
                    <td class="price">{{ number_format($order->change_amount, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="divider"></div>

        <div class="footer">
            <p>Thank you! Please come again.</p>
            <br>
            <p>Accreditation No: 000-0000000000-000000</p>
            <p>Date Issued: 01/01/2025 | Valid Until: 01/01/2030</p>
            <p>PTU No: FP00000-000-0000000-00000</p>
            <br>
            <p style="font-weight: bold;">THIS DOCUMENT IS NOT VALID FOR CLAIM OF INPUT TAX</p>
            <p>System Developer: Ashong1</p>
        </div>
    </div>
</body>
</html>