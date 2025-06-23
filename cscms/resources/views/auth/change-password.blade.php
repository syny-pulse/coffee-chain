{{-- resources/views/auth/change-password.blade.php --}}
@extends('layouts.app')
@section('title', 'Change Password')

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
            <!-- Change Password Card -->
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
                    <h2 class="text-lg font-semibold text-coffee-medium">Change Your Password</h2>
                    <p class="text-coffee-light text-sm mt-2">Enter your new password to update your account</p>
                </div>

                <!-- Change Password Form -->
                <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

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
                            value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus readonly>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="block text-sm font-semibold text-coffee-dark mb-2">
                            <i class="fas fa-lock mr-2 text-coffee-medium"></i>
                            New Password
                        </label>
                        <div class="relative">
                            <input id="password" name="password" type="password"
                                class="w-full px-4 py-3 bg-white bg-opacity-80 border-2 border-coffee-light border-opacity-30 rounded-xl text-coffee-dark placeholder-coffee-light placeholder-opacity-60 focus:outline-none focus:border-coffee-medium focus:bg-white focus:bg-opacity-100 transition-all duration-300 @error('password') border-red-400 @enderror"
                                placeholder="Enter your new password" required autocomplete="new-password"
                                oninput="updatePasswordStrength(this.value); checkPasswordMatch()">
                            <button type="button" onclick="togglePassword('password')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-coffee-light hover:text-coffee-medium transition-colors duration-200">
                                <i id="password-toggle-icon" class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <div id="password-strength" class="mt-2"></div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="form-group">
                        <label for="password-confirm" class="block text-sm font-semibold text-coffee-dark mb-2">
                            <i class="fas fa-lock mr-2 text-coffee-medium"></i>
                            Confirm Password
                        </label>
                        <div class="relative">
                            <input id="password-confirm" name="password_confirmation" type="password"
                                class="w-full px-4 py-3 bg-white bg-opacity-80 border-2 border-coffee-light border-opacity-30 rounded-xl text-coffee-dark placeholder-coffee-light placeholder-opacity-60 focus:outline-none focus:border-coffee-medium focus:bg-white focus:bg-opacity-100 transition-all duration-300"
                                placeholder="Confirm your new password" required autocomplete="new-password"
                                oninput="checkPasswordMatch()">
                            <button type="button" onclick="togglePassword('password-confirm')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-coffee-light hover:text-coffee-medium transition-colors duration-200">
                                <i id="password-confirm-toggle-icon" class="fas fa-eye"></i>
                            </button>
                        </div>
                        <p id="password-match-message" class="mt-2 text-sm text-red-600 flex items-center"
                            style="display: none;">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            Passwords do not match
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submit-button"
                        class="w-full bg-gradient-to-r from-coffee-medium to-coffee-light text-black font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-coffee-medium focus:ring-opacity-50 flex items-center justify-center">
                        <i class="fas fa-key mr-2"></i>
                        Reset Password
                    </button>
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
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(fieldId + '-toggle-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        function calculatePasswordStrength(password) {
            let score = 0;
            if (password.length >= 8) score++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) score++;
            if (/\d/.test(password)) score++;
            if (/[^a-zA-Z\d]/.test(password)) score++;
            return Math.min(score, 3);
        }

        function updatePasswordStrength(password) {
            const strengthEl = document.getElementById('password-strength');
            if (!strengthEl) return;

            const strength = calculatePasswordStrength(password);
            const colors = ['#dc3545', '#fd7e14', '#ffc107', '#28a745']; // Red, Orange, Yellow, Green
            const labels = ['Weak', 'Fair', 'Good', 'Strong'];

            if (password.length === 0) {
                strengthEl.innerHTML = '';
                return;
            }

            strengthEl.innerHTML = `
                <div class="mt-2">
                    <div class="h-1 bg-coffee-light bg-opacity-20 rounded-full overflow-hidden">
                        <div class="h-full bg-[${colors[strength]}] transition-all duration-300 ease-in-out" style="width: ${(strength + 1) * 25}%"></div>
                    </div>
                    <p class="text-xs text-[${colors[strength]}] mt-1 flex items-center">
                        <i class="fas fa-shield-alt mr-1 text-[${colors[strength]}]"></i>
                        Password strength: ${labels[strength]}
                    </p>
                </div>
            `;
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password-confirm').value;
            const messageElement = document.getElementById('password-match-message');
            const submitButton = document.getElementById('submit-button');

            if (password === confirmPassword) {
                messageElement.style.display = 'none';
                submitButton.disabled = false;
            } else {
                messageElement.style.display = 'flex';
                submitButton.disabled = true;
            }
        }

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

            // Initialize password strength and match check
            document.getElementById('password').addEventListener('input', function() {
                updatePasswordStrength(this.value);
                checkPasswordMatch();
            });
            document.getElementById('password-confirm').addEventListener('input', checkPasswordMatch);
        });
    </script>
@endsection
