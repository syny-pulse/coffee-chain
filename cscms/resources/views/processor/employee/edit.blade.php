@extends('layouts.processor')

@section('title', 'Edit Employee')

@section('content')
    <!-- Dashboard Header -->
    <div class="dashboard-header fade-in">
        <div class="dashboard-title">
            <i class="fas fa-user-edit"></i>
            <div>
                <h1>Edit Employee</h1>
                <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">
                    Update employee information
                </p>
            </div>
        </div>
        <div class="dashboard-actions">
            <a href="{{ route('processor.employee.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>
                Back to Employees
            </a>
        </div>
    </div>

    <!-- Employee Form -->
    <div class="content-section fade-in">
        <form action="{{ route('processor.employee.update', $employee->employee_id) }}" method="POST" class="form-container">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="employee_name">Full Name</label>
                <input type="text" id="employee_name" name="employee_name" class="form-control" value="{{ $employee->employee_name }}" required 
                       placeholder="Enter employee's full name">
            </div>

            <div class="form-group">
                <label for="employee_code">Employee Code</label>
                <input type="text" id="employee_code" name="employee_code" class="form-control" value="{{ $employee->employee_code }}" required 
                       placeholder="Enter unique employee code">
            </div>

            <div class="form-group">
                <label for="skill_set">Primary Skill</label>
                <select id="skill_set" name="skill_set" class="form-control" required>
                    <option value="">Select Skill</option>
                    <option value="grading" {{ $employee->skill_set == 'grading' ? 'selected' : '' }}>Grading</option>
                    <option value="roasting" {{ $employee->skill_set == 'roasting' ? 'selected' : '' }}>Roasting</option>
                    <option value="packaging" {{ $employee->skill_set == 'packaging' ? 'selected' : '' }}>Packaging</option>
                    <option value="logistics" {{ $employee->skill_set == 'logistics' ? 'selected' : '' }}>Logistics</option>
                    <option value="quality_control" {{ $employee->skill_set == 'quality_control' ? 'selected' : '' }}>Quality Control</option>
                    <option value="maintenance" {{ $employee->skill_set == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>

            <div class="form-group">
                <label for="primary_station">Primary Work Station</label>
                <select id="primary_station" name="primary_station" class="form-control" required>
                    <option value="">Select Station</option>
                    <option value="grading" {{ $employee->primary_station == 'grading' ? 'selected' : '' }}>Grading</option>
                    <option value="roasting" {{ $employee->primary_station == 'roasting' ? 'selected' : '' }}>Roasting</option>
                    <option value="packaging" {{ $employee->primary_station == 'packaging' ? 'selected' : '' }}>Packaging</option>
                    <option value="logistics" {{ $employee->primary_station == 'logistics' ? 'selected' : '' }}>Logistics</option>
                    <option value="quality_control" {{ $employee->primary_station == 'quality_control' ? 'selected' : '' }}>Quality Control</option>
                    <option value="maintenance" {{ $employee->primary_station == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>

            <div class="form-group">
                <label for="shift_schedule">Shift Schedule</label>
                <select id="shift_schedule" name="shift_schedule" class="form-control" required>
                    <option value="">Select Shift</option>
                    <option value="morning" {{ $employee->shift_schedule == 'morning' ? 'selected' : '' }}>Morning</option>
                    <option value="afternoon" {{ $employee->shift_schedule == 'afternoon' ? 'selected' : '' }}>Afternoon</option>
                    <option value="night" {{ $employee->shift_schedule == 'night' ? 'selected' : '' }}>Night</option>
                    <option value="flexible" {{ $employee->shift_schedule == 'flexible' ? 'selected' : '' }}>Flexible</option>
                </select>
            </div>

            <div class="form-group">
                <label for="hourly_rate">Hourly Rate (UGX)</label>
                <input type="number" id="hourly_rate" name="hourly_rate" class="form-control" step="0.01" value="{{ $employee->hourly_rate }}" required 
                       placeholder="Enter hourly rate">
            </div>

            <div class="form-group">
                <label for="hire_date">Hire Date</label>
                <input type="date" id="hire_date" name="hire_date" class="form-control" value="{{ $employee->hire_date ? $employee->hire_date->format('Y-m-d') : '' }}" required>
            </div>

            <div class="form-group">
                <label for="status">Employment Status</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="active" {{ $employee->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $employee->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="terminated" {{ $employee->status == 'terminated' ? 'selected' : '' }}>Terminated</option>
                </select>
            </div>

            <div class="form-group">
                <label for="availability_status">Current Availability</label>
                <select id="availability_status" name="availability_status" class="form-control" required>
                    <option value="available" {{ $employee->availability_status == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="busy" {{ $employee->availability_status == 'busy' ? 'selected' : '' }}>Busy</option>
                    <option value="on_break" {{ $employee->availability_status == 'on_break' ? 'selected' : '' }}>On Break</option>
                    <option value="off_duty" {{ $employee->availability_status == 'off_duty' ? 'selected' : '' }}>Off Duty</option>
                    <option value="on_leave" {{ $employee->availability_status == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                </select>
            </div>

            <div class="auth-buttons">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update Employee
                </button>
                <a href="{{ route('processor.employee.index') }}" class="btn btn-outline">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<style>
    /* Form Styles */
    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--coffee-dark);
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid rgba(111, 78, 55, 0.2);
        border-radius: 8px;
        font-size: 0.9rem;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
        background: rgba(255, 255, 255, 0.8);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--coffee-medium);
        box-shadow: 0 0 0 3px rgba(111, 78, 55, 0.1);
    }

    .form-control::placeholder {
        color: var(--text-light);
    }

    .auth-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        justify-content: flex-start;
        padding-top: 1rem;
        border-top: 1px solid rgba(111, 78, 55, 0.1);
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(111, 78, 55, 0.3);
    }

    .btn-outline {
        background: transparent;
        color: var(--coffee-medium);
        border: 2px solid var(--coffee-medium);
    }

    .btn-outline:hover {
        background: var(--coffee-medium);
        color: white;
        transform: translateY(-2px);
    }
</style>
@endsection 