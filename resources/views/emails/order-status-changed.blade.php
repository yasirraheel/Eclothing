<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #F85606; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 30px; }
        .status-box { background: white; padding: 20px; margin: 20px 0; border-radius: 5px; text-align: center; }
        .status-badge { display: inline-block; padding: 10px 20px; font-size: 18px; font-weight: bold; border-radius: 5px; margin: 10px 0; }
        .status-pending { background: #FFF3CD; color: #856404; }
        .status-processing { background: #CCE5FF; color: #004085; }
        .status-shipped { background: #D1ECF1; color: #0C5460; }
        .status-delivered { background: #D4EDDA; color: #155724; }
        .status-cancelled { background: #F8D7DA; color: #721C24; }
        .order-details { background: white; padding: 20px; margin: 20px 0; border-radius: 5px; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Status Update</h1>
        </div>
        
        <div class="content">
            <h2>Hello {{ $order->customer_name }},</h2>
            
            <div class="status-box">
                <p>Your order status has been updated:</p>
                <div class="status-badge status-{{ $newStatus }}">
                    {{ ucfirst($newStatus) }}
                </div>
                
                @if($newStatus == 'pending')
                    <p><strong>Your order is pending confirmation.</strong></p>
                    <p>We have received your order and will process it shortly.</p>
                @elseif($newStatus == 'processing')
                    <p><strong>Your order is being processed!</strong></p>
                    <p>We are preparing your items for shipment.</p>
                @elseif($newStatus == 'shipped')
                    <p><strong>Your order has been shipped!</strong></p>
                    <p>Your package is on its way to you.</p>
                @elseif($newStatus == 'delivered')
                    <p><strong>Your order has been delivered!</strong></p>
                    <p>We hope you enjoy your purchase. Thank you for shopping with us!</p>
                @elseif($newStatus == 'cancelled')
                    <p><strong>Your order has been cancelled.</strong></p>
                    <p>If you have any questions, please contact our customer support.</p>
                @endif
            </div>
            
            <div class="order-details">
                <h3>Order Information</h3>
                <p><strong>Order Number:</strong> #{{ $order->order_number }}</p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
                <p><strong>Total Amount:</strong> Rs. {{ number_format($order->total_amount, 2) }}</p>
                <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
            </div>
            
            <p>You can view your complete order details by logging into your account.</p>
        </div>
        
        <div class="footer">
            <p>Thank you for shopping with us!</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
