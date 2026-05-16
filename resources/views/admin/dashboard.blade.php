@extends('layouts.admin')

@section('content')
    @php
        $recentRevenue = $recentOrders->sum('total_amount');
        $deliveredCount = $recentOrders->where('status', 'delivered')->count();
        $processingCount = $recentOrders->where('status', 'processing')->count();
    @endphp

    <div class="dashboard-page">
        <section class="dashboard-hero card">
            <div class="dashboard-hero-copy">
                <div class="dashboard-kicker">Eclothing Admin</div>
                <h1 class="page-title dashboard-title">Storefront performance at a glance</h1>
                <p class="dashboard-subtitle">Track products, revenue, and orders from a branded control center built to match the Eclothing look and feel.</p>

                <div class="dashboard-hero-actions">
                    <a href="{{ route('admin.products.create') }}" class="hero-action hero-action-primary">
                        <i class="fa-solid fa-plus"></i>
                        Add Product
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="hero-action hero-action-secondary">
                        <i class="fa-solid fa-cart-shopping"></i>
                        View Orders
                    </a>
                </div>

            </div>

            <div class="dashboard-hero-stats">
                <div class="hero-mini-stat hero-mini-stat-primary">
                    <span>Recent Revenue</span>
                    <strong>Rs. {{ number_format($recentRevenue, 0) }}</strong>
                    <small>Latest order activity</small>
                </div>
                <div class="hero-mini-stat">
                    <span>Delivered Orders</span>
                    <strong>{{ number_format($deliveredCount) }}</strong>
                    <small>Successful handovers</small>
                </div>
                <div class="hero-mini-stat">
                    <span>In Progress</span>
                    <strong>{{ number_format($processingCount) }}</strong>
                    <small>Active workflow queue</small>
                </div>
            </div>
        </section>
        </section>

        <section class="stats-grid dashboard-stats-grid">
            <a href="{{ route('admin.users.index') }}" class="dashboard-stat-link" title="View all users">
                <article class="stat-card dashboard-stat-card">
                    <div class="stat-icon dashboard-icon orange">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Users</h3>
                        <div class="value">{{ number_format($usersCount) }}</div>
                        <small>Registered shoppers</small>
                    </div>
                </article>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="dashboard-stat-link" title="View sales orders">
                <article class="stat-card dashboard-stat-card">
                    <div class="stat-icon dashboard-icon green">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Sales Revenue</h3>
                        <div class="value">Rs. {{ number_format($revenue, 0) }}</div>
                        <small>Sales total (non-cancelled)</small>
                    </div>
                    <div class="stat-glow"></div>
                </article>
            </a>

            <a href="{{ route('admin.products.index') }}" class="dashboard-stat-link" title="View products">
                <article class="stat-card dashboard-stat-card">
                    <div class="stat-icon dashboard-icon purple">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Purchase Cost</h3>
                        <div class="value">Rs. {{ number_format($purchaseTotal, 0) }}</div>
                        <small>Inventory purchase total</small>
                    </div>
                </article>
            </a>

            <a href="{{ route('admin.products.index') }}" class="dashboard-stat-link" title="View products">
                <article class="stat-card dashboard-stat-card">
                    <div class="stat-icon dashboard-icon teal">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Sold Products %</h3>
                        <div class="value">{{ $soldProductsRate }}%</div>
                        <small>Sold units vs products</small>
                    </div>
                </article>
            </a>

            <a href="{{ route('admin.products.index') }}" class="dashboard-stat-link" title="View products">
                <article class="stat-card dashboard-stat-card">
                    <div class="stat-icon dashboard-icon yellow">
                        <i class="fa-solid fa-box"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Products</h3>
                        <div class="value">{{ number_format($productsCount) }}</div>
                        <small>Active products in catalog</small>
                    </div>
                </article>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="dashboard-stat-link" title="View orders">
                <article class="stat-card dashboard-stat-card">
                    <div class="stat-icon dashboard-icon red">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Orders</h3>
                        <div class="value">{{ number_format($ordersCount) }}</div>
                        <small>All orders captured</small>
                    </div>
                </article>
            </a>
        </section>

        <section class="card dashboard-panel">
            <div class="card-header dashboard-panel-header">
                <div>
                    <h2 class="card-title">Recent Orders</h2>
                    <p class="panel-note">Latest activity from your clothing store.</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary dashboard-view-all">View All</a>
            </div>

            <div class="table-responsive dashboard-table-wrap">
                <table class="table dashboard-table">
                    <thead>
                        <tr>
                            <th>Order Number</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            @php
                                $statusBadge = match($order->status) {
                                    'processing' => ['badge-warning', 'Processing'],
                                    'shipped'    => ['badge-primary', 'Shipped'],
                                    'delivered'  => ['badge-success', 'Delivered'],
                                    'cancelled'  => ['badge-danger', 'Cancelled'],
                                    default      => ['badge-info', 'Pending'],
                                };
                            @endphp
                            <tr>
                                <td><strong>{{ $order->order_number }}</strong></td>
                                <td>
                                    <div class="customer-name">{{ $order->user->name ?? 'Guest' }}</div>
                                    <div class="customer-meta">{{ $order->user->email ?? 'No email' }}</div>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td><strong class="amount-highlight">Rs. {{ number_format($order->total_amount, 0) }}</strong></td>
                                <td><span class="badge {{ $statusBadge[0] }}">{{ $statusBadge[1] }}</span></td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm dashboard-view-btn">
                                        <i class="fa-solid fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">No recent orders available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <style>
        .dashboard-page {
            display: grid;
            gap: 1.5rem;
        }

        .dashboard-hero {
            padding: 1.7rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1.5rem;
            flex-wrap: wrap;
            border-radius: 1rem;
            background:
                radial-gradient(circle at top right, rgba(245, 114, 36, 0.18), transparent 32%),
                linear-gradient(135deg, #ffffff 0%, #fff7f1 48%, #fff1e7 100%);
            position: relative;
            overflow: hidden;
        }

        .dashboard-hero::after {
            content: '';
            position: absolute;
            inset: auto -80px -80px auto;
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(245, 114, 36, 0.2), transparent 68%);
            pointer-events: none;
        }

        .dashboard-hero-copy {
            position: relative;
            z-index: 1;
            flex: 1 1 460px;
            min-width: 0;
        }

        .dashboard-kicker {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.7rem;
            border-radius: 999px;
            background: rgba(245, 114, 36, 0.12);
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 0.12em;
            font-size: 0.72rem;
            font-weight: 800;
            margin-bottom: 0.8rem;
        }

        .dashboard-title {
            margin: 0 0 0.55rem;
            font-size: clamp(1.8rem, 2.4vw, 2.8rem);
            line-height: 1.05;
        }

        .dashboard-subtitle {
            margin: 0;
            color: var(--text-muted);
            max-width: 640px;
            line-height: 1.7;
        }

        .dashboard-hero-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-top: 1.2rem;
        }

        .hero-action {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.82rem 1.15rem;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 700;
            transition: transform 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease;
        }

        .hero-action:hover {
            transform: translateY(-1px);
        }

        .hero-action-primary {
            background: var(--primary-color);
            color: #fff;
            box-shadow: 0 10px 20px rgba(245, 114, 36, 0.18);
        }

        .hero-action-primary:hover {
            background: var(--primary-hover);
        }

        .hero-action-secondary {
            background: #fff;
            color: var(--text-main);
            border: 1px solid rgba(245, 114, 36, 0.18);
        }

        .dashboard-hero-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(150px, 1fr));
            gap: 0.85rem;
            align-self: stretch;
            flex: 1 1 430px;
            min-width: 0;
            position: relative;
            z-index: 1;
        }

        .hero-mini-stat {
            min-width: 0;
            padding: 1rem 1.05rem;
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(245, 114, 36, 0.1);
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .hero-mini-stat-primary {
            background: linear-gradient(180deg, rgba(245, 114, 36, 0.13), rgba(255, 255, 255, 0.95));
            border-color: rgba(245, 114, 36, 0.16);
        }

        .hero-mini-stat span {
            display: block;
            color: var(--text-muted);
            font-size: 0.82rem;
            margin-bottom: 0.3rem;
        }

        .hero-mini-stat strong {
            font-size: 1.08rem;
            line-height: 1.2;
        }

        .hero-mini-stat small {
            display: block;
            margin-top: 0.28rem;
            color: var(--text-muted);
            font-size: 0.76rem;
        }

        .dashboard-stats-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
        }

        .dashboard-stat-link {
            display: block;
            text-decoration: none;
            color: inherit;
            border-radius: 1rem;
        }

        .dashboard-stat-link .dashboard-stat-card {
            height: 100%;
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }

        .dashboard-stat-link:hover .dashboard-stat-card {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.08);
            border-color: rgba(245, 114, 36, 0.25);
        }

        .dashboard-stat-link:focus-visible {
            outline: 2px solid var(--primary-color);
            outline-offset: 3px;
        }

        .dashboard-stat-card {
            min-height: 148px;
            border-radius: 1rem;
            padding: 1.2rem;
            gap: 0.95rem;
            align-items: center;
        }

        .dashboard-icon {
            width: 64px;
            height: 64px;
            border-radius: 1rem;
            display: grid;
            place-items: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .dashboard-icon.orange { color: var(--primary-color); background: rgba(245, 114, 36, 0.12); }
        .dashboard-icon.green { color: #059669; background: rgba(16, 185, 129, 0.12); }
        .dashboard-icon.yellow { color: #d97706; background: rgba(245, 158, 11, 0.12); }
        .dashboard-icon.red { color: #ef4444; background: rgba(239, 68, 68, 0.12); }
        .dashboard-icon.purple { color: #7c3aed; background: rgba(124, 58, 237, 0.12); }
        .dashboard-icon.teal { color: #0f766e; background: rgba(13, 148, 136, 0.12); }

        .stat-info {
            flex: 1;
            min-width: 0;
        }

        .stat-info h3 {
            margin-bottom: 0.35rem;
            font-size: 0.92rem;
        }

        .stat-info .value {
            font-size: clamp(1.35rem, 1.9vw, 2rem);
            font-weight: 800;
            line-height: 1.1;
        }

        .stat-info small {
            display: block;
            margin-top: 0.35rem;
            color: var(--text-muted);
        }

        .growth-text {
            color: #059669 !important;
            font-weight: 600;
        }

        .dashboard-panel {
            border-radius: 1rem;
            overflow: hidden;
        }

        .dashboard-panel-header {
            align-items: center;
        }

        .panel-note {
            margin: 0.25rem 0 0;
            color: var(--text-muted);
            font-size: 0.92rem;
        }

        .dashboard-view-all {
            border-radius: 999px;
            background: #6b7280;
            color: #fff;
            border: 0;
        }

        .dashboard-view-all:hover {
            background: #4b5563;
        }

        .dashboard-table-wrap {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .dashboard-table {
            width: 100%;
            min-width: 720px;
        }

        .dashboard-table thead th {
            background: #fafafa;
        }

        .customer-name {
            font-weight: 700;
        }

        .customer-meta {
            margin-top: 0.15rem;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .amount-highlight {
            color: var(--primary-color);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.35rem 0.8rem;
            border-radius: 999px;
            font-size: 0.76rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-primary { background: rgba(245, 114, 36, 0.12); color: var(--primary-color); }
        .badge-success { background: rgba(16, 185, 129, 0.14); color: #059669; }
        .badge-danger { background: rgba(239, 68, 68, 0.12); color: #dc2626; }
        .badge-warning { background: rgba(245, 158, 11, 0.14); color: #d97706; }
        .badge-info { background: rgba(59, 130, 246, 0.12); color: #2563eb; }

        .dashboard-view-btn {
            border-radius: 999px;
            padding: 0.4rem 0.8rem;
            background: #6b7280;
            color: #fff;
            font-size: 0.8rem;
        }

        .dashboard-view-btn:hover {
            background: #4b5563;
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: var(--text-muted);
        }

        @media (max-width: 1100px) {
            .dashboard-hero-stats,
            .dashboard-stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .dashboard-hero,
            .dashboard-panel-header {
                padding: 1rem;
            }

            .dashboard-hero-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .hero-action {
                justify-content: center;
            }

            .dashboard-hero-stats,
            .dashboard-stats-grid {
                grid-template-columns: 1fr;
            }

            .hero-mini-stat {
                min-width: 0;
            }
        }
    </style>
@endsection
