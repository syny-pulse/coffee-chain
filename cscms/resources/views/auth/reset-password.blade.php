{{-- resources/views/auth/reset-password.blade.php --}}
@extends('layouts.app')
@section('title', 'Reset Password')

@section('content')
    <div class="min-h-screen flex items-center justify-center relative overflow-hidden">
        <!-- Background with coffee theme -->
        <div class="absolute inset-0 bg-gradient-to-br from-coffee-cream via-coffee-light-bg to-coffee-cream"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-coffee-light-opacity to-coffee-medium-opacity"></div>

        <!-- Floating coffee beans decoration -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-coffee-medium rounded-full opacity-10 animate-float"></div>
        <div class="absolute top-32 right-20 w-16 h-16 bg-coffee-light rounded-full opacity-15 animate-float-delayed"></div>
        <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-coffee-dark rounded-full opacity-10 animate-float"></div>
        <div class="absolute bottom-40 right-10 w-24 h-24 bg-coffee-medium rounded-full opacity-8 animate-float-delayed">
        </div>

        <div class="relative z-10 max-w-md w-full mx-4">
            <!-- Reset Password Card -->
            <div
                class="bg-white bg-opacity-90 backdrop-blur-lg rounded-3xl shadow-2xl border border-coffee-light border-opacity-20 p-8 transform transition-all duration-300 hover:shadow-coffee-medium hover:shadow-opacity-20">

                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="flex justify-center items-center mb-4">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-coffee-medium to-coffee-light rounded-full flex items-center justify-center shadow-lg">
                            <i class="fas fa-seedling text-white text-2xl"></i>
                        </div>
                    </div>
                    <h1 class="text-2xl font-bold text-coffee-dark mb-2">Coffee Supply Chain</h1>
                    <h2 class="text-lg font-semibold text-coffee-medium">Reset Your Password</h2>
                    <p class="text-coffee-light text-sm mt-2">Enter your email to receive a password reset link</p>
                </div>

                <!-- Reset Password Form -->
                <form method="POST" action="{{ route('password.send') }}" class="space-y-6">
                    @csrf

                    <!-- Status Message -->
                    @if (session('status'))
                        <div
                            class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email" class="block text-sm font-semibold text-coffee-dark mb-2">
                            <i class="fas fa-envelope mr-2 text-coffee-medium"></i>
                            Email Address
                        </label>
                        <input id="email" name="email" type="email"
                            class="w-full px-4 py-3 bg-white bg-opacity-80 border-2 border-coffee-light border-opacity-30 rounded-xl text-coffee-dark placeholder-coffee-light placeholder-opacity-60 focus:outline-none focus:border-coffee-medium focus:bg-white focus:bg-opacity-100 transition-all duration-300 @error('email') border-red-400 @enderror"
                            placeholder="Enter your email address" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-coffee-medium to-coffee-light text-black font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-coffee-medium focus:ring-opacity-50 flex items-center justify-center">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Send Reset Link
                    </button>

                    <!-- Back to Login Link -->
                    <div class="text-center pt-4 border-t border-coffee-light border-opacity-20">
                        <p class="text-sm text-coffee-light">
                            Remember your password?
                            <a href="{{ route('login') }}"
                                class="font-semibold text-coffee-medium hover:text-coffee-dark transition-colors duration-200 ml-1">
                                Back to Login
                            </a>
                        </p>
                    </div>
                </form>

                <!-- Additional Info -->
                <div class="mt-8 text-center">
                    <div class="flex items-center justify-center space-x-4 text-xs text-coffee-light">
                        <span class="flex items-center">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Secure Login
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-clock mr-1"></i>
                            24/7 Support
                        </span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-6">
                <p class="text-sm text-coffee-medium opacity-80">
                    © 2024 Coffee Supply Chain Management - G-26
                </p>
            </div>
        </div>
    </div>

    <style>

    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });

                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });
        });
    </script>
@endsection
