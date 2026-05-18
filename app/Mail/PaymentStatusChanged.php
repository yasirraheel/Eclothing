<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $oldPaymentStatus;
    public $newPaymentStatus;

    public function __construct(Order $order, $oldPaymentStatus, $newPaymentStatus)
    {
        $this->order = $order;
        $this->oldPaymentStatus = $oldPaymentStatus;
        $this->newPaymentStatus = $newPaymentStatus;
    }

    public function build()
    {
        $statusMessages = [
            'pending' => 'Payment is pending',
            'paid' => 'Payment has been received',
            'failed' => 'Payment failed',
            'refunded' => 'Payment has been refunded'
        ];

        $subject = 'Payment Status Update - ' . ($statusMessages[$this->newPaymentStatus] ?? 'Status Changed');

        return $this->subject($subject . ' - #' . $this->order->order_number)
                    ->view('emails.payment-status-changed');
    }
}
