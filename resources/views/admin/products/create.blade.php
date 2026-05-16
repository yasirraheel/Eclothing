@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="page-title" style="margin-bottom: 0;">Add Product</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back to Products</a>
    </div>

    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <div class="card-header">
            <h2 class="card-title">Product Details</h2>
        </div>
        <div style="padding: 1.5rem;">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <label for="name" class="form-label">Product Name *</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="image" class="form-label">Product Image</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="purchase_price" class="form-label">Purchase Price (RS) *</label>
                        <input type="number" step="0.01" id="purchase_price" name="purchase_price" class="form-control" value="{{ old('purchase_price') }}" required oninput="calculatePrices()">
                        @error('purchase_price') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="stock" class="form-label">Stock Quantity *</label>
                        <input type="number" id="stock" name="stock" class="form-control" value="{{ old('stock', 0) }}" required min="0">
                        @error('stock') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="discount_percentage" class="form-label">Discount Percentage (%)</label>
                    <input type="number" step="0.01" id="discount_percentage" name="discount_percentage" class="form-control" value="{{ old('discount_percentage', 0) }}" min="0" max="100">
                    <small class="text-muted">Leave at 0 if there is no discount.</small>
                    @error('discount_percentage') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="cash_margin_percentage" class="form-label">Cash Margin Percentage (%) *</label>
                        <input type="number" step="0.01" id="cash_margin_percentage" name="cash_margin_percentage" class="form-control" value="{{ old('cash_margin_percentage') }}" required oninput="calculatePrices()">
                        @error('cash_margin_percentage') <span class="text-danger">{{ $message }}</span> @enderror
                        <div class="mt-4 p-3" style="background: var(--bg-color); border-radius: 0.5rem; font-weight: 500;">
                            Calculated Cash Sale Price: RS <span id="calc_cash_price">0.00</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="emi_margin_percentage" class="form-label">EMI Margin Percentage (%) *</label>
                        <input type="number" step="0.01" id="emi_margin_percentage" name="emi_margin_percentage" class="form-control" value="{{ old('emi_margin_percentage') }}" required oninput="calculatePrices()">
                        @error('emi_margin_percentage') <span class="text-danger">{{ $message }}</span> @enderror
                        <div class="mt-4 p-3" style="background: var(--bg-color); border-radius: 0.5rem; font-weight: 500;">
                            Calculated EMI Sale Price: RS <span id="calc_emi_price">0.00</span>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem; margin-bottom: 0;">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Product</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function calculatePrices() {
            const purchasePrice = parseFloat(document.getElementById('purchase_price').value) || 0;
            const cashMargin = parseFloat(document.getElementById('cash_margin_percentage').value) || 0;
            const emiMargin = parseFloat(document.getElementById('emi_margin_percentage').value) || 0;

            const cashPrice = purchasePrice + (purchasePrice * cashMargin / 100);
            const emiPrice = purchasePrice + (purchasePrice * emiMargin / 100);

            document.getElementById('calc_cash_price').innerText = cashPrice.toFixed(2);
            document.getElementById('calc_emi_price').innerText = emiPrice.toFixed(2);
        }

        // Run calculation on load if values exist (e.g. on validation error back)
        document.addEventListener("DOMContentLoaded", calculatePrices);
    </script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#description'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
