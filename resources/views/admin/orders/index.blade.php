@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="page-title" style="margin-bottom: 0;">Orders</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Row -->
    <div class="stats-grid" style="margin-bottom:1.5rem;">
        <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-list-check"></i></div>
            <div class="stat-info"><h3>Total Orders</h3><div class="value">{{ $stats['total'] }}</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="color:#f59e0b;background:rgba(245,158,11,0.1);"><i class="fa-solid fa-clock"></i></div>
            <div class="stat-info"><h3>Pending</h3><div class="value">{{ $stats['pending'] }}</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="color:#3b82f6;background:rgba(59,130,246,0.1);"><i class="fa-solid fa-gear"></i></div>
            <div class="stat-info"><h3>Processing</h3><div class="value">{{ $stats['processing'] }}</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="color:#8b5cf6;background:rgba(139,92,246,0.1);"><i class="fa-solid fa-truck"></i></div>
            <div class="stat-info"><h3>Shipped</h3><div class="value">{{ $stats['shipped'] }}</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="color:#10b981;background:rgba(16,185,129,0.1);"><i class="fa-solid fa-circle-check"></i></div>
            <div class="stat-info"><h3>Delivered</h3><div class="value">{{ $stats['delivered'] }}</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="color:#ef4444;background:rgba(239,68,68,0.1);"><i class="fa-solid fa-xmark"></i></div>
            <div class="stat-info"><h3>Cancelled</h3><div class="value">{{ $stats['cancelled'] }}</div></div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom:1.5rem;">
        <div style="padding:1rem 1.5rem;">
            <form method="GET" action="{{ route('admin.orders.index') }}" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
                <div class="form-group" style="margin:0;flex:1;min-width:180px;">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Order # or customer name..." value="{{ request('search') }}">
                </div>
                <div class="form-group" style="margin:0;min-width:150px;">
                    <label class="form-label">Order Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="pending"    {{ request('status')=='pending'    ? 'selected':'' }}>Pending</option>
                        <option value="processing" {{ request('status')=='processing' ? 'selected':'' }}>Processing</option>
                        <option value="shipped"    {{ request('status')=='shipped'    ? 'selected':'' }}>Shipped</option>
                        <option value="delivered"  {{ request('status')=='delivered'  ? 'selected':'' }}>Delivered</option>
                        <option value="cancelled"  {{ request('status')=='cancelled'  ? 'selected':'' }}>Cancelled</option>
                    </select>
                </div>
                <div class="form-group" style="margin:0;min-width:150px;">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-control">
                        <option value="">All Methods</option>
                        <option value="cod"       {{ request('payment_method')=='cod'       ? 'selected':'' }}>Cash on Delivery</option>
                        <option value="bank"      {{ request('payment_method')=='bank'      ? 'selected':'' }}>Bank Transfer</option>
                        <option value="jazzcash"  {{ request('payment_method')=='jazzcash'  ? 'selected':'' }}>JazzCash</option>
                        <option value="easypaisa" {{ request('payment_method')=='easypaisa' ? 'selected':'' }}>EasyPaisa</option>
                    </select>
                </div>
                <div style="display:flex;gap:0.5rem;">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter"></i> Filter</button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary"><i class="fa-solid fa-rotate"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">All Orders ({{ $orders->total() }})</h2>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Pay Status</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>
                            <div style="font-weight:500;">{{ $order->user->name ?? 'N/A' }}</div>
                            <div style="font-size:12px;color:var(--text-muted);">{{ $order->user->email ?? '' }}</div>
                        </td>
                        <td>{{ $order->created_at->format('d M Y') }}<br><span style="font-size:12px;color:var(--text-muted);">{{ $order->created_at->format('h:i A') }}</span></td>
                        <td>{{ $order->items->count() }} item(s)</td>
                        <td><strong style="color:var(--primary-color);">Rs. {{ number_format($order->total_amount, 0) }}</strong></td>
                        <td>
                            @if($order->payment_method == 'cod')
                                <span style="font-size:12px;"><i class="fa-solid fa-money-bill-wave" style="color:#27ae60;"></i> COD</span>
                            @elseif($order->payment_method == 'bank')
                                <span style="font-size:12px;"><i class="fa-solid fa-building-columns" style="color:#2980b9;"></i> Bank</span>
                            @elseif($order->payment_method == 'jazzcash')
                                <span style="font-size:12px;"><i class="fa-solid fa-mobile-screen" style="color:#e91e63;"></i> JazzCash</span>
                            @elseif($order->payment_method == 'easypaisa')
                                <span style="font-size:12px;"><i class="fa-solid fa-wallet" style="color:#4CAF50;"></i> EasyPaisa</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $pBadge = match($order->payment_status) {
                                    'paid'     => ['badge-success', 'Paid'],
                                    'failed'   => ['badge-danger', 'Failed'],
                                    'refunded' => ['badge-warning', 'Refunded'],
                                    default    => ['badge-secondary', 'Pending'],
                                };
                            @endphp
                            <span class="badge {{ $pBadge[0] }}">{{ $pBadge[1] }}</span>
                        </td>
                        <td>
                            @php
                                $sBadge = match($order->status) {
                                    'processing' => ['badge-primary', 'Processing'],
                                    'shipped'    => ['badge-purple', 'Shipped'],
                                    'delivered'  => ['badge-success', 'Delivered'],
                                    'cancelled'  => ['badge-danger', 'Cancelled'],
                                    default      => ['badge-warning', 'Pending'],
                                };
                            @endphp
                            <span class="badge {{ $sBadge[0] }}">{{ $sBadge[1] }}</span>
                        </td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-secondary">
                                    <i class="fa-solid fa-eye"></i> View
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align:center;padding:3rem;color:var(--text-muted);">
                            <i class="fa-solid fa-box-open" style="font-size:2rem;margin-bottom:0.5rem;display:block;"></i>
                            No orders found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
        <div style="padding:1rem 1.5rem;">
            {{ $orders->links() }}
        </div>
        @endif
    </div>

    <style>
    .badge-success { background-color: #dcfce7 !important; color: #166534 !important; }
    .badge-warning { background-color: #fef9c3 !important; color: #854d0e !important; }
    .badge-danger { background-color: #fee2e2 !important; color: #991b1b !important; }
    .badge-primary { background-color: #e0f2fe !important; color: #0369a1 !important; }
    .badge-purple { background-color: #f3e8ff !important; color: #6b21a8 !important; }
    .badge-secondary { background-color: #f3f4f6 !important; color: #4b5563 !important; }
    .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.875rem; }
    </style>
@endsection
