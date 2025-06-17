@extends('layouts.processor')

@section('title', 'Dashboard')

@section('content')
    <!-- Dashboard Header -->
    <div class="dashboard-header fade-in">
        <div class="dashboard-title">
            <i class="fas fa-tachometer-alt"></i>
            <div>
                <h1>Processing Dashboard</h1>
                <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">
                    Welcome back, {{ auth()->check() ? auth()->user()->name : 'Guest' }}! 
                    Here's your coffee processing overview as of {{ now()->format('h:i A T, l, F d, Y') }}
                </p>
            </div>
        </div>
        <div class="dashboard-actions">
            @auth
                <a href="{{ route('processor.employee.index') }}" class="btn btn-success">
                    <i class="fas fa-users"></i>
                    View Employees
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-success">Login to Add Employee</a>
            @endauth
            <button class="btn btn-primary" onclick="showQuickActions()">
                <i class="fas fa-plus"></i>
                Quick Actions
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid fade-in">
        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Total Employees</span>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--success) 0%, #20c997 100%);">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $employees->count() }}</div>
            <div class="stat-card-change">
                <i class="fas fa-arrow-up"></i>
                <span class="change-positive">+{{ $employees->where('created_at', '>=', now()->subMonth())->count() }} this month</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Raw Materials Stock</span>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--info) 0%, #6f42c1 100%);">
                    <i class="fas fa-seedling"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($inventory->raw_material_total ?? 0) }}kg</div>
            <div class="stat-card-change">
                <i class="fas fa-arrow-down"></i>
                <span class="change-negative">-5% from last week</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Active Retailer Orders</span>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--warning) 0%, #fd7e14 100%);">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $retailer_orders->where('status', 'active')->count() }}</div>
            <div class="stat-card-change">
                <i class="fas fa-arrow-up"></i>
                <span class="change-positive">+{{ $retailer_orders->where('created_at', '>=', now()->startOfDay())->count() }} new today</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Production Efficiency</span>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);">
                    <i class="fas fa-cogs"></i>
                </div>
            </div>
            <div class="stat-card-value">87%</div>
            <div class="stat-card-change">
                <i class="fas fa-arrow-up"></i>
                <span class="change-positive">+3% this week</span>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Grid -->
    <div class="dashboard-grid">
        <!-- Left Column -->
        <div>
            <!-- Employee Overview -->
            <div class="content-section fade-in">
                <div class="section-header">
                    <div class="section-title">
                        <i class="fas fa-users"></i>
                        <span>Employee Overview</span>
                    </div>
                    <a href="{{ route('processor.employee.index') }}" class="btn btn-primary">
                        <i class="fas fa-users"></i>
                        View Employees
                    </a>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Shift</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <i class="fas fa-user-circle" style="color: var(--coffee-medium); font-size: 1.5rem;"></i>
                                        <div>
                                            <strong>{{ $employee->name ?? 'N/A' }}</strong>
                                            <br><small style="color: var(--text-light);">ID: {{ $employee->employee_id ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $employee->position ?? 'N/A' }}</td>
                                <td>{{ $employee->shift ?? 'N/A' }}</td>
                                <td><span class="status-badge status-{{ $employee->status ?? 'inactive' }}">{{ ucfirst($employee->status ?? 'Inactive') }}</span></td>
                                <td>
                                    @if(isset($employee->employee_id))
                                    <a href="{{ route('processor.employee.edit', $employee->employee_id) }}" class="btn" style="padding: 0.25rem 0.5rem; font-size: 0.8rem; background: var(--info); color: white;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No employees found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Raw Materials Overview -->
            <div class="content-section fade-in">
                <div class="section-header">
                    <div class="section-title">
                        <i class="fas fa-seedling"></i>
                        <span>Raw Materials Inventory</span>
                    </div>
                    <a href="{{ route('processor.inventory.index') }}" class="btn btn-primary">
                        <i class="fas fa-warehouse"></i>
                        View Inventory
                    </a>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Coffee Type</th>
                                <th>Grade</th>
                                <th>Stock (kg)</th>
                                <th>Cost/kg</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($raw_materials as $material)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <i class="fas fa-coffee" style="color: var(--coffee-medium);"></i>
                                        <strong>{{ ucfirst($material->coffee_variety ?? 'N/A') }}</strong>
                                    </div>
                                </td>
                                <td>{{ $material->grade ?? 'N/A' }}</td>
                                <td>{{ number_format($material->current_quantity ?? 0) }}</td>
                                <td>UGX {{ number_format($material->unit_price ?? 0) }}</td>
                                <td><span class="status-badge status-{{ $material->stock_status ?? 'low' }}">{{ ucfirst($material->stock_status ?? 'Low') }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No raw materials found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div>
            <!-- Recent Retailer Orders -->
            <div class="content-section fade-in">
                <div class="section-header">
                    <div class="section-title">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Recent Retailer Orders</span>
                    </div>
                    <a href="{{ route('processor.order.retailer_order.index') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-cart"></i>
                        View Retailer Orders
                    </a>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($retailer_orders as $order)
                            <tr>
                                <td>{{ $order->order_id ?? 'N/A' }}</td>
                                <td>{{ $order->customer_name ?? 'N/A' }}</td>
                                <td>UGX {{ number_format($order->total_amount ?? 0) }}</td>
                                <td><span class="status-badge status-{{ $order->status ?? 'pending' }}">{{ ucfirst($order->status ?? 'Pending') }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No retailer orders found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Messages -->
            <div class="content-section fade-in">
                <div class="section-header">
                    <div class="section-title">
                        <i class="fas fa-envelope"></i>
                        <span>Recent Messages</span>
                    </div>
                    <a href="{{ route('processor.message.index') }}" class="btn btn-primary">
                        <i class="fas fa-comments"></i>
                        View Messages
                    </a>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>From</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messages as $message)
                            <tr>
                                <td>{{ $message->subject ?? 'N/A' }}</td>
                                <td>{{ $message->sender->company_name ?? 'N/A' }}</td>
                                <td>{{ isset($message->created_at) ? $message->created_at->format('M d, Y') : 'N/A' }}</td>
                                <td><span class="status-badge status-{{ $message->status ?? 'unread' }}">{{ ucfirst($message->status ?? 'Unread') }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No messages found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div id="addEmployeeModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal('addEmployeeModal')">×</span>
            <h3>Add Employee</h3>
            <form>
                <div class="form-group">
                    <label for="employeeName">Name</label>
                    <input type="text" id="employeeName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="employeeStation">Station</label>
                    <select id="employeeStation" class="form-control" required>
                        <option value="grading">Grading</option>
                        <option value="roasting">Roasting</option>
                        <option value="packaging">Packaging</option>
                        <option value="logistics">Logistics</option>
                        <option value="quality_control">Quality Control</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="employeeShift">Shift</label>
                    <select id="employeeShift" class="form-control" required>
                        <option value="morning">Morning</option>
                        <option value="evening">Evening</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Add</button>
            </form>
        </div>
    </div>

    <div id="addRawMaterialModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal('addRawMaterialModal')">×</span>
            <h3>Add Raw Material</h3>
            <form>
                <div class="form-group">
                    <label for="rawMaterialType">Type</label>
                    <input type="text" id="rawMaterialType" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="rawMaterialGrade">Grade</label>
                    <input type="text" id="rawMaterialGrade" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="rawMaterialStock">Stock (kg)</label>
                    <input type="number" id="rawMaterialStock" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="rawMaterialCost">Cost/kg (UGX)</label>
                    <input type="number" id="rawMaterialCost" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Add</button>
            </form>
        </div>
    </div>

    <div id="addFinishedGoodModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal('addFinishedGoodModal')">×</span>
            <h3>Add Finished Good</h3>
            <form>
                <div class="form-group">
                    <label for="finishedGoodName">Name</label>
                    <input type="text" id="finishedGoodName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="finishedGoodType">Type</label>
                    <select id="finishedGoodType" class="form-control" required>
                        <option value="drinking_coffee">Drinking Coffee</option>
                        <option value="roasted_coffee">Roasted Coffee</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="finishedGoodStock">Stock (kg)</label>
                    <input type="number" id="finishedGoodStock" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="finishedGoodPrice">Price/kg (UGX)</label>
                    <input type="number" id="finishedGoodPrice" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Add</button>
            </form>
        </div>
    </div>

    <div id="addRetailerOrderModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal('addRetailerOrderModal')">×</span>
            <h3>Create Retailer Order</h3>
            <form>
                <div class="form-group">
                    <label for="orderId">Order ID</label>
                    <input type="text" id="orderId" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="orderCustomer">Customer</label>
                    <input type="text" id="orderCustomer" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="orderAmount">Amount (UGX)</label>
                    <input type="number" id="orderAmount" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Create</button>
            </form>
        </div>
    </div>

    <div id="addFarmerOrderModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal('addFarmerOrderModal')">×</span>
            <h3>Create Farmer Order</h3>
            <form>
                <div class="form-group">
                    <label for="orderId">Order ID</label>
                    <input type="text" id="orderId" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="orderFarmer">Farmer</label>
                    <input type="text" id="orderFarmer" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="orderAmount">Amount (UGX)</label>
                    <input type="number" id="orderAmount" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Create</button>
            </form>
        </div>
    </div>

    <div id="sendMessageModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal('sendMessageModal')">×</span>
            <h3>Send Message</h3>
            <form>
                <div class="form-group">
                    <label for="messageRecipient">Recipient</label>
                    <input type="text" id="messageRecipient" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="messageSubject">Subject</label>
                    <input type="text" id="messageSubject" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="messageBody">Message</label>
                    <textarea id="messageBody" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-success">Send</button>
            </form>
        </div>
    </div>
@endsection