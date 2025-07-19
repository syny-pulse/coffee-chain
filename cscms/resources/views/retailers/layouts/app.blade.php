<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Shop - @yield('title', 'Dashboard')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/messages.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary: #2D3748;
            --primary-light: #4A5568;
            --accent: #8B7355;
            --accent-light: #A68B5B;
            --success: #48BB78;
            --warning: #ED8936;
            --danger: #F56565;
            --info: #4299E1;
            --bg-primary: #FFFFFF;
            --bg-secondary: #F7FAFC;
            --bg-tertiary: #EDF2F7;
            --text-primary: #2D3748;
            --text-secondary: #4A5568;
            --text-muted: #718096;
            --border: #E2E8F0;
            --border-light: #F1F5F9;
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --radius-sm: 6px;
            --radius: 8px;
            --radius-lg: 12px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; line-height: 1.6; color: var(--text-primary); background: var(--bg-secondary); min-height: 100vh; }
        .sidebar { width: 260px; background: var(--bg-primary); border-right: 1px solid var(--border); padding: 2rem 0; display: flex; flex-direction: column; }
        .logo { padding: 0 2rem; margin-bottom: 3rem; display: flex; align-items: center; gap: 0.75rem; }
        .logo-icon { width: 32px; height: 32px; background: var(--accent); border-radius: var(--radius); display: flex; align-items: center; justify-content: center; color: white; font-size: 1rem; }
        .logo-text { font-size: 1.25rem; font-weight: 600; color: var(--text-primary); }
        .nav-menu { flex: 1; padding: 0 1rem; }
        .nav-section { margin-bottom: 2rem; }
        .nav-section-title { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); padding: 0 1rem; margin-bottom: 0.5rem; }
        .nav-item { margin-bottom: 0.25rem; }
        .nav-link { display: flex; align-items: center; padding: 0.75rem 1rem; color: var(--text-secondary); text-decoration: none; border-radius: var(--radius); transition: all 0.2s ease; font-weight: 500; font-size: 0.875rem; }
        .nav-link:hover { background: var(--bg-secondary); color: var(--text-primary); }
        .nav-link.active { background: var(--accent); color: white; }
        .nav-link .icon { margin-right: 0.75rem; font-size: 1rem; width: 20px; text-align: center; }
        .user-section { padding: 1rem; border-top: 1px solid var(--border); margin-top: auto; }
        .user-profile { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: var(--bg-secondary); border-radius: var(--radius); }
        .user-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--accent); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.875rem; }
        .user-info { flex: 1; }
        .user-name { font-weight: 600; font-size: 0.875rem; color: var(--text-primary); }
        .user-role { font-size: 0.75rem; color: var(--text-muted); }
        .main-content { flex: 1; padding: 2rem; overflow-y: auto; }
        .page-header { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem; }
        .page-title { font-size: 1.875rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem; }
        .page-subtitle { color: var(--text-secondary); font-size: 1rem; width: 100%; }
        .alert { padding: 1rem; border-radius: var(--radius); margin-bottom: 1.5rem; font-size: 0.875rem; }
        .alert-success { background: rgba(72, 187, 120, 0.1); border: 1px solid var(--success); color: var(--success); }
        .alert-danger { background: rgba(245, 101, 101, 0.1); border: 1px solid var(--danger); color: var(--danger); }
        .btn { display: inline-block; padding: 0.75rem 1.5rem; background: var(--accent); color: white; border-radius: var(--radius); text-decoration: none; font-weight: 500; transition: all 0.2s ease; font-size: 0.875rem; border: none; cursor: pointer; white-space: nowrap; }
        .btn:hover { background: var(--accent-light); box-shadow: var(--shadow); }
        .btn-primary { background: var(--accent); }
        .btn-primary:hover { background: var(--accent-light); }
        .btn-secondary { background: var(--primary-light); }
        .btn-secondary:hover { background: var(--primary); }
        .btn-info { background: var(--info); }
        .btn-info:hover { background: #3182ce; }
        .btn-danger { background: var(--danger); }
        .btn-danger:hover { background: #e53e3e; }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text-secondary); }
        .btn-outline:hover { background: var(--bg-secondary); color: var(--text-primary); }
        .btn-sm { padding: 0.25rem 0.75rem; font-size: 0.75rem; }
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text-primary); font-size: 0.875rem; }
        .form-control { width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: var(--radius); font-family: inherit; font-size: 0.875rem; transition: all 0.2s ease; }
        .form-control:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(139, 115, 85, 0.1); }
        textarea.form-control { min-height: 100px; resize: vertical; }
        .form-row { display: flex; flex-wrap: wrap; margin-right: -0.5rem; margin-left: -0.5rem; }
        .form-group.col-md-3 { padding: 0 0.5rem; flex: 0 0 25%; max-width: 25%; }
        .d-flex { display: flex; }
        .align-items-end { align-items: flex-end; }
        .mt-2 { margin-top: 0.5rem; }
        .mt-3 { margin-top: 1rem; }
        hr { border: 0; border-top: 1px solid var(--border); margin: 1.5rem 0; }
        .table { width: 100%; border-collapse: collapse; background: var(--bg-primary); border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); border: 1px solid var(--border); margin-bottom: 1.5rem; }
        .table th, .table td { padding: 1rem; text-align: left; border-bottom: 1px solid var(--border); }
        .table th { background: var(--bg-tertiary); color: var(--text-primary); font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; }
        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr:hover { background: var(--bg-secondary); }
        .inventory-summary { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .inventory-card { background: var(--bg-primary); border-radius: var(--radius); padding: 1rem; border: 1px solid var(--border); box-shadow: var(--shadow); text-align: center; }
        .inventory-card h3 { font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; }
        .inventory-card p { font-size: 1rem; color: var(--text-secondary); }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: var(--bg-primary); border-radius: var(--radius); padding: 1rem; border: 1px solid var(--border); transition: all 0.2s ease; min-width: 0; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow); }
        .stat-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem; }
        .stat-icon { width: 32px; height: 32px; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; font-size: 0.875rem; color: white; background: var(--accent); }
        .stat-trend { display: flex; align-items: center; gap: 0.25rem; font-size: 0.625rem; font-weight: 600; padding: 0.125rem 0.375rem; border-radius: 12px; }
        .stat-trend.positive { background: rgba(72, 187, 120, 0.1); color: var(--success); }
        .stat-trend.negative { background: rgba(245, 101, 101, 0.1); color: var(--danger); }
        .stat-value { font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.25rem; line-height: 1.2; }
        .stat-label { color: var(--text-secondary); font-weight: 500; font-size: 0.75rem; line-height: 1.3; }
        .card { background: var(--bg-primary); border-radius: var(--radius); border: 1px solid var(--border); margin-bottom: 2rem; box-shadow: var(--shadow); }
        .card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
        .card-title { font-size: 1.25rem; font-weight: 600; color: var(--text-primary); }
        .card-actions { display: flex; gap: 0.5rem; }
        .messages-container { padding: 1.5rem; }
        .message-item { padding: 1.25rem; border-bottom: 1px solid var(--border); transition: all 0.2s ease; cursor: pointer; }
        .message-item:last-child { border-bottom: none; }
        .message-item:hover { background: var(--bg-secondary); }
        .message-item.unread { background: rgba(66, 153, 225, 0.05); }
        .message-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; }
        .message-sender { display: flex; align-items: center; gap: 0.5rem; font-weight: 600; color: var(--text-primary); }
        .unread-badge { width: 8px; height: 8px; background: var(--info); border-radius: 50%; }
        .message-meta { display: flex; gap: 1rem; font-size: 0.75rem; color: var(--text-muted); }
        .message-type { padding: 0.25rem 0.5rem; border-radius: 12px; font-weight: 600; text-transform: uppercase; font-size: 0.625rem; letter-spacing: 0.05em; background: var(--bg-tertiary); }
        .message-subject { margin-bottom: 0.5rem; font-weight: 500; color: var(--text-primary); }
        .message-content { color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5; margin-bottom: 1rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .message-actions { display: flex; gap: 0.5rem; }
        .form-actions { display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1rem; }
        .empty-state { padding: 3rem 2rem; text-align: center; border: 1px dashed var(--border); border-radius: var(--radius); background: var(--bg-secondary); }
        .empty-state-icon { font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem; }
        .empty-state h3 { font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem; }
        .empty-state p { color: var(--text-muted); margin-bottom: 1.5rem; }
        .status-badge { padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }
        .status-pending { background: var(--warning); color: white; }
        .status-delivered { background: var(--success); color: white; }
        .status-cancelled { background: var(--danger); color: white; }
        .status-processing { background: var(--info); color: white; }
        .quick-actions { margin-bottom: 2rem; }
        .section-title { font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1rem; }
        .actions-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.5rem; }
        .action-card { background: var(--bg-primary); border-radius: var(--radius); box-shadow: var(--shadow); padding: 1.5rem; text-align: center; text-decoration: none; color: var(--text-primary); transition: box-shadow 0.2s, transform 0.2s; display: flex; flex-direction: column; align-items: center; }
        .action-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-2px) scale(1.03); color: var(--accent); }
        .action-icon { font-size: 2rem; margin-bottom: 0.75rem; color: var(--accent); }
        .action-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 0.25rem; }
        .action-desc { color: var(--text-muted); font-size: 0.95rem; }
        .recent-activity { margin-bottom: 2rem; }
        .activity-list { display: flex; flex-direction: column; gap: 1.25rem; }
        .activity-item { display: flex; align-items: flex-start; gap: 1rem; background: var(--bg-primary); border-radius: var(--radius); box-shadow: var(--shadow-sm); padding: 1rem 1.25rem; }
        .activity-icon { font-size: 1.5rem; color: var(--accent); margin-top: 0.2rem; }
        .activity-content { flex: 1; }
        .activity-title { font-weight: 600; color: var(--text-primary); font-size: 1rem; }
        .activity-time { color: var(--text-muted); font-size: 0.85rem; }
        /* Modal fixes */
        .modal { display: none; position: fixed; z-index: 2147483647 !important; left: 0; top: 0; width: 100vw; height: 100vh; overflow: auto; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; }
        .modal[style*="display: flex"] { display: flex !important; }
        .modal-content { background: white; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); max-width: 500px; width: 90%; max-height: 80vh; overflow: auto; position: relative; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem 1rem 1.5rem; border-bottom: 1px solid var(--border); }
        .modal-close { background: none; border: none; font-size: 1.25rem; color: var(--text-muted); cursor: pointer; }
        .modal-close:hover { color: var(--danger); }
        /* Responsive Design */
        @media (max-width: 1200px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .form-group.col-md-3 { flex: 0 0 50%; max-width: 50%; }
        }
        @media (max-width: 768px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; padding: 1rem 0; order: 2; }
            .main-content { order: 1; padding: 1rem; }
            .page-header { flex-direction: column; align-items: flex-start; }
            .table th, .table td { padding: 0.75rem; }
            .stats-grid { grid-template-columns: 1fr; }
            .message-header { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
            .message-actions { flex-direction: column; }
            .form-group.col-md-3 { flex: 0 0 100%; max-width: 100%; }
        }
        /* Scrollbar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: var(--bg-tertiary); }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }
    </style>
</head>
<body>
    @include('partials.notifications')
    <div style="display: flex; min-height: 100vh;">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-mug-hot"></i>
                </div>
                <div class="logo-text">Coffee Shop</div>
            </div>
            <nav class="nav-menu">
                <div class="nav-section">
                    <div class="nav-section-title">Main</div>
                    <div class="nav-item">
                        <a href="{{ route('retailer.dashboard') }}" class="nav-link @if(request()->routeIs('retailer.dashboard')) active @endif">
                            <span class="icon"><i class="fas fa-grid-2"></i></span>
                            Dashboard
                        </a>
                    </div>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Shop Management</div>
                    <div class="nav-item">
                        <a href="{{ route('retailer.sales.index') }}" class="nav-link @if(request()->routeIs('retailer.sales.*')) active @endif">
                            <span class="icon"><i class="fas fa-chart-bar"></i></span>
                            Sales Data
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('retailer.product_recipes.index') }}" class="nav-link @if(request()->routeIs('retailer.product_recipes.*')) active @endif">
                            <span class="icon"><i class="fas fa-utensils"></i></span>
                            Product Recipes
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('retailer.inventory.index') }}" class="nav-link @if(request()->routeIs('retailer.inventory.*')) active @endif">
                            <span class="icon"><i class="fas fa-warehouse"></i></span>
                            Inventory
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('retailer.orders.index') }}" class="nav-link @if(request()->routeIs('retailer.orders.*')) active @endif">
                            <span class="icon"><i class="fas fa-shopping-bag"></i></span>
                            Orders
                        </a>
                    </div>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Business</div>
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <span class="icon"><i class="fas fa-dollar-sign"></i></span>
                            Financials
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <span class="icon"><i class="fas fa-chart-line"></i></span>
                            Analytics
                        </a>
                    </div>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Communication</div>
                    <div class="nav-item">
                        <a href="{{ route('messages.index') }}" class="nav-link @if(request()->routeIs('messages.*')) active @endif">
                            <span class="icon"><i class="fas fa-envelope"></i></span>
                            Messages
                        </a>
                    </div>
                </div>
            </nav>
            <div class="user-section">
                <div class="user-profile">
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ auth()->user()->name ?? 'User' }}</div>
                        <div class="user-role">Retailer</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin-top: 1.5rem;">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="width: 100%;"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </form>
            </div>
        </div>
        <!-- Main Content -->
        <div class="main-content" style="width: 100%;">
            @yield('page-actions')
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Shared modal and dynamic form JS can be placed here if needed for all pages
    </script>
    <script>
// Modal close on click outside and close button
window.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.modal').forEach(function(modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) modal.style.display = 'none';
        });
        modal.querySelectorAll('.modal-close').forEach(function(btn) {
            btn.addEventListener('click', function() {
                modal.style.display = 'none';
            });
        });
    });
});
</script>
</body>
</html> 