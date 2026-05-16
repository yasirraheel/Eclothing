<div class="sidebar-menu">
    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fa-solid fa-user"></i> Manage My Account
    </a>
    <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">
        <i class="fa-solid fa-box"></i> My Orders
    </a>
    <a href="#">
        <i class="fa-regular fa-heart"></i> My Wishlist
    </a>
    <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
        <i class="fa-solid fa-gear"></i> Settings
    </a>
</div>
