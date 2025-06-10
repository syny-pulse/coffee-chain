<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        // Log the user in
        Auth::login($user);

        return redirect($this->redirectTo)->with('success', 
            'Registration successful! Your account is pending approval.');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_type' => ['required', 'in:farmer,processor,retailer'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            
            // Company fields
            'company_name' => ['required', 'string', 'max:255'],
            'company_email' => ['required', 'string', 'email', 'max:255'],
            'company_phone' => ['required', 'string', 'max:20'],
            'company_address' => ['required', 'string'],
            'registration_number' => ['required', 'string', 'max:255', 'unique:companies'],
        ]);
    }

    protected function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Create company first
            $company = Company::create([
                'company_name' => $data['company_name'],
                'email' => $data['company_email'],
                'company_type' => $data['user_type'],
                'phone' => $data['company_phone'],
                'address' => $data['company_address'],
                'registration_number' => $data['registration_number'],
                'acceptance_status' => 'pending',
                'financial_risk_rating' => 0.0,
                'reputational_risk_rating' => 0.0,
                'compliance_risk_rating' => 0.0,
            ]);

            // Create user
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'user_type' => $data['user_type'],
                'company_id' => $company->company_id,
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'status' => 'pending',
            ]);

            return $user;
        });
    }
}