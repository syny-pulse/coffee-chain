<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Coffee Supply Chain - Onboarding</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --coffee-dark: #2D1B0E;
            --coffee-medium: #6F4E37;
            --coffee-light: #A0702A;
            --cream: #F5F1EB;
            --accent: #D4A574;
            --text-dark: #2D1B0E;
            --text-light: #666;
            --success: #28a745;
            --error: #dc3545;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--cream) 0%, #FAFAF8 100%);
            min-height: 100vh;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(160, 112, 42, 0.06) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(111, 78, 55, 0.06) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .onboarding-container {
            position: relative;
            z-index: 1;
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem 1rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .onboarding-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            font-size: 2rem;
            font-weight: 700;
            color: var(--coffee-dark);
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--coffee-dark) 0%, var(--coffee-medium) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .welcome-text {
            color: var(--text-light);
            font-size: 1.1rem;
        }

        /* Progress Bar */
        .progress-container {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(111, 78, 55, 0.1);
            border: 1px solid rgba(111, 78, 55, 0.1);
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            position: relative;
        }

        .progress-line {
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 3px;
            background: rgba(111, 78, 55, 0.2);
            border-radius: 2px;
            z-index: 1;
        }

        .progress-line-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);
            border-radius: 2px;
            transition: width 0.5s ease;
            width: 0%;
        }

        .step-indicator {
            position: relative;
            z-index: 2;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: 3px solid rgba(111, 78, 55, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--text-light);
            transition: all 0.3s ease;
        }

        .step-indicator.active {
            border-color: var(--coffee-medium);
            color: var(--coffee-medium);
            background: rgba(111, 78, 55, 0.1);
            transform: scale(1.1);
        }

        .step-indicator.completed {
            background: var(--coffee-medium);
            border-color: var(--coffee-medium);
            color: white;
        }

        .step-labels {
            display: flex;
            justify-content: space-between;
            margin-top: 0.5rem;
        }

        .step-label {
            font-size: 0.85rem;
            color: var(--text-light);
            text-align: center;
            min-width: 80px;
        }

        .step-label.active {
            color: var(--coffee-medium);
            font-weight: 600;
        }

        /* Main Card */
        .onboarding-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(111, 78, 55, 0.15);
            border: 1px solid rgba(111, 78, 55, 0.1);
            backdrop-filter: blur(20px);
            flex: 1;
            overflow: hidden;
            position: relative;
        }

        .onboarding-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);
        }

        /* Step Content */
        .step-content {
            display: none;
            padding: 3rem;
            animation: fadeIn 0.5s ease-in-out;
        }

        .step-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .step-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--coffee-dark);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .step-title i {
            color: var(--coffee-medium);
            font-size: 1.5rem;
        }

        .step-description {
            color: var(--text-light);
            font-size: 1.1rem;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        /* User Type Selection */
        .user-type-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .user-type-card {
            background: rgba(255, 255, 255, 0.8);
            border: 2px solid rgba(111, 78, 55, 0.15);
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .user-type-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(111, 78, 55, 0.05) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .user-type-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(111, 78, 55, 0.2);
            border-color: var(--coffee-medium);
        }

        .user-type-card:hover::before {
            opacity: 1;
        }

        .user-type-card.selected {
            border-color: var(--coffee-medium);
            background: rgba(111, 78, 55, 0.1);
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(111, 78, 55, 0.25);
        }

        .user-type-card.selected::before {
            opacity: 1;
        }

        .user-type-icon {
            font-size: 3rem;
            color: var(--coffee-medium);
            margin-bottom: 1rem;
            display: block;
        }

        .user-type-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--coffee-dark);
            margin-bottom: 0.5rem;
        }

        .user-type-description {
            color: var(--text-light);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Form Styles */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--coffee-dark);
            margin-bottom: 0.75rem;
        }

        .form-input,
        .form-textarea {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid rgba(111, 78, 55, 0.2);
            border-radius: 12px;
            font-size: 1rem;
            color: var(--text-dark);
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input:focus,
        .form-textarea:focus {
            border-color: var(--coffee-medium);
            box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.1);
            background: white;
            transform: translateY(-2px);
        }

        .form-input.error,
        .form-textarea.error {
            border-color: var(--error);
            box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.1);
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .error-message {
            color: var(--error);
            font-size: 0.875rem;
            margin-top: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Navigation Buttons */
        .step-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 2rem 3rem;
            border-top: 1px solid rgba(111, 78, 55, 0.1);
            background: rgba(255, 255, 255, 0.5);
        }

        .nav-button {
            padding: 0.875rem 2rem;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .nav-button.secondary {
            background: rgba(111, 78, 55, 0.1);
            color: var(--coffee-medium);
            border: 2px solid rgba(111, 78, 55, 0.2);
        }

        .nav-button.secondary:hover {
            background: rgba(111, 78, 55, 0.2);
            transform: translateY(-2px);
        }

        .nav-button.primary {
            background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(111, 78, 55, 0.3);
        }

        .nav-button.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(111, 78, 55, 0.4);
        }

        .nav-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Success Animation */
        .success-animation {
            text-align: center;
            padding: 3rem;
        }

        .success-icon {
            font-size: 4rem;
            color: var(--success);
            margin-bottom: 1.5rem;
            animation: bounceIn 0.8s ease-out;
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            50% {
                transform: scale(1.2);
                opacity: 1;
            }

            100% {
                transform: scale(1);
            }
        }

        .success-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--coffee-dark);
            margin-bottom: 1rem;
        }

        .success-description {
            color: var(--text-light);
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .onboarding-container {
                padding: 1rem 0.5rem;
            }

            .step-content {
                padding: 2rem 1.5rem;
            }

            .step-navigation {
                padding: 1.5rem;
                flex-direction: column;
                gap: 1rem;
            }

            .nav-button {
                width: 100%;
                justify-content: center;
            }

            .user-type-grid {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .progress-steps {
                margin-bottom: 0.5rem;
            }

            .step-indicator {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
            }

            .step-label {
                font-size: 0.75rem;
                min-width: 60px;
            }
        }

        /* Loading State */
        .loading {
            pointer-events: none;
            position: relative;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="onboarding-container">
        <!-- Header -->
        <div class="onboarding-header">
            <div class="logo">☕ Coffee Supply Chain</div>
            <p class="welcome-text">Join our network of coffee professionals</p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-container">
            <div class="progress-steps">
                <div class="progress-line">
                    <div class="progress-line-fill" id="progressFill"></div>
                </div>
                <div class="step-indicator active" data-step="1">1</div>
                <div class="step-indicator" data-step="2">2</div>
                <div class="step-indicator" data-step="3">3</div>
                <div class="step-indicator" data-step="4">4</div>
                <div class="step-indicator" data-step="5">5</div>
            </div>
            <div class="step-labels">
                <div class="step-label active">Role</div>
                <div class="step-label">Personal</div>
                <div class="step-label">Security</div>
                <div class="step-label">Company</div>
                <div class="step-label">Review</div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="onboarding-card">
            <!-- Step 1: User Type Selection -->
            <div class="step-content active" id="step1">
                <div class="step-title">
                    <i class="fas fa-user-tag"></i>
                    Choose Your Role
                </div>
                <p class="step-description">
                    Tell us about your role in the coffee supply chain. This helps us customize your experience and
                    connect you with the right partners.
                </p>

                <div class="user-type-grid">
                    <div class="user-type-card" data-type="farmer">
                        <i class="fas fa-seedling user-type-icon"></i>
                        <div class="user-type-title">Coffee Farmer</div>
                        <p class="user-type-description">
                            I grow and harvest coffee beans, managing farms and working directly with the land.
                        </p>
                    </div>

                    <div class="user-type-card" data-type="processor">
                        <i class="fas fa-industry user-type-icon"></i>
                        <div class="user-type-title">Coffee Processor</div>
                        <p class="user-type-description">
                            I process raw coffee beans, handling roasting, packaging, and quality control.
                        </p>
                    </div>

                    <div class="user-type-card" data-type="retailer">
                        <i class="fas fa-store user-type-icon"></i>
                        <div class="user-type-title">Coffee Retailer</div>
                        <p class="user-type-description">
                            I sell coffee products to consumers through cafes, shops, or online platforms.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Step 2: Personal Information -->
            <div class="step-content" id="step2">
                <div class="step-title">
                    <i class="fas fa-user"></i>
                    Personal Information
                </div>
                <p class="step-description">
                    Let's get to know you better. This information will be used for your profile and account
                    verification.
                </p>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" id="name" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" id="email" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" id="phone" class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="address" class="form-label">Personal Address</label>
                        <input type="text" id="address" class="form-input">
                    </div>
                </div>
            </div>

            <!-- Step 3: Security -->
            <div class="step-content" id="step3">
                <div class="step-title">
                    <i class="fas fa-shield-alt"></i>
                    Secure Your Account
                </div>
                <p class="step-description">
                    Create a strong password to protect your account. Make sure it's at least 8 characters long and
                    includes a mix of letters, numbers, and symbols.
                </p>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="password" class="form-label">Password *</label>
                        <input type="password" id="password" class="form-input" required>
                        <div class="password-strength" id="passwordStrength"></div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password *</label>
                        <input type="password" id="password_confirmation" class="form-input" required>
                    </div>
                </div>
            </div>

            <!-- Step 4: Company Information -->
            <div class="step-content" id="step4">
                <div class="step-title">
                    <i class="fas fa-building"></i>
                    Company Details
                </div>
                <p class="step-description">
                    Tell us about your business. This information helps us verify your company and enables
                    business-to-business transactions.
                </p>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="company_name" class="form-label">Company Name *</label>
                        <input type="text" id="company_name" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="company_email" class="form-label">Company Email *</label>
                        <input type="email" id="company_email" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="company_phone" class="form-label">Company Phone *</label>
                        <input type="tel" id="company_phone" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="registration_number" class="form-label">Business Registration Number *</label>
                        <input type="text" id="registration_number" class="form-input" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="company_address" class="form-label">Company Address *</label>
                    <textarea id="company_address" class="form-textarea" required></textarea>
                </div>
            </div>

            <!-- Step 5: Review & Submit -->
            <div class="step-content" id="step5">
                <div class="step-title">
                    <i class="fas fa-check-circle"></i>
                    Review Your Information
                </div>
                <p class="step-description">
                    Please review all the information you've provided. Make sure everything is correct before submitting
                    your registration.
                </p>

                <div id="reviewContent">
                    <!-- Review content will be populated by JavaScript -->
                </div>
            </div>

            <!-- Success Screen -->
            <div class="step-content" id="success" style="display: none;">
                <div class="success-animation">
                    <i class="fas fa-check-circle success-icon"></i>
                    <div class="success-title">Welcome to Coffee Supply Chain!</div>
                    <p class="success-description">
                        Your account has been created successfully. We've sent a verification email to your inbox.
                        Please check your email and click the verification link to activate your account.
                    </p>
                    <a href="/login" class="nav-button primary">
                        <i class="fas fa-sign-in-alt"></i>
                        Sign In to Your Account
                    </a>
                </div>
            </div>

            <!-- Navigation -->
            <div class="step-navigation">
                <button type="button" class="nav-button secondary" id="prevBtn" style="visibility: hidden;">
                    <i class="fas fa-arrow-left"></i>
                    Previous
                </button>

                <button type="button" class="nav-button primary" id="nextBtn" disabled>
                    Next
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        class OnboardingFlow {
            constructor() {
                this.currentStep = 1;
                this.totalSteps = 5;
                this.formData = {};
                this.init();
            }

            init() {
                this.bindEvents();
                this.updateUI();
            }

            bindEvents() {
                // Navigation buttons
                document.getElementById('nextBtn').addEventListener('click', () => this.nextStep());
                document.getElementById('prevBtn').addEventListener('click', () => this.prevStep());

                // User type selection
                document.querySelectorAll('.user-type-card').forEach(card => {
                    card.addEventListener('click', () => {
                        document.querySelectorAll('.user-type-card').forEach(c => c.classList.remove(
                            'selected'));
                        card.classList.add('selected');
                        this.formData.user_type = card.dataset.type;
                        this.validateCurrentStep();
                    });
                });

                // Form inputs
                document.querySelectorAll('.form-input, .form-textarea').forEach(input => {
                    input.addEventListener('input', () => {
                        this.formData[input.id] = input.value;
                        this.validateField(input);
                        this.validateCurrentStep();
                    });

                    input.addEventListener('blur', () => {
                        this.validateField(input);
                    });
                });

                // Password confirmation
                document.getElementById('password_confirmation').addEventListener('input', () => {
                    this.validatePasswordMatch();
                });
            }

            validateField(field) {
                const value = field.value.trim();
                let isValid = true;

                // Required field validation
                if (field.hasAttribute('required') && value === '') {
                    isValid = false;
                }

                // Email validation
                if (field.type === 'email' && value !== '') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(value)) {
                        isValid = false;
                    }
                }

                // Password strength
                if (field.id === 'password') {
                    this.updatePasswordStrength(value);
                }

                // Update field appearance
                if (isValid) {
                    field.classList.remove('error');
                    this.removeErrorMessage(field);
                } else {
                    field.classList.add('error');
                    if (field.hasAttribute('required') && value === '') {
                        this.showErrorMessage(field, 'This field is required');
                    } else if (field.type === 'email') {
                        this.showErrorMessage(field, 'Please enter a valid email address');
                    }
                }

                return isValid;
            }

            validatePasswordMatch() {
                const password = document.getElementById('password').value;
                const confirmation = document.getElementById('password_confirmation').value;
                const confirmField = document.getElementById('password_confirmation');

                if (confirmation && password !== confirmation) {
                    confirmField.classList.add('error');
                    this.showErrorMessage(confirmField, 'Passwords do not match');
                    return false;
                } else {
                    confirmField.classList.remove('error');
                    this.removeErrorMessage(confirmField);
                    return true;
                }
            }

            updatePasswordStrength(password) {
                const strengthEl = document.getElementById('passwordStrength');
                if (!strengthEl) return;

                const strength = this.calculatePasswordStrength(password);
                const colors = ['#dc3545', '#fd7e14', '#ffc107', '#28a745'];
                const labels = ['Weak', 'Fair', 'Good', 'Strong'];

                if (password.length === 0) {
                    strengthEl.innerHTML = '';
                    return;
                }

                strengthEl.innerHTML = `
                    <div style="margin-top: 0.5rem;">
                        <div style="height: 4px; background: #e9ecef; border-radius: 2px; overflow: hidden;">
                            <div style="height: 100%; width: ${(strength + 1) * 25}%; background: ${colors[strength]}; transition: all 0.3s ease;"></div>
                        </div>
                        <small style="color: ${colors[strength]}; font-size: 0.8rem; margin-top: 0.25rem; display: block;">
                            Password strength: ${labels[strength]}
                        </small>
                    </div>
                `;
            }

            calculatePasswordStrength(password) {
                let score = 0;
                if (password.length >= 8) score++;
                if (/[a-z]/.test(password) && /[A-Z]/.test(password)) score++;
                if (/\d/.test(password)) score++;
                if (/[^a-zA-Z\d]/.test(password)) score++;
                return Math.min(score, 3);
            }

            showErrorMessage(field, message) {
                this.removeErrorMessage(field);
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
                field.parentNode.appendChild(errorDiv);
            }

            removeErrorMessage(field) {
                const existingError = field.parentNode.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }
            }

            validateCurrentStep() {
                let isValid = false;

                switch (this.currentStep) {
                    case 1:
                        isValid = !!this.formData.user_type;
                        break;
                    case 2:
                        isValid = this.formData.name && this.formData.email &&
                            this.validateField(document.getElementById('name')) &&
                            this.validateField(document.getElementById('email'));
                        break;
                    case 3:
                        isValid = this.formData.password && this.formData.password_confirmation &&
                            this.formData.password === this.formData.password_confirmation &&
                            this.formData.password.length >= 8;
                        break;
                    case 4:
                        isValid = this.formData.company_name && this.formData.company_email &&
                            this.formData.company_phone && this.formData.registration_number &&
                            this.formData.company_address;
                        break;
                    case 5:
                        isValid = true;
                        break;
                }

                const nextBtn = document.getElementById('nextBtn');
                nextBtn.disabled = !isValid;
                return isValid;
            }

            nextStep() {
                if (!this.validateCurrentStep()) return;

                if (this.currentStep === 5) {
                    this.submitForm();
                    return;
                }

                this.currentStep++;
                this.updateUI();
                this.animateStep();

                if (this.currentStep === 5) {
                    this.populateReview();
                    document.getElementById('nextBtn').innerHTML = '<i class="fas fa-check"></i> Complete Registration';
                }
            }

            prevStep() {
                if (this.currentStep > 1) {
                    this.currentStep--;
                    this.updateUI();
                    this.animateStep();

                    if (this.currentStep < 5) {
                        document.getElementById('nextBtn').innerHTML = 'Next <i class="fas fa-arrow-right"></i>';
                    }
                }
            }

            updateUI() {
                // Update progress bar
                const progressFill = document.getElementById('progressFill');
                const progressPercent = ((this.currentStep - 1) / (this.totalSteps - 1)) * 100;
                progressFill.style.width = `${progressPercent}%`;

                // Update step indicators
                document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
                    const stepNum = index + 1;
                    indicator.classList.remove('active', 'completed');

                    if (stepNum < this.currentStep) {
                        indicator.classList.add('completed');
                        indicator.innerHTML = '<i class="fas fa-check"></i>';
                    } else if (stepNum === this.currentStep) {
                        indicator.classList.add('active');
                        indicator.innerHTML = stepNum;
                    } else {
                        indicator.innerHTML = stepNum;
                    }
                });

                // Update step labels
                document.querySelectorAll('.step-label').forEach((label, index) => {
                    label.classList.toggle('active', index + 1 === this.currentStep);
                });

                // Show/hide step content
                document.querySelectorAll('.step-content').forEach((content, index) => {
                    content.classList.toggle('active', index + 1 === this.currentStep);
                });

                // Update navigation buttons
                const prevBtn = document.getElementById('prevBtn');
                const nextBtn = document.getElementById('nextBtn');

                prevBtn.style.visibility = this.currentStep === 1 ? 'hidden' : 'visible';
                this.validateCurrentStep();
            }

            animateStep() {
                const activeContent = document.querySelector('.step-content.active');
                if (activeContent) {
                    activeContent.style.opacity = '0';
                    activeContent.style.transform = 'translateY(20px)';

                    setTimeout(() => {
                        activeContent.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                        activeContent.style.opacity = '1';
                        activeContent.style.transform = 'translateY(0)';
                    }, 100);
                }
            }

            populateReview() {
                const reviewContent = document.getElementById('reviewContent');
                const userTypeLabels = {
                    farmer: 'Coffee Farmer',
                    processor: 'Coffee Processor',
                    retailer: 'Coffee Retailer'
                };

                reviewContent.innerHTML = `
                    <div style="display: grid; gap: 2rem;">
                        <div style="background: rgba(111, 78, 55, 0.05); padding: 1.5rem; border-radius: 12px; border-left: 4px solid var(--coffee-medium);">
                            <h3 style="color: var(--coffee-dark); margin-bottom: 1rem; font-size: 1.2rem;">
                                <i class="fas fa-user-tag" style="margin-right: 0.5rem; color: var(--coffee-medium);"></i>
                                Registration Type
                            </h3>
                            <p style="color: var(--text-dark); font-size: 1.1rem; font-weight: 600;">
                                ${userTypeLabels[this.formData.user_type] || 'Not selected'}
                            </p>
                        </div>

                        <div style="background: rgba(111, 78, 55, 0.05); padding: 1.5rem; border-radius: 12px; border-left: 4px solid var(--coffee-medium);">
                            <h3 style="color: var(--coffee-dark); margin-bottom: 1rem; font-size: 1.2rem;">
                                <i class="fas fa-user" style="margin-right: 0.5rem; color: var(--coffee-medium);"></i>
                                Personal Information
                            </h3>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                                <div>
                                    <strong style="color: var(--coffee-dark);">Name:</strong><br>
                                    <span style="color: var(--text-dark);">${this.formData.name || 'Not provided'}</span>
                                </div>
                                <div>
                                    <strong style="color: var(--coffee-dark);">Email:</strong><br>
                                    <span style="color: var(--text-dark);">${this.formData.email || 'Not provided'}</span>
                                </div>
                                <div>
                                    <strong style="color: var(--coffee-dark);">Phone:</strong><br>
                                    <span style="color: var(--text-dark);">${this.formData.phone || 'Not provided'}</span>
                                </div>
                                <div>
                                    <strong style="color: var(--coffee-dark);">Address:</strong><br>
                                    <span style="color: var(--text-dark);">${this.formData.address || 'Not provided'}</span>
                                </div>
                            </div>
                        </div>

                        <div style="background: rgba(111, 78, 55, 0.05); padding: 1.5rem; border-radius: 12px; border-left: 4px solid var(--coffee-medium);">
                            <h3 style="color: var(--coffee-dark); margin-bottom: 1rem; font-size: 1.2rem;">
                                <i class="fas fa-building" style="margin-right: 0.5rem; color: var(--coffee-medium);"></i>
                                Company Information
                            </h3>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                                <div>
                                    <strong style="color: var(--coffee-dark);">Company Name:</strong><br>
                                    <span style="color: var(--text-dark);">${this.formData.company_name || 'Not provided'}</span>
                                </div>
                                <div>
                                    <strong style="color: var(--coffee-dark);">Company Email:</strong><br>
                                    <span style="color: var(--text-dark);">${this.formData.company_email || 'Not provided'}</span>
                                </div>
                                <div>
                                    <strong style="color: var(--coffee-dark);">Company Phone:</strong><br>
                                    <span style="color: var(--text-dark);">${this.formData.company_phone || 'Not provided'}</span>
                                </div>
                                <div>
                                    <strong style="color: var(--coffee-dark);">Registration Number:</strong><br>
                                    <span style="color: var(--text-dark);">${this.formData.registration_number || 'Not provided'}</span>
                                </div>
                            </div>
                            <div style="margin-top: 1rem;">
                                <strong style="color: var(--coffee-dark);">Company Address:</strong><br>
                                <span style="color: var(--text-dark);">${this.formData.company_address || 'Not provided'}</span>
                            </div>
                        </div>

                        <div style="background: rgba(40, 167, 69, 0.1); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(40, 167, 69, 0.2);">
                            <p style="color: var(--success); font-weight: 600; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-info-circle"></i>
                                By completing this registration, you agree to our Terms of Service and Privacy Policy.
                            </p>
                        </div>
                    </div>
                `;
            }

            async submitForm() {
                const nextBtn = document.getElementById('nextBtn');
                nextBtn.classList.add('loading');
                nextBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Account...';

                try {
                    // Simulate API call
                    await new Promise(resolve => setTimeout(resolve, 2000));

                    // Show success screen
                    document.querySelectorAll('.step-content').forEach(content => {
                        content.style.display = 'none';
                    });

                    document.getElementById('success').style.display = 'block';
                    document.querySelector('.step-navigation').style.display = 'none';

                    // Update progress to 100%
                    document.getElementById('progressFill').style.width = '100%';
                    document.querySelectorAll('.step-indicator').forEach(indicator => {
                        indicator.classList.remove('active');
                        indicator.classList.add('completed');
                        indicator.innerHTML = '<i class="fas fa-check"></i>';
                    });

                } catch (error) {
                    console.error('Registration failed:', error);
                    nextBtn.classList.remove('loading');
                    nextBtn.innerHTML = '<i class="fas fa-check"></i> Complete Registration';
                    alert('Registration failed. Please try again.');
                }
            }
        }

        // Initialize the onboarding flow
        document.addEventListener('DOMContentLoaded', () => {
            new OnboardingFlow();
        });

        // Add some nice entrance animations
        document.addEventListener('DOMContentLoaded', () => {
            const elements = document.querySelectorAll('.onboarding-header, .progress-container, .onboarding-card');
            elements.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';

                setTimeout(() => {
                    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 150);
            });
        });
    </script>
</body>

</html>
