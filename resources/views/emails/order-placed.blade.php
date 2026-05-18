<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #F85606; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 30px; }
        .order-details { background: white; padding: 20px; margin: 20px 0; border-radius: 5px; }
        .order-item { border-bottom: 1px solid #eee; padding: 10px 0; }
        .total { font-size: 18px; font-weight: bold; color: #F85606; margin-top: 15px; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        .status-badge { display: inline-block; padding: 5px 15px; background: #FFF3CD; color: #856404; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Placed Successfully!</h1>
        </div>
        
        <div class="content">
            <h2>Thank you for your order!</h2>
            <p>Hello {{ $order->customer_name }},</p>
            <p>Your order has been placed successfully and is now being processed.</p>
            
            <div class="order-details">
                <h3>Order Details</h3>
                <p><strong>Order Number:</strong> #{{ $order->order_number }}</p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
                <p><strong>Status:</strong> <span class="status-badge">{{ ucfirst($order->status) }}</span></p>
                
                <h4 style="margin-top: 20px;">Items:</h4>
                @foreach($order->items as $item)
                <div class="order-item">
                    <strong>{{ $item->product_name }}</strong><br>
                    Quantity: {{ $item->quantity }} × Rs. {{ number_format($item->price, 2) }}
                    = Rs. {{ number_format($item->quantity * $item->price, 2) }}
                </div>
                @endforeach
                
                <p class="total">Total Amount: Rs. {{ number_format($order->total_amount, 2) }}</p>
                
                <h4 style="margin-top: 20px;">Delivery Address:</h4>
                <p>
                    {{ $order->customer_name }}<br>
                    {{ $order->customer_address }}<br>
                    Phone: {{ $order->customer_phone }}<br>
                    Email: {{ $order->customer_email }}
                </p>
                
                <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
            </div>
            
            <p>We will send you another email when your order status changes.</p>
            <p>You can track your order status anytime by logging into your account.</p>
        </div>
        
        <div class="footer">
            <p>Thank you for shopping with us!</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
