@extends('layouts.processor')

@section('title', 'Employee Management')

@section('content')
<section class="section" id="employees">
    <div class="section-container">
         <div class="dashboard-header fade-in">
        <div class="dashboard-title">
           <i class="fas fa-plus"></i>
            <div>
                <h1>Workforce Management</h1>
                <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">
                    Add Employee as of {{ now()->format('h:i A T, l, F d, Y') }}
                </p>
            </div>
        </div>
        <div class="dashboard-actions">
            <a href="{{ route('processor.employee.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i>
                Add Employee
            </a>
        </div>
    </div>
    <div class="section-header">
            <h2>Employees</h2>
            <p>These are the employees who work in this sector</p>
        </div>
        
        <div class="content-section fade-in">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Shift</th>
                            <th>Salary (UGX)</th>
                            <th>Joining Date</th>
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
                                </td>
                                <td>{{ $employee->position ?? 'N/A' }}</td>
                                <td>{{ $employee->shift ?? 'N/A' }}</td>
                                <td>{{ number_format($employee->salary ?? 0) }}</td>
                                <td>{{ isset($employee->joining_date) ? \Carbon\Carbon::parse($employee->joining_date)->format('M d, Y') : 'N/A' }}</td>
                                <td><span class="status-badge status-{{ $employee->status ?? 'inactive' }}">{{ ucfirst($employee->status ?? 'Inactive') }}</span></td>
                                <td>
                                    @if(isset($employee->employee_id))
                                    <a href="{{ route('processor.employee.edit', $employee->employee_id) }}" class="btn" style="padding: 0.25rem 0.5rem; font-size: 0.8rem; background: var(--info); color: white;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('processor.employee.destroy', $employee->employee_id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this employee?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn" style="padding: 0.25rem 0.5rem; font-size: 0.8rem; background: var(--danger); color: white;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No employees found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection