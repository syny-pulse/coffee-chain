{{-- resources/views/dashboard/pending.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">Account Pending Approval</h3>
                    
                    <div class="mt-4 text-sm text-gray-600">
                        <p>Welcome, {{ $user->name }}!</p>
                        <p class="mt-2">Your {{ $user->user_type }} account and company registration are currently under review.</p>
                        <p class="mt-2">You will receive an email notification once your account has been approved.</p>
                    </div>

                    <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-700">
                            <h4 class="font-medium">Your Registration Details:</h4>
                            <div class="mt-2 space-y-1">
                                <p><span class="font-medium">Name:</span> {{ $user->name }}</p>
                                <p><span class="font-medium">Email:</span> {{ $user->email }}</p>
                                <p><span class="font-medium">Role:</span> {{ ucfirst($user->user_type) }}</p>
                                @if($user->company)
                                    <p><span class="font-medium">Company:</span> {{ $user->company->company_name }}</p>
                                @endif
                                <p><span class="font-medium">Status:</span> 
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection