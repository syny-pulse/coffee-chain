<nav class="nav">
    <ul>
        <li><a href="{{ route('farmers.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="{{ route('farmers.harvests.index') }}"><i class="fas fa-seedling"></i> Harvests</a></li>
        <li><a href="{{ route('farmers.inventory.index') }}"><i class="fas fa-warehouse"></i> Inventory</a></li>
        <li><a href="{{ route('farmers.orders.index') }}"><i class="fas fa-shopping-cart"></i> Orders</a></li>
        <li><a href="{{ route('farmers.communication.index') }}"><i class="fas fa-comments"></i> Communication</a></li>
        <li><a href="{{ route('farmers.financials.index') }}"><i class="fas fa-money-bill"></i> Financials</a></li>
        <li><a href="{{ route('farmers.analytics.reports') }}"><i class="fas fa-chart-line"></i> Reports</a></li>
    </ul>
</nav>