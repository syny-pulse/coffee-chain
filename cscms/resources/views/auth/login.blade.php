{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center relative overflow-hidden">
    <!-- Background with coffee theme -->
    <div class="absolute inset-0 bg-gradient-to-br from-coffee-cream via-coffee-light-bg to-coffee-cream"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-coffee-light-opacity to-coffee-medium-opacity"></div>
    
    <!-- Floating coffee beans decoration -->
    <div class="absolute top-10 left-10 w-20 h-20 bg-coffee-medium rounded-full opacity-10 animate-float"></div>
    <div class="absolute top-32 right-20 w-16 h-16 bg-coffee-light rounded-full opacity-15 animate-float-delayed"></div>
    <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-coffee-dark rounded-full opacity-10 animate-float"></div>
    <div class="absolute bottom-40 right-10 w-24 h-24 bg-coffee-medium rounded-full opacity-8 animate-float-delayed"></div>

    <div class="relative z-10 max-w-md w-full mx-4">
        <!-- Login Card -->
        <div class="bg-white bg-opacity-90 backdrop-blur-lg rounded-3xl shadow-2xl border border-coffee-light border-opacity-20 p-8 transform transition-all duration-300 hover:shadow-coffee-medium hover:shadow-opacity-20">
            
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center items-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-coffee-medium to-coffee-light rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-seedling text-white text-2xl"></i>
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-coffee-dark mb-2">Coffee Supply Chain</h1>
                <h2 class="text-lg font-semibold text-coffee-medium">Welcome Back</h2>
                <p class="text-coffee-light text-sm mt-2">Sign in to your account to continue</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email" class="block text-sm font-semibold text-coffee-dark mb-2">
                        <i class="fas fa-envelope mr-2 text-coffee-medium"></i>
                        Email Address
                    </label>
                    <input id="email" 
                           name="email" 
                           type="email"
                           class="w-full px-4 py-3 bg-white bg-opacity-80 border-2 border-coffee-light border-opacity-30 rounded-xl text-coffee-dark placeholder-coffee-light placeholder-opacity-60 focus:outline-none focus:border-coffee-medium focus:bg-white focus:bg-opacity-100 transition-all duration-300 @error('email') border-red-400 @enderror"
                           placeholder="Enter your email address"
                           value="{{ old('email') }}" 
                           required 
                           autofocus>
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
                        Password
                    </label>
                    <div class="relative">
                        <input id="password" 
                               name="password" 
                               type="password"
                               class="w-full px-4 py-3 bg-white bg-opacity-80 border-2 border-coffee-light border-opacity-30 rounded-xl text-coffee-dark placeholder-coffee-light placeholder-opacity-60 focus:outline-none focus:border-coffee-medium focus:bg-white focus:bg-opacity-100 transition-all duration-300 @error('password') border-red-400 @enderror"
                               placeholder="Enter your password" 
                               required>
                        <button type="button" 
                                onclick="togglePassword()" 
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
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" 
                               name="remember" 
                               type="checkbox"
                               class="w-4 h-4 text-coffee-medium bg-white border-coffee-light rounded focus:ring-coffee-medium focus:ring-2">
                        <label for="remember_me" class="ml-2 text-sm text-coffee-dark">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-coffee-medium hover:text-coffee-dark transition-colors duration-200">
                            Forgot password?
                        </a>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full bg-gradient-to-r from-coffee-medium to-coffee-light text-black font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-coffee-medium focus:ring-opacity-50 flex items-center justify-center">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Sign In
                </button>

                <!-- Register Link -->
                <div class="text-center pt-4 border-t border-coffee-light border-opacity-20">
                    <p class="text-sm text-coffee-light">
                        Don't have an account?
                        <a href="{{ route('register') }}" 
                           class="font-semibold text-coffee-medium hover:text-coffee-dark transition-colors duration-200 ml-1">
                            Create one here
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
                &copy; 2024 Coffee Supply Chain Management - G-26
            </p>
        </div>
    </div>
</div>

<style>
:root {
    --coffee-dark: #2D1B0E;
    --coffee-medium: #6F4E37;
    --coffee-light: #A0702A;
    --coffee-cream: #F5F1EB;
    --coffee-accent: #D4A574;
}

.bg-coffee-cream { background-color: var(--coffee-cream); }
.bg-coffee-light-bg { background-color: #FAFAF8; }
.bg-coffee-light-opacity { background-color: rgba(160, 112, 42, 0.1); }
.bg-coffee-medium-opacity { background-color: rgba(111, 78, 55, 0.1); }
.bg-coffee-medium { background-color: var(--coffee-medium); }
.bg-coffee-light { background-color: var(--coffee-light); }
.bg-coffee-dark { background-color: var(--coffee-dark); }

.text-coffee-dark { color: var(--coffee-dark); }
.text-coffee-medium { color: var(--coffee-medium); }
.text-coffee-light { color: #666; }

.border-coffee-light { border-color: var(--coffee-light); }
.border-coffee-medium { border-color: var(--coffee-medium); }

.focus\:border-coffee-medium:focus { border-color: var(--coffee-medium); }
.focus\:ring-coffee-medium:focus { --tw-ring-color: var(--coffee-medium); }

.hover\:text-coffee-medium:hover { color: var(--coffee-medium); }
.hover\:text-coffee-dark:hover { color: var(--coffee-dark); }

.hover\:shadow-coffee-medium:hover { box-shadow: 0 20px 40px rgba(111, 78, 55, 0.2); }

.placeholder-coffee-light::placeholder { color: #666; }

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-float-delayed {
    animation: float 6s ease-in-out infinite;
    animation-delay: 3s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    33% { transform: translateY(-10px) rotate(120deg); }
    66% { transform: translateY(-5px) rotate(240deg); }
}

.form-group input:focus {
    transform: translateY(-1px);
    box-shadow: 0 8px 25px rgba(111, 78, 55, 0.15);
}

button[type="submit"]:active {
    transform: translateY(0) !important;
}
</style>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('password-toggle-icon');
    
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

// Add floating animation to form elements
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