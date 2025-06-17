@extends('layouts.processor')

@section('title', 'Add Employee')

@section('content')
<section class="section" id="employees">
    <div class="section-container">
        <div class="section-header">
            <h2>Add New Employee</h2>
            <p>Register a new employee in the system</p>
        </div>

        <form action="{{ route('processor.employee.store') }}" method="POST" class="form-container">
            @csrf
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="position">Position</label>
                <select id="position" name="position" class="form-control" required>
                    <option value="">Select Position</option>
                    <option value="Grading">Grading</option>
                    <option value="Roasting">Roasting</option>
                    <option value="Packaging">Packaging</option>
                    <option value="Quality Control">Quality Control</option>
                </select>
            </div>

            <div class="form-group">
                <label for="shift">Shift</label>
                <select id="shift" name="shift" class="form-control" required>
                    <option value="">Select Shift</option>
                    <option value="Morning">Morning</option>
                    <option value="Evening">Evening</option>
                    <option value="Night">Night</option>
                </select>
            </div>

            <div class="form-group">
                <label for="salary">Salary (UGX)</label>
                <input type="number" id="salary" name="salary" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="joining_date">Joining Date</label>
                <input type="date" id="joining_date" name="joining_date" class="form-control" required>
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
</section>
@endsection