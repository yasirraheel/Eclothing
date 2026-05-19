@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="page-title" style="margin-bottom: 0;">Products</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add Product</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card products-card">
        <div class="card-header">
            <h2 class="card-title">All Products</h2>
        </div>
        <div class="table-responsive products-table-wrap">
            <table class="table products-table">
                <thead>
                    <tr>
                        <th class="col-image"></th>
                        <th class="col-name">Product</th>
                        <th class="col-stock">Stock</th>
                        <th class="col-price">Purchase</th>
                        <th class="col-percent">Cash %</th>
                        <th class="col-price">Cash Price</th>
                        <th class="col-percent">EMI %</th>
                        <th class="col-price">EMI Price</th>
                        <th class="col-actions"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="col-image">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-img">
                                @else
                                    <div class="product-img" style="background-color: #e5e7eb; display:flex; align-items:center; justify-content:center; color: #9ca3af;">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="col-name">{{ $product->name }}</td>
                            <td class="col-stock">
                                @if($product->stock > 10)
                                    <span class="badge" style="background-color: #dcfce7; color: #166534;">{{ $product->stock }} In Stock</span>
                                @elseif($product->stock > 0)
                                    <span class="badge" style="background-color: #fef9c3; color: #854d0e;">{{ $product->stock }} Low Stock</span>
                                @else
                                    <span class="badge" style="background-color: #fee2e2; color: #991b1b;">Out of Stock</span>
                                @endif
                            </td>
                            <td class="col-price">{{ number_format($product->purchase_price, 0) }}</td>
                            <td class="col-percent">{{ $product->cash_margin_percentage }}%</td>
                            <td class="col-price"><strong>{{ number_format($product->cash_sale_price, 0) }}</strong></td>
                            <td class="col-percent">{{ $product->emi_margin_percentage }}%</td>
                            <td class="col-price"><strong>{{ number_format($product->emi_sale_price, 0) }}</strong></td>
                            <td class="col-actions">
                                <div class="product-actions">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-secondary" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 2rem;">No products found. Click "Add Product" to create one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
