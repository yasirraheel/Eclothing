@extends('layouts.admin')

@section('content')
@php
    $statusMap = [
        'pending' => ['Pending', 'status-pending', 'fa-clock'],
        'processing' => ['Processing', 'status-processing', 'fa-gear'],
        'shipped' => ['Shipped', 'status-shipped', 'fa-truck'],
        'delivered' => ['Delivered', 'status-delivered', 'fa-circle-check'],
        'cancelled' => ['Cancelled', 'status-cancelled', 'fa-ban'],
    ];

    $paymentMap = [
        'pending' => ['Pending', 'status-pending', 'fa-clock'],
        'paid' => ['Paid', 'status-delivered', 'fa-circle-check'],
        'failed' => ['Failed', 'status-cancelled', 'fa-triangle-exclamation'],
        'refunded' => ['Refunded', 'status-processing', 'fa-rotate-left'],
    ];

    $status = $statusMap[$order->status] ?? ['Unknown', 'status-pending', 'fa-question'];
    $payment = $paymentMap[$order->payment_status] ?? ['Unknown', 'status-pending', 'fa-question'];
@endphp

<div class="order-show-page">
    <div class="order-hero card">
        <div class="order-hero-main">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary order-back-link">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Orders
            </a>

            <div>
                <div class="order-kicker">Order Details</div>
                <h1 class="page-title order-title" style="margin-bottom:0.35rem;">{{ $order->order_number }}</h1>
                <p class="order-subtitle">Placed on {{ $order->created_at->format('d M Y, h:i A') }} by {{ $order->user->name ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="order-hero-badges">
            <span class="status-badge {{ $status[1] }}"><i class="fa-solid {{ $status[2] }}"></i>{{ $status[0] }}</span>
            <span class="status-badge {{ $payment[1] }}"><i class="fa-solid {{ $payment[2] }}"></i>{{ $payment[0] }}</span>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="order-grid">
        <div class="order-main-column">
            <div class="card order-panel">
                <div class="card-header"><h2 class="card-title"><i class="fa-solid fa-box"></i> Order Items</h2></div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Unit Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="order-product-cell">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product_name }}" class="order-product-image">
                                        @else
                                            <div class="order-product-image order-product-placeholder"><i class="fa-solid fa-box"></i></div>
                                        @endif
                                        <div>
                                            <div class="order-product-name">{{ $item->product_name }}</div>
                                            <div class="order-product-meta">SKU item • Qty {{ $item->quantity }}</div>
                                        </div>
                                    </td>
                                    <td>Rs. {{ number_format($item->price, 0) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td><strong>Rs. {{ number_format($item->subtotal, 0) }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="order-total-row">
                                <td colspan="3" class="order-total-label">Grand Total</td>
                                <td class="order-total-value">Rs. {{ number_format($order->total_amount, 0) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="card order-panel">
                <div class="card-header"><h2 class="card-title"><i class="fa-solid fa-location-dot"></i> Shipping Information</h2></div>
                <div class="order-panel-body">
                    <div class="order-info-grid">
                        <div class="info-box">
                            <span class="info-label">Customer Name</span>
                            <strong class="info-value">{{ $order->user->name ?? 'N/A' }}</strong>
                        </div>
                        <div class="info-box">
                            <span class="info-label">Phone</span>
                            <strong class="info-value">{{ $order->shipping_phone }}</strong>
                        </div>
                        <div class="info-box">
                            <span class="info-label">City</span>
                            <strong class="info-value">{{ $order->shipping_city }}</strong>
                        </div>
                        <div class="info-box">
                            <span class="info-label">Email</span>
                            <strong class="info-value">{{ $order->user->email ?? 'N/A' }}</strong>
                        </div>
                    </div>

                    <div class="address-box">
                        <span class="info-label">Detailed Address</span>
                        <p class="address-text">{{ $order->shipping_address }}</p>
                    </div>

                    @if($order->order_notes)
                        <div class="notes-box">
                            <span class="info-label notes-label">Order Notes</span>
                            <p class="notes-text">{{ $order->order_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="order-sidebar">
            <div class="card order-panel order-summary-card">
                <div class="card-header"><h2 class="card-title"><i class="fa-solid fa-receipt"></i> Order Summary</h2></div>
                <div class="order-panel-body">
                    <div class="summary-total">
                        <span class="summary-label">Total Amount</span>
                        <strong class="summary-amount">Rs. {{ number_format($order->total_amount, 0) }}</strong>
                    </div>

                    <div class="summary-list">
                        <div class="summary-row">
                            <span>Order Number</span>
                            <strong>{{ $order->order_number }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>Order Date</span>
                            <strong>{{ $order->created_at->format('d M Y') }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>Payment Method</span>
                            <strong>
                                @if($order->payment_method == 'cod') Cash on Delivery
                                @elseif($order->payment_method == 'bank') Bank Transfer
                                @elseif($order->payment_method == 'jazzcash') JazzCash
                                @elseif($order->payment_method == 'easypaisa') EasyPaisa
                                @else {{ ucfirst($order->payment_method) }}
                                @endif
                            </strong>
                        </div>
                        <div class="summary-row">
                            <span>Total Items</span>
                            <strong>{{ $order->items->count() }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card order-panel sticky-panel">
                <div class="card-header"><h2 class="card-title"><i class="fa-solid fa-sliders"></i> Update Status</h2></div>
                <div class="order-panel-body">
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="order-status-form">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label class="form-label">Order Status</label>
                            <select name="status" class="form-control">
                                <option value="pending" {{ $order->status=='pending' ? 'selected':'' }}>Pending</option>
                                <option value="processing" {{ $order->status=='processing' ? 'selected':'' }}>Processing</option>
                                <option value="shipped" {{ $order->status=='shipped' ? 'selected':'' }}>Shipped</option>
                                <option value="delivered" {{ $order->status=='delivered' ? 'selected':'' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status=='cancelled' ? 'selected':'' }}>Cancelled</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-control">
                                <option value="pending" {{ $order->payment_status=='pending' ? 'selected':'' }}>Pending</option>
                                <option value="paid" {{ $order->payment_status=='paid' ? 'selected':'' }}>Paid</option>
                                <option value="failed" {{ $order->payment_status=='failed' ? 'selected':'' }}>Failed</option>
                                <option value="refunded" {{ $order->payment_status=='refunded' ? 'selected':'' }}>Refunded</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary order-update-btn">
                            <i class="fa-solid fa-floppy-disk"></i> Update Order
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .order-show-page {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .order-hero {
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .order-hero-main {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .order-kicker {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: var(--text-muted);
        margin-bottom: 0.3rem;
        font-weight: 700;
    }

    .order-title {
        font-size: 1.7rem;
    }

    .order-subtitle {
        color: var(--text-muted);
        font-size: 0.95rem;
    }

    .order-hero-badges {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .order-back-link {
        white-space: nowrap;
        align-self: flex-start;
        background: #fff;
        color: var(--primary-color);
        border: 1px solid rgba(245, 114, 36, 0.28);
        box-shadow: 0 4px 12px rgba(245, 114, 36, 0.08);
        border-radius: 999px;
        padding: 0.78rem 1.15rem;
        font-weight: 700;
    }

    .order-back-link:hover {
        background: rgba(245, 114, 36, 0.06);
        color: var(--primary-color);
        border-color: rgba(245, 114, 36, 0.4);
        transform: translateY(-1px);
    }

    .order-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 360px;
        gap: 1.5rem;
        align-items: start;
    }

    .order-main-column,
    .order-sidebar {
        display: grid;
        gap: 1.5rem;
    }

    .order-panel-body {
        padding: 1.5rem;
    }

    .order-product-cell {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        white-space: normal;
        min-width: 240px;
    }

    .order-product-image {
        width: 54px;
        height: 54px;
        border-radius: 0.75rem;
        object-fit: cover;
        flex-shrink: 0;
        border: 1px solid var(--border-color);
        background: #fff;
    }

    .order-product-placeholder {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        background: rgba(245, 114, 36, 0.08);
    }

    .order-product-name {
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 0.2rem;
    }

    .order-product-meta {
        font-size: 0.82rem;
        color: var(--text-muted);
    }

    .order-total-row {
        background: #fff8f2;
    }

    .order-total-label {
        text-align: right;
        font-weight: 700;
        color: var(--text-main);
    }

    .order-total-value {
        color: var(--primary-color);
        font-size: 1.2rem;
        font-weight: 800;
    }

    .order-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .info-box,
    .address-box,
    .notes-box {
        border: 1px solid var(--border-color);
        border-radius: 0.9rem;
        background: #fff;
        padding: 1rem;
    }

    .info-label {
        display: block;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
        margin-bottom: 0.45rem;
        font-weight: 700;
    }

    .info-value,
    .address-text,
    .notes-text {
        color: var(--text-main);
        line-height: 1.6;
        word-break: break-word;
    }

    .address-text,
    .notes-text {
        font-weight: 500;
    }

    .notes-box {
        margin-top: 1rem;
        background: linear-gradient(180deg, #fff8eb 0%, #fffdf7 100%);
        border-color: #f3d39c;
    }

    .notes-label {
        color: #9a5a00;
    }

    .summary-total {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        padding: 1rem 1.1rem;
        border-radius: 0.9rem;
        background: linear-gradient(135deg, rgba(245, 114, 36, 0.12), rgba(245, 114, 36, 0.02));
        margin-bottom: 1rem;
    }

    .summary-label {
        font-size: 0.85rem;
        color: var(--text-muted);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .summary-amount {
        color: var(--primary-color);
        font-size: 1.4rem;
        font-weight: 800;
        text-align: right;
    }

    .summary-list {
        display: grid;
        gap: 0.85rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        align-items: flex-start;
        color: var(--text-main);
        font-size: 0.95rem;
    }

    .summary-row span {
        color: var(--text-muted);
    }

    .summary-row strong {
        text-align: right;
        word-break: break-word;
    }

    .order-status-form {
        display: grid;
        gap: 1rem;
    }

    .order-update-btn {
        width: 100%;
        justify-content: center;
        border-radius: 999px;
        padding: 0.9rem 1.2rem;
        font-weight: 700;
        box-shadow: 0 10px 18px rgba(245, 114, 36, 0.18);
        background: linear-gradient(135deg, var(--primary-color), #ff8a3d);
    }

    .order-update-btn:hover {
        background: linear-gradient(135deg, var(--primary-hover), #f57224);
        box-shadow: 0 12px 20px rgba(245, 114, 36, 0.24);
        transform: translateY(-1px);
    }

    .order-update-btn i,
    .order-back-link i {
        font-size: 0.95em;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.65rem 0.95rem;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 700;
        border: 1px solid transparent;
    }

    .status-pending {
        background: #fff7e6;
        color: #a16207;
        border-color: #fcd34d;
    }

    .status-processing {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #bfdbfe;
    }

    .status-shipped {
        background: #f3e8ff;
        color: #7e22ce;
        border-color: #d8b4fe;
    }

    .status-delivered {
        background: #ecfdf3;
        color: #15803d;
        border-color: #bbf7d0;
    }

    .status-cancelled {
        background: #fef2f2;
        color: #b91c1c;
        border-color: #fecaca;
    }

    .sticky-panel {
        position: sticky;
        top: 1rem;
    }

    @media (max-width: 1240px) {
        .order-grid {
            grid-template-columns: 1fr;
        }

        .sticky-panel {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .order-info-grid {
            grid-template-columns: 1fr;
        }

        .order-hero {
            padding: 1rem;
        }

        .order-panel-body {
            padding: 1rem;
        }

        .order-product-cell {
            min-width: 220px;
        }

        .order-title {
            font-size: 1.35rem;
        }
    }
</style>
@endsection
