<?php

namespace App\Http\Controllers\Processor;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    public function index()
    {
        $companyId = Auth::check() ? Auth::id() : 0; // Default to 0 for testing
        $employees = Employee::where('processor_company_id', $companyId)->get();
        return view('processor.employee.index', compact('employees'));
    }

    public function create()
    {
        return view('processor.employee.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_name' => 'required|string|max:100',
            'employee_code' => 'required|string|max:30|unique:employees,employee_code',
            'skill_set' => 'required|array',
            'skill_set.*' => 'in:grading,roasting,packaging,logistics,quality_control,maintenance',
            'primary_station' => 'required|in:grading,roasting,packaging,logistics,quality_control,maintenance',
            'availability_status' => 'required|in:available,busy,on_break,off_duty,on_leave',
            'shift_schedule' => 'required|in:morning,afternoon,night,flexible',
            'hourly_rate' => 'nullable|numeric|min:0',
            'hire_date' => 'required|date',
        ]);

        try {
            $companyId = Auth::check() ? Auth::id() : 0; // Default to 0 for testing
            Employee::create(array_merge($request->all(), [
                'processor_company_id' => $companyId,
                'status' => 'active',
            ]));

            return redirect()->route('processor.employee.index')
                ->with('success', 'Employee added successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to add employee: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add employee.');
        }
    }

    public function show($id)
    {
        $companyId = Auth::check() ? Auth::id() : 0; // Default to 0 for testing
        $employee = Employee::where('processor_company_id', $companyId)->findOrFail($id);
        return view('processor.employee.show', compact('employee'));
    }

    public function edit($id)
    {
        $companyId = Auth::check() ? Auth::id() : 0; // Default to 0 for testing
        $employee = Employee::where('processor_company_id', $companyId)->findOrFail($id);
        return view('processor.employee.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'employee_name' => 'required|string|max:100',
            'employee_code' => 'required|string|max:30|unique:employees,employee_code,' . $id,
            'skill_set' => 'required|array',
            'skill_set.*' => 'in:grading,roasting,packaging,logistics,quality_control,maintenance',
            'primary_station' => 'required|in:grading,roasting,packaging,logistics,quality_control,maintenance',
            'availability_status' => 'required|in:available,busy,on_break,off_duty,on_leave',
            'shift_schedule' => 'required|in:morning,afternoon,night,flexible',
            'hourly_rate' => 'nullable|numeric|min:0',
            'hire_date' => 'required|date',
        ]);

        try {
            $companyId = Auth::check() ? Auth::id() : 0; // Default to 0 for testing
            $employee = Employee::where('processor_company_id', $companyId)->findOrFail($id);
            $employee->update($request->all());
            return redirect()->route('processor.employee.index')
                ->with('success', 'Employee updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update employee: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update employee.');
        }
    }

    public function destroy($id)
    {
        try {
            $companyId = Auth::check() ? Auth::id() : 0; // Default to 0 for testing
            $employee = Employee::where('processor_company_id', $companyId)->findOrFail($id);
            $employee->delete();
            return redirect()->route('processor.employee.index')
                ->with('success', 'Employee deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete employee: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete employee.');
        }
    }
}