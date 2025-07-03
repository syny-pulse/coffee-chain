<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Coffee Supply Chain</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/farmers.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <!-- Top Header -->
    <header class="top-header">
        <button class="sidebar-toggle-btn" aria-label="Toggle sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <a href="{{ route('farmers.dashboard') }}" class="header-brand">
            <i class="fas fa-leaf"></i>
            Farmer Dashboard
        </a>
        <div class="header-actions">
            <!-- Quick Actions Dropdown -->
            <div class="quick-actions-dropdown" tabindex="0">
                <button class="quick-actions-btn" aria-haspopup="true" aria-expanded="false" onclick="toggleQuickActionsDropdown(event)">
                    <i class="fas fa-bolt"></i> Quick Actions
                </button>
                <div class="quick-actions-menu" style="display: none;">
                    <a href="{{ route('farmers.harvests.create') }}"><i class="fas fa-plus"></i> Add Harvest</a>
                    <a href="{{ route('farmers.orders.index') }}"><i class="fas fa-clipboard-list"></i> View Orders</a>
                    <a href="{{ route('farmers.inventory.index') }}"><i class="fas fa-boxes-stacked"></i> Inventory</a>
                    <a href="{{ route('farmers.financials.pricing') }}"><i class="fas fa-tags"></i> Pricing</a>
                    <a href="{{ route('farmers.analytics.reports') }}"><i class="fas fa-chart-line"></i> Reports</a>
                    <a href="{{ route('farmers.communication.index') }}"><i class="fas fa-comments"></i> Messages</a>
                </div>
            </div>
            <!-- End Quick Actions Dropdown -->
            <div class="user-profile">
                <div class="user-avatar">{{ substr(auth()->user()->name ?? 'F', 0, 1) }}</div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name ?? 'Farmer' }}</div>
                    <div class="user-role">Coffee Farmer</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </form>
        </div>
    </header>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            
            
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Main</div>
                    <div class="nav-item">
                        <a href="{{ route('farmers.dashboard') }}" class="nav-link {{ request()->routeIs('farmers.dashboard') ? 'active' : '' }}">
                            <span class="icon"><i class="fas fa-home"></i></span>
                            Dashboard
                        </a>
                    </div>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Farm Management</div>
                    <div class="nav-item">
                        <a href="{{ route('farmers.harvests.index') }}" class="nav-link {{ request()->routeIs('farmers.harvests.*') ? 'active' : '' }}">
                            <span class="icon"><i class="fas fa-wheat-awn"></i></span>
                            Harvests
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('farmers.inventory.index') }}" class="nav-link {{ request()->routeIs('farmers.inventory.*') ? 'active' : '' }}">
                            <span class="icon"><i class="fas fa-boxes-stacked"></i></span>
                            Inventory
                        </a>
                    </div>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Business</div>
                    <div class="nav-item">
                        <a href="{{ route('farmers.orders.index') }}" class="nav-link {{ request()->routeIs('farmers.orders.*') ? 'active' : '' }}">
                            <span class="icon"><i class="fas fa-clipboard-list"></i></span>
                            Orders
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('farmers.financials.index') }}" class="nav-link {{ request()->routeIs('farmers.financials.*') ? 'active' : '' }}">
                            <span class="icon"><i class="fas fa-coins"></i></span>
                            Financials
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('farmers.analytics.reports') }}" class="nav-link {{ request()->routeIs('farmers.analytics.*') ? 'active' : '' }}">
                            <span class="icon"><i class="fas fa-chart-line"></i></span>
                            Analytics
                        </a>
                    </div>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Communication</div>
                    <div class="nav-item">
                        <a href="{{ route('farmers.communication.index') }}" class="nav-link {{ request()->routeIs('farmers.communication.*') ? 'active' : '' }}">
                            <span class="icon"><i class="fas fa-comments"></i></span>
                            Messages
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            @include('farmers.partials.messages')
            
            <!-- Page Header -->
            <div class="page-header">
                <h1>@yield('page-title')</h1>
                @hasSection('page-subtitle')
                    <p class="page-subtitle">@yield('page-subtitle')</p>
                @endif
                @hasSection('page-actions')
                    <div class="page-actions">
                        @yield('page-actions')
                    </div>
                @endif
            </div>

            <!-- Main Content Area -->
            @yield('content')
        </div>
    </div>

    @vite('resources/js/app.js')
    @stack('scripts')
</body>
</html>