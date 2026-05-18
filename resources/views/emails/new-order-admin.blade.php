<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #232F3E; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 30px; }
        .alert { background: #FFF3CD; border-left: 4px solid #F85606; padding: 15px; margin: 20px 0; }
        .order-details { background: white; padding: 20px; margin: 20px 0; border-radius: 5px; }
        .order-item { border-bottom: 1px solid #eee; padding: 10px 0; }
        .total { font-size: 18px; font-weight: bold; color: #F85606; margin-top: 15px; }
        .action-button { display: inline-block; background: #F85606; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 10px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 New Order Received!</h1>
        </div>
        
        <div class="content">
            <div class="alert">
                <strong>Action Required:</strong> A new order has been placed and requires your attention.
            </div>
            
            <div class="order-details">
                <h3>Order Details</h3>
                <p><strong>Order Number:</strong> #{{ $order->order_number }}</p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
                <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                
                <h4 style="margin-top: 20px;">Customer Information:</h4>
                <p>
                    <strong>Name:</strong> {{ $order->customer_name }}<br>
                    <strong>Email:</strong> {{ $order->customer_email }}<br>
                    <strong>Phone:</strong> {{ $order->customer_phone }}<br>
                    <strong>Address:</strong> {{ $order->customer_address }}
                </p>
                
                <h4 style="margin-top: 20px;">Order Items:</h4>
                @foreach($order->items as $item)
                <div class="order-item">
                    <strong>{{ $item->product_name }}</strong><br>
                    Quantity: {{ $item->quantity }} × Rs. {{ number_format($item->price, 2) }}
                    = Rs. {{ number_format($item->quantity * $item->price, 2) }}
                </div>
                @endforeach
                
                <p class="total">Total Amount: Rs. {{ number_format($order->total_amount, 2) }}</p>
                <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ route('admin.orders.show', $order->id) }}" class="action-button">
                    View Order in Admin Panel
                </a>
            </div>
            
            <p style="margin-top: 20px;">Please process this order as soon as possible.</p>
        </div>
        
        <div class="footer">
            <p>This is an automated notification from {{ config('app.name') }}</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
