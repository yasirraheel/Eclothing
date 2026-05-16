<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'purchase_price' => 'required|numeric|min:0',
            'cash_margin_percentage' => 'required|numeric|min:0',
            'emi_margin_percentage' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Calculate prices based on percentage
        $validated['cash_sale_price'] = $validated['purchase_price'] + ($validated['purchase_price'] * $validated['cash_margin_percentage'] / 100);
        $validated['emi_sale_price'] = $validated['purchase_price'] + ($validated['purchase_price'] * $validated['emi_margin_percentage'] / 100);

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'purchase_price' => 'required|numeric|min:0',
            'cash_margin_percentage' => 'required|numeric|min:0',
            'emi_margin_percentage' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Calculate prices based on percentage
        $validated['cash_sale_price'] = $validated['purchase_price'] + ($validated['purchase_price'] * $validated['cash_margin_percentage'] / 100);
        $validated['emi_sale_price'] = $validated['purchase_price'] + ($validated['purchase_price'] * $validated['emi_margin_percentage'] / 100);

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
