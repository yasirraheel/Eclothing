<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        $setting = \App\Models\Setting::first();
        $customerCareTitles = ['Help Center', 'How to Buy', 'Returns & Refunds', 'Contact Us'];
        $customerCarePages = \App\Models\Page::whereIn('title', $customerCareTitles)->get();
        $darazPages = \App\Models\Page::whereNotIn('title', $customerCareTitles)->get();

        return view('orders.index', compact('orders', 'setting', 'customerCarePages', 'darazPages'));
    }

    public function show($orderNumber)
    {
        $order = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $setting = \App\Models\Setting::first();
        $customerCareTitles = ['Help Center', 'How to Buy', 'Returns & Refunds', 'Contact Us'];
        $customerCarePages = \App\Models\Page::whereIn('title', $customerCareTitles)->get();
        $darazPages = \App\Models\Page::whereNotIn('title', $customerCareTitles)->get();

        return view('orders.show', compact('order', 'setting', 'customerCarePages', 'darazPages'));
    }
}
