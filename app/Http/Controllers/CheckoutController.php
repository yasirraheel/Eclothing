<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlaced;
use App\Mail\NewOrderAdmin;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        $setting = \App\Models\Setting::first();
        $customerCareTitles = ['Help Center', 'How to Buy', 'Returns & Refunds', 'Contact Us'];
        $customerCarePages = \App\Models\Page::whereIn('title', $customerCareTitles)->get();
        $darazPages = \App\Models\Page::whereNotIn('title', $customerCareTitles)->get();
        return view('checkout', compact('cart', 'setting', 'customerCarePages', 'darazPages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_phone' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $cart = session()->get('cart');
        if (empty($cart)) return redirect()->route('cart.index');

        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        $order = \App\Models\Order::create([
            'user_id' => auth()->id(),
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'shipping_address' => $request->shipping_address,
            'shipping_city' => $request->shipping_city,
            'shipping_phone' => $request->shipping_phone,
            'order_notes' => $request->order_notes,
        ]);

        foreach ($cart as $id => $details) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'product_name' => $details['name'],
                'price' => $details['price'],
                'quantity' => $details['quantity'],
                'subtotal' => $details['price'] * $details['quantity'],
            ]);
            
            // Deduct stock
            $product = \App\Models\Product::find($id);
            if ($product) {
                $product->stock = max(0, $product->stock - $details['quantity']);
                $product->save();
            }
        }

        session()->forget('cart');
        
        // Send email to customer
        try {
            Mail::to($order->customer_email)->send(new OrderPlaced($order));
        } catch (\Exception $e) {
            \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
        }
        
        // Send email to admin
        try {
            $setting = \App\Models\Setting::first();
            if ($setting && $setting->email) {
                Mail::to($setting->email)->send(new NewOrderAdmin($order));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send admin notification email: ' . $e->getMessage());
        }
        
        return redirect()->route('orders.index')->with('success', 'Order placed successfully! Order Number: ' . $order->order_number);
    }
}
