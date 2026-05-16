<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $usersCount = \App\Models\User::count();
        $productsCount = \App\Models\Product::count();
        $revenue = \App\Models\Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $purchaseTotal = \App\Models\Product::sum('purchase_price');
        $ordersCount = \App\Models\Order::count();
        $soldProductsCount = \App\Models\OrderItem::whereHas('order', function ($query) {
            $query->where('status', '!=', 'cancelled');
        })->sum('quantity');
        $soldProductsRate = $productsCount > 0
            ? round(($soldProductsCount / $productsCount) * 100)
            : 0;
        $recentOrders = \App\Models\Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('usersCount', 'productsCount', 'revenue', 'purchaseTotal', 'ordersCount', 'soldProductsCount', 'soldProductsRate', 'recentOrders'));
    }
}
