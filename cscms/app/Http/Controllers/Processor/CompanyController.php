<?php

namespace App\Http\Controllers\Processor;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function index()
    {
        // Fetch companies with related user account status
        $companies = DB::table('companies')
            ->leftJoin('users', 'companies.company_id', '=', 'users.company_id')
            ->select(
                'companies.company_id',
                'companies.company_name',
                'companies.email',
                'companies.company_type',
                'companies.phone',
                'companies.address',
                'companies.registration_number',
                'companies.acceptance_status',
                'users.status as account_status',
                'users.id as user_id' // Include user_id for account status updates
            )
            ->get();

        return view('processor.company', compact('companies'));
    }

    public function updateAcceptanceStatus(Request $request, $companyId)
    {
        $request->validate([
            'acceptance_status' => 'required|in:accepted,rejected,pending,visit_scheduled',
        ]);

        $company = Company::findOrFail($companyId);
        $company->acceptance_status = $request->acceptance_status;
        $company->save();

        return redirect()->route('processor.company.index')->with('success', 'Acceptance status updated successfully.');
    }

    public function updateAccountStatus(Request $request, $userId)
    {
        $request->validate([
            'account_status' => 'required|in:active,inactive,pending',
        ]);

        $user = User::findOrFail($userId);
        $user->status = $request->account_status;
        $user->save();

        return redirect()->route('processor.company.index')->with('success', 'Account status updated successfully.');
    }
}
