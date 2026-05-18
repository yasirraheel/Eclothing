<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $oldStatus;
    public $newStatus;

    public function __construct(Order $order, $oldStatus, $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function build()
    {
        $statusMessages = [
            'pending' => 'Your order is pending confirmation',
            'processing' => 'Your order is being processed',
            'shipped' => 'Your order has been shipped',
            'delivered' => 'Your order has been delivered',
            'cancelled' => 'Your order has been cancelled'
        ];

        $subject = 'Order Status Update - ' . ($statusMessages[$this->newStatus] ?? 'Status Changed');

        return $this->subject($subject . ' - #' . $this->order->order_number)
                    ->view('emails.order-status-changed');
    }
}
