@extends('layouts.processor')

@section('title', 'Employee Management')

@section('content')
    <!-- Dashboard Header -->
    <div class="dashboard-header fade-in">
        <div class="dashboard-title">
            <i class="fas fa-users"></i>
            <div>
                <h1>Workforce Management</h1>
                <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">
                    Manage employees as of {{ now()->format('h:i A T, l, F d, Y') }}
                </p>
            </div>
        </div>
        <div class="dashboard-actions">
            <a href="{{ route('processor.employee.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Add Employee
            </a>
        </div>
    </div>

    <!-- Employee Overview -->
    <div class="stats-grid fade-in">
        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Total Employees</span>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $employees->count() }}</div>
            <div class="stat-card-change">
                <i class="fas fa-chart-line"></i>
                <span class="change-positive">Total workforce</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Active Employees</span>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $employees->where('status', 'active')->count() }}</div>
            <div class="stat-card-change">
                <i class="fas fa-arrow-up"></i>
                <span class="change-positive">Currently working</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Available</span>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);">
                    <i class="fas fa-user-clock"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $employees->where('availability_status', 'available')->count() }}</div>
            <div class="stat-card-change">
                <i class="fas fa-check"></i>
                <span class="change-positive">Ready to work</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">On Leave</span>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);">
                    <i class="fas fa-user-times"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $employees->where('availability_status', 'on_leave')->count() }}</div>
            <div class="stat-card-change">
                <i class="fas fa-calendar"></i>
                <span class="change-negative">Not available</span>
            </div>
        </div>
    </div>

    <!-- Employees List -->
    <div class="content-section fade-in">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-users"></i>
                <span>All Employees</span>
            </div>
            <a href="{{ route('processor.employee.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Add Employee
            </a>
        </div>
        
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Skill Set</th>
                        <th>Station</th>
                        <th>Shift</th>
                        <th>Hourly Rate</th>
                        <th>Hire Date</th>
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
                                    <strong>{{ $employee->employee_name ?? 'N/A' }}</strong>
                                    <br><small style="color: var(--text-light);">ID: {{ $employee->employee_code ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ ucfirst(str_replace('_', ' ', $employee->skill_set ?? 'N/A')) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $employee->primary_station ?? 'N/A')) }}</td>
                        <td>{{ ucfirst($employee->shift_schedule ?? 'N/A') }}</td>
                        <td>UGX {{ number_format($employee->hourly_rate ?? 0) }}</td>
                        <td>{{ isset($employee->hire_date) ? \Carbon\Carbon::parse($employee->hire_date)->format('M d, Y') : 'N/A' }}</td>
                        <td>
                            @if($employee->status == 'active')
                                <span class="status-badge status-high">Active</span>
                            @elseif($employee->status == 'inactive')
                                <span class="status-badge status-medium">Inactive</span>
                            @else
                                <span class="status-badge status-low">Terminated</span>
                            @endif
                        </td>
                        <td>
                            <a href="#" class="btn" style="padding: 0.25rem 0.5rem; font-size: 0.8rem; background: var(--coffee-medium); color: white;">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No employees found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection