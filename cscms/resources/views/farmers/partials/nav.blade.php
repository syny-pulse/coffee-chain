<nav class="nav">
    <ul>
        <li><a href="{{ route('farmers.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="{{ route('farmers.harvests.index') }}"><i class="fas fa-seedling"></i> Harvests</a></li>
        <li><a href="{{ route('farmers.inventory.index') }}"><i class="fas fa-warehouse"></i> Inventory</a></li>
        <li>
            <a href="{{ route('farmers.orders.index') }}">
                <i class="fas fa-shopping-cart"></i> Orders
                @if(isset($pendingOrdersCount) && $pendingOrdersCount > 0)
                    <span class="notification-badge">{{ $pendingOrdersCount }}</span>
                @endif
            </a>
        </li>
        <li>
            <a href="{{ route('messages.index') }}">
                <i class="fas fa-comments"></i> Communication
                @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                    <span class="notification-badge">{{ $unreadMessagesCount }}</span>
                @endif
            </a>
        </li>
        <li><a href="{{ route('farmers.financials.index') }}"><i class="fas fa-money-bill"></i> Financials</a></li>
        <li><a href="{{ route('farmers.analytics.reports') }}"><i class="fas fa-chart-line"></i> Reports</a></li>
    </ul>
</nav>

<style>
    .notification-badge {
        background: #8B4513;
        color: white;
        border-radius: 50%;
        padding: 0.2rem 0.5rem;
        font-size: 0.7rem;
        font-weight: bold;
        margin-left: 0.5rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }
</style>