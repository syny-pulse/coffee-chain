<?php

namespace App\Http\Controllers\Processor;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        // Get the search and sort queries from the request
        $search = $request->query('search');
        $sort = $request->query('sort');

        // Build the query to fetch companies with related user account status
        $query = DB::table('companies')
            ->leftJoin('users', 'companies.company_id', '=', 'users.company_id')
            ->select(
                'companies.company_id',
                'companies.company_name',
                'companies.email',
                'companies.company_type',
                'companies.phone',
                'companies.address',
                'companies.registration_number',
                'companies.pdf_path',
                'companies.created_at',
                'companies.acceptance_status',
                'users.status as account_status',
                'users.id as user_id'
            );

        // Apply search filter if provided
        if ($search) {
            $query->where('companies.company_name', 'LIKE', '%' . $search . '%');
        }

        // Apply sorting
        if ($sort) {
            switch ($sort) {
                case 'created_at_desc':
                    $query->orderBy('companies.created_at', 'desc');
                    break;
                case 'created_at_asc':
                    $query->orderBy('companies.created_at', 'asc');
                    break;
                case 'company_name_asc':
                    $query->orderBy('companies.company_name', 'asc');
                    break;
                case 'company_name_desc':
                    $query->orderBy('companies.company_name', 'desc');
                    break;
                default:
                    $query->orderBy('companies.created_at', 'asc');
                    break;
            }
        } else {
            // Default sorting by created_at ascending
            $query->orderBy('companies.created_at', 'asc');
        }

        // Execute the query and get the results
        $companies = $query->get();

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
