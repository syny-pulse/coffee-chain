<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

    public function store(Request $request)
    {
        // Check if someone tries to register as processor
        if ($request->input('user_type') === 'processor') {
            return response()->json([
                'message' => 'Processor registration is not allowed.',
                'errors' => ['user_type' => ['Processor accounts are managed by the system administrator.']],
            ], 403);
        }

        // Validate the request
        $validated = $request->validate([
            // Personal Information
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:60',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'user_type' => 'required|in:farmer,retailer',
            // Company Information
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|unique:companies,email|max:60',
            'company_phone' => 'required|string|max:20',
            'registration_number' => 'required|string|max:50',
            'company_address' => 'required|string|max:100',
            // PDF Upload
            'pdf' => 'required|file|mimes:pdf|max:10', // Max 10KB
        ]);

        DB::beginTransaction();

        try {
            // Create the user
            $user = new User();
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['password']);
            $user->user_type = $validated['user_type'];
            $user->phone = $validated['phone'];
            $user->address = $validated['address'];
            $user->save();

            // Store the PDF file
            if (!$request->hasFile('pdf') || !$request->file('pdf')->isValid()) {
                throw new \Exception('Invalid or missing PDF file.');
            }
            $pdfPath = $request->file('pdf')->store('applications', 'public');
            $absolutePdfPath = Storage::disk('public')->path($pdfPath);

            // Create the company
            $company = new Company();
            $company->company_name = $validated['company_name'];
            $company->email = $validated['company_email'];
            $company->phone = $validated['company_phone'];
            $company->company_type = $validated['user_type'];
            $company->registration_number = $validated['registration_number'];
            $company->address = $validated['company_address'];
            $company->pdf_path = $absolutePdfPath;
            $company->acceptance_status = 'pending';
            $company->financial_risk_rating = 0.0;
            $company->reputational_risk_rating = 0.0;
            $company->compliance_risk_rating = 0.0;
            $company->save();

            // Associate user with company
            $user->company_id = $company->company_id;
            $user->save();

            DB::commit();

            // Return success response
            return response()->json([
                'message' => 'Registration successful.',
            ], 201);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration failed: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'Registration failed. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
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
        // Check if someone tries to register as processor
        if (isset($data['user_type']) && $data['user_type'] === 'processor') {
            throw ValidationException::withMessages([
                'user_type' => ['Processor accounts are managed by the system administrator.']
            ]);
        }

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_type' => ['required', 'in:farmer,retailer'],
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
