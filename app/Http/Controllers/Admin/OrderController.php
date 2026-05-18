<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusChanged;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($uq) use ($request) {
                      $uq->where('name', 'like', '%' . $request->search . '%')
                         ->orWhere('email', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        $stats = [
            'total'     => Order::count(),
            'pending'   => Order::where('status', 'pending')->count(),
            'processing'=> Order::where('status', 'processing')->count(),
            'shipped'   => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status'         => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update([
            'status'         => $request->status,
            'payment_status' => $request->payment_status,
        ]);

        // Send email notification if status changed
        if ($oldStatus !== $newStatus) {
            try {
                Mail::to($order->customer_email)->send(new OrderStatusChanged($order, $oldStatus, $newStatus));
            } catch (\Exception $e) {
                \Log::error('Failed to send order status change email: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Order #' . $order->order_number . ' updated successfully.');
    }
}
