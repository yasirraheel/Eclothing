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
        .status-paid { background: #D4EDDA; color: #155724; }
        .status-failed { background: #F8D7DA; color: #721C24; }
        .status-refunded { background: #D1ECF1; color: #0C5460; }
        .order-details { background: white; padding: 20px; margin: 20px 0; border-radius: 5px; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Payment Status Update</h1>
        </div>
        
        <div class="content">
            <h2>Hello {{ $order->customer_name }},</h2>
            
            <div class="status-box">
                <p>Your payment status has been updated:</p>
                <div class="status-badge status-{{ $newPaymentStatus }}">
                    {{ ucfirst($newPaymentStatus) }}
                </div>
                
                @if($newPaymentStatus == 'pending')
                    <p><strong>Payment is pending.</strong></p>
                    <p>We are waiting for your payment confirmation.</p>
                @elseif($newPaymentStatus == 'paid')
                    <p><strong>Payment received successfully!</strong></p>
                    <p>Thank you for your payment. Your order will be processed shortly.</p>
                @elseif($newPaymentStatus == 'failed')
                    <p><strong>Payment failed.</strong></p>
                    <p>Unfortunately, your payment could not be processed. Please contact us for assistance.</p>
                @elseif($newPaymentStatus == 'refunded')
                    <p><strong>Payment has been refunded.</strong></p>
                    <p>Your payment has been refunded. It may take 5-7 business days to reflect in your account.</p>
                @endif
            </div>
            
            <div class="order-details">
                <h3>Order Information</h3>
                <p><strong>Order Number:</strong> #{{ $order->order_number }}</p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
                <p><strong>Total Amount:</strong> Rs. {{ number_format($order->total_amount, 2) }}</p>
                <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                <p><strong>Order Status:</strong> {{ ucfirst($order->status) }}</p>
            </div>
            
            <p>If you have any questions about your payment, please contact our customer support.</p>
        </div>
        
        <div class="footer">
            <p>Thank you for shopping with us!</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
