<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $setting = \App\Models\Setting::first();
        $customerCareTitles = ['Help Center', 'How to Buy', 'Returns & Refunds', 'Contact Us'];
        $customerCarePages = \App\Models\Page::whereIn('title', $customerCareTitles)->get();
        $darazPages = \App\Models\Page::whereNotIn('title', $customerCareTitles)->get();
        return view('cart', compact('cart', 'setting', 'customerCarePages', 'darazPages'));
    }

    public function count()
    {
        $cart = session()->get('cart', []);
        $cartCount = 0;
        foreach($cart as $item) {
            $cartCount += $item['quantity'] ?? 0;
        }
        return response()->json(['count' => $cartCount]);
    }

    public function add(Request $request, $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $cart = session()->get('cart', []);

        $qty = $request->input('quantity', 1);

        if(isset($cart[$id])) {
            $cart[$id]['quantity'] += $qty;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => $qty,
                "price" => $product->discount_percentage > 0 ? $product->discounted_price : $product->cash_sale_price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        
        if ($request->has('checkout')) {
            return redirect()->route('checkout.index');
        }
        
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Cart updated successfully');
        }
    }

    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return redirect()->back()->with('success', 'Product removed successfully');
        }
    }
}
