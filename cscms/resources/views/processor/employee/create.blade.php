@extends('layouts.processor')

@section('title', 'Add Employee')

@section('content')
    <!-- Dashboard Header -->
    <div class="dashboard-header fade-in">
        <div class="dashboard-title">
            <i class="fas fa-user-plus"></i>
            <div>
                <h1>Add New Employee</h1>
                <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">
                    Register a new employee in the system
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
        <form action="{{ route('processor.employee.store') }}" method="POST" class="form-container">
            @csrf
            <div class="form-group">
                <label for="employee_name">Full Name</label>
                <input type="text" id="employee_name" name="employee_name" class="form-control" required 
                       placeholder="Enter employee's full name">
            </div>

            <div class="form-group">
                <label for="employee_code">Employee Code</label>
                <input type="text" id="employee_code" name="employee_code" class="form-control" required 
                       placeholder="Enter unique employee code">
            </div>

            <div class="form-group">
                <label for="skill_set">Primary Skill</label>
                <select id="skill_set" name="skill_set" class="form-control" required>
                    <option value="">Select Skill</option>
                    <option value="grading">Grading</option>
                    <option value="roasting">Roasting</option>
                    <option value="packaging">Packaging</option>
                    <option value="logistics">Logistics</option>
                    <option value="quality_control">Quality Control</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>

            <div class="form-group">
                <label for="primary_station">Primary Work Station</label>
                <select id="primary_station" name="primary_station" class="form-control" required>
                    <option value="">Select Station</option>
                    <option value="grading">Grading</option>
                    <option value="roasting">Roasting</option>
                    <option value="packaging">Packaging</option>
                    <option value="logistics">Logistics</option>
                    <option value="quality_control">Quality Control</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>

            <div class="form-group">
                <label for="shift_schedule">Shift Schedule</label>
                <select id="shift_schedule" name="shift_schedule" class="form-control" required>
                    <option value="">Select Shift</option>
                    <option value="morning">Morning</option>
                    <option value="afternoon">Afternoon</option>
                    <option value="night">Night</option>
                    <option value="flexible">Flexible</option>
                </select>
            </div>

            <div class="form-group">
                <label for="hourly_rate">Hourly Rate (UGX)</label>
                <input type="number" id="hourly_rate" name="hourly_rate" class="form-control" step="0.01" required 
                       placeholder="Enter hourly rate">
            </div>

            <div class="form-group">
                <label for="hire_date">Hire Date</label>
                <input type="date" id="hire_date" name="hire_date" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="status">Employment Status</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="terminated">Terminated</option>
                </select>
            </div>

            <div class="form-group">
                <label for="availability_status">Initial Availability</label>
                <select id="availability_status" name="availability_status" class="form-control" required>
                    <option value="available">Available</option>
                    <option value="busy">Busy</option>
                    <option value="on_break">On Break</option>
                    <option value="off_duty">Off Duty</option>
                    <option value="on_leave">On Leave</option>
                </select>
            </div>

            <div class="auth-buttons">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Save Employee
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
    }
</style>
@endsection