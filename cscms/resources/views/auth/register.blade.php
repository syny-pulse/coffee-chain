<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Join Coffee Supply Chain - Onboarding</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Include jsPDF library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
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
            min-width: 60px;
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

        /* Review Section Grid Text Wrapping */
        .review-grid span {
            word-break: break-word;
            overflow-wrap: break-word;
            display: inline-block;
            max-width: 100%;
        }

        /* Optional: Enhance readability for long text */
        .review-grid strong {
            display: block;
            margin-bottom: 0.25rem;
        }

        /* Responsive adjustment for smaller screens */
        @media (max-width: 768px) {
            .review-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .review-grid>div {
                padding: 0.5rem 0;
                border-bottom: 1px solid rgba(111, 78, 55, 0.1);
            }

            .review-grid>div:last-child {
                border-bottom: none;
                /* Remove border from last item */
            }
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

        /* File Upload Styles */
        .file-upload {
            position: relative;
            padding: 1rem;
            border: 2px dashed rgba(111, 78, 55, 0.3);
            border-radius: 12px;
            text-align: center;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
        }

        .file-upload:hover {
            border-color: var(--coffee-medium);
            background: rgba(111, 78, 55, 0.05);
        }

        .file-upload input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload-label {
            color: var(--coffee-medium);
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .file-upload-text {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        /* Navigation Buttons */
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

        /* Download Button */
        .download-button {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 2rem;
            background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .download-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(111, 78, 55, 0.4);
        }

        .download-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
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

        /* Navigation Container */
        .step-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 3rem;
            background: rgba(255, 255, 255, 0.95);
            border-top: 1px solid rgba(111, 78, 55, 0.1);
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
                min-width: 50px;
            }

            .download-button {
                width: 100%;
                justify-content: center;
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
                <div class="step-indicator" data-step="6">6</div>
            </div>
            <div class="step-labels">
                <div class="step-label active">Role</div>
                <div class="step-label">Personal</div>
                <div class="step-label">Security</div>
                <div class="step-label">Company</div>
                <div class="step-label">Upload PDF</div>
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

            <!-- Step 4: Company Information with PDF Download -->
            <div class="step-content" id="step4">
                <div class="step-title">
                    <i class="fas fa-building"></i>
                    Company Details
                </div>
                <p class="step-description">
                    Tell us about your business. This information helps us verify your company and enables
                    business-to-business transactions. Download the form as a PDF after filling it out.
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

                <div class="step-title">
                    <i class="fas fa-building"></i>
                    Financial Stability
                </div>

                <p class="step-description">
                    Please provide your financial stability information. This helps us assess your business's
                    reliability and creditworthiness.
                </p>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="annual_revenue" class="form-label">Annual Revenue (UGX, last year) *</label>
                        <input type="text" id="annual_revenue" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="years_in_business" class="form-label">Years in Business( Atleast 1 year) *</label>
                        <input type="number" id="years_in_business" class="form-input" required min="1">
                    </div>

                    <div class="form-group">
                        <label for="business_bank" class="form-label">Business Bank Account *</label>
                        <input type="text" id="business_bank" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="debt_to_equity_ratio" class="form-label">Debt-to-Equity Ratio *</label>
                        <input type="text" id="debt_to_equity_ratio" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="cash_flow_summary_year_1" id="label_year_1" class="form-label">Cash Flow Summary
                            - Year 1 *</label>
                        <input type="text" id="cash_flow_summary_year_1" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="cash_flow_summary_year_2" id="label_year_2" class="form-label">Cash Flow Summary
                            - Year 2</label>
                        <input type="text" id="cash_flow_summary_year_2" class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="credit_score" class="form-label">Credit Score *</label>
                        <input type="text" id="credit_score" class="form-input" required>
                    </div>
                </div>

                <div class="step-title">
                    <i class="fas fa-building"></i>
                    Reputation
                </div>

                <p class="step-description">
                    Please provide your reputation information. This helps us assess your business's standing in the
                    industry and its relationship with customers and partners.
                </p>
                <div class="form-grid">
                    <!-- Reference 1 -->
                    <div class="form-group">
                        <label for="ref1_name" class="form-label">Reference 1 - Name *</label>
                        <input type="text" id="ref1_name" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="ref1_contact" class="form-label">Reference 1 - Contact *</label>
                        <input type="text" id="ref1_contact" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="ref1_relationship" class="form-label">Reference 1 - Relationship *</label>
                        <input type="text" id="ref1_relationship" class="form-input" required>
                    </div>

                    <!-- Reference 2 -->
                    <div class="form-group">
                        <label for="ref2_name" class="form-label">Reference 2 - Name</label>
                        <input type="text" id="ref2_name" class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="ref2_contact" class="form-label">Reference 2 - Contact </label>
                        <input type="text" id="ref2_contact" class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="ref2_relationship" class="form-label">Reference 2 - Relationship </label>
                        <input type="text" id="ref2_relationship" class="form-input">
                    </div>

                    <!-- Reference 3 -->
                    <div class="form-group">
                        <label for="ref3_name" class="form-label">Reference 3 - Name </label>
                        <input type="text" id="ref3_name" class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="ref3_contact" class="form-label">Reference 3 - Contact </label>
                        <input type="text" id="ref3_contact" class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="ref3_relationship" class="form-label">Reference 3 - Relationship </label>
                        <input type="text" id="ref3_relationship" class="form-input">
                    </div>

                    <!-- Legal Disputes -->
                    <div class="form-group">
                        <label class="form-label">Any Legal Disputes (last 5 years)? *</label>
                        <div>
                            <label><input type="radio" name="legal_disputes" value="yes" required> Yes</label>
                            <label style="margin-left: 1rem;"><input type="radio" name="legal_disputes"
                                    value="no" required> No</label>
                        </div>
                    </div>

                    <div class="form-group" id="legal_dispute_details_group" style="display: none;">
                        <label for="legal_dispute_details" class="form-label">If Yes, provide details:</label>
                        <textarea id="legal_dispute_details" class="form-textarea"></textarea>
                    </div>
                </div>

                <div class="step-title">
                    <i class="fas fa-building"></i>
                    Compliance
                </div>

                <p class="step-description">
                    Please provide your compliance information. This helps us ensure that your business meets all
                    necessary legal and regulatory requirements.
                </p>
                <div class="form-grid">
                    <!-- Certification 1 -->
                    <div class="form-group">
                        <label for="cert1_type" class="form-label">Certification 1 - Type (e.g., Fair Trade, Organic)
                            *</label>
                        <input type="text" id="cert1_type" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="cert1_issuer" class="form-label">Certification 1 - Issuer *</label>
                        <input type="text" id="cert1_issuer" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="cert1_expiry" class="form-label">Certification 1 - Expiry Date *</label>
                        <input type="date" id="cert1_expiry" class="form-input" required>
                    </div>

                    <!-- Certification 2 -->
                    <div class="form-group">
                        <label for="cert2_type" class="form-label">Certification 2 - Type </label>
                        <input type="text" id="cert2_type" class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="cert2_issuer" class="form-label">Certification 2 - Issuer </label>
                        <input type="text" id="cert2_issuer" class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="cert2_expiry" class="form-label">Certification 2 - Expiry Date </label>
                        <input type="date" id="cert2_expiry" class="form-input">
                    </div>

                    <!-- Compliance with Environmental Regulations -->
                    <div class="form-group">
                        <label class="form-label">Compliance with Environmental Regulations *</label>
                        <div>
                            <label><input type="radio" name="env_compliance" value="yes" required> Yes</label>
                            <label style="margin-left: 1rem;"><input type="radio" name="env_compliance"
                                    value="no" required> No</label>
                        </div>
                    </div>

                    <div class="form-group" id="env_compliance_details_group" style="display: none;">
                        <label for="env_compliance_details" class="form-label">If No, provide details:</label>
                        <textarea id="env_compliance_details" class="form-textarea"></textarea>
                    </div>
                </div>

                <button type="button" class="download-button" id="downloadPdfBtn">
                    <i class="fas fa-download"></i>
                    Download Form as PDF
                </button>
            </div>

            <!-- Step 5: PDF Upload -->
            <div class="step-content" id="step5">
                <div class="step-title">
                    <i class="fas fa-file-upload"></i>
                    Upload PDF
                </div>
                <p class="step-description">
                    Upload the PDF you downloaded in the previous step. Ensure it is the correct file before proceeding.
                </p>

                <div class="form-group">
                    <div class="file-upload">
                        <input type="file" id="pdfUpload" accept=".pdf" required>
                        <div class="file-upload-label">
                            <i class="fas fa-upload"></i>
                            Choose PDF File
                        </div>
                        <p class="file-upload-text" id="fileName">No file selected</p>
                    </div>
                </div>
            </div>

            <!-- Step 6: Review & Submit -->
            <div class="step-content" id="step6">
                <div class="step-title">
                    <i class="fas fa-check-circle"></i>
                    Review Your Information
                </div>
                <p class="step-description">
                    Please review all the information you've provided, including the uploaded PDF. Make sure everything
                    is correct before submitting your registration.
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
                        Your application has been submitted successfully.
                        You will receive an email notification once your application has been approved or if further
                        details are required. Thank you for your patience.
                    </p>

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
                this.totalSteps = 6;
                this.formData = {};
                this.uploadedFile = null;
                this.maxSizeInBytes = 10240; // 10KB for PDF validation
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
                        // Remove 'selected' class from all cards
                        document.querySelectorAll('.user-type-card').forEach(c => c.classList.remove(
                            'selected'));
                        // Add 'selected' class to clicked card
                        card.classList.add('selected');
                        // Set user_type in formData
                        this.formData.user_type = card.dataset.type;
                        // Validate step to enable Next button
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

                // Radio buttons for legal disputes and environmental compliance
                document.querySelectorAll('input[name="legal_disputes"]').forEach(radio => {
                    radio.addEventListener('change', () => {
                        this.formData.legal_disputes = radio.value;
                        const detailsInput = document.getElementById('legal_dispute_details');
                        const detailsGroup = document.getElementById('legal_dispute_details_group');
                        if (radio.value === 'yes') {
                            detailsGroup.style.display = 'block';
                            detailsInput.setAttribute('required', 'required');
                        } else {
                            detailsGroup.style.display = 'none';
                            detailsInput.removeAttribute('required');
                            this.formData.legal_dispute_details = ''; // Clear details if 'no'
                        }
                        this.validateCurrentStep();
                    });
                });

                document.querySelectorAll('input[name="env_compliance"]').forEach(radio => {
                    radio.addEventListener('change', () => {
                        this.formData.env_compliance = radio.value;
                        const detailsInput = document.getElementById('env_compliance_details');
                        const detailsGroup = document.getElementById('env_compliance_details_group');
                        if (radio.value === 'no') {
                            detailsGroup.style.display = 'block';
                            detailsInput.setAttribute('required', 'required');
                        } else {
                            detailsGroup.style.display = 'none';
                            detailsInput.removeAttribute('required');
                            this.formData.env_compliance_details = ''; // Clear details if 'yes'
                        }
                        this.validateCurrentStep();
                    });
                });

                // Password confirmation
                document.getElementById('password_confirmation').addEventListener('input', () => {
                    this.validatePasswordMatch();
                    this.validateCurrentStep();
                });

                // PDF Download
                document.getElementById('downloadPdfBtn').addEventListener('click', () => this.downloadPDF());

                // File Upload
                const fileInput = document.getElementById('pdfUpload');
                fileInput.addEventListener('change', (e) => {
                    const file = e.target.files[0];
                    const fileNameDisplay = document.getElementById('fileName');
                    if (file) {
                        if (file.type !== 'application/pdf') {
                            this.uploadedFile = null;
                            fileNameDisplay.textContent = 'Please select a valid PDF file';
                            fileNameDisplay.style.color = 'var(--error)';
                            fileInput.value = ''; // Clear input
                        } else if (file.size > this.maxSizeInBytes) {
                            this.uploadedFile = null;
                            fileNameDisplay.textContent = 'File size exceeds 10KB';
                            fileNameDisplay.style.color = 'var(--error)';
                            fileInput.value = ''; // Clear input
                        } else {
                            this.uploadedFile = file;
                            fileNameDisplay.textContent = file.name;
                            fileNameDisplay.style.color = 'var(--success)';
                        }
                    } else {
                        this.uploadedFile = null;
                        fileNameDisplay.textContent = 'No file selected';
                        fileNameDisplay.style.color = 'var(--text-light)';
                    }
                    this.validateCurrentStep();
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
                        isValid = !!this.formData.user_type; // Check if user_type is set
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
                            this.formData.company_address &&
                            this.formData.annual_revenue && this.formData.years_in_business &&
                            this.formData.business_bank && this.formData.debt_to_equity_ratio &&
                            this.formData.cash_flow_summary_year_1 &&
                            this.formData.credit_score &&
                            this.formData.ref1_name && this.formData.ref1_contact && this.formData.ref1_relationship &&
                            this.formData.legal_disputes &&
                            (this.formData.legal_disputes !== 'yes' || this.formData.legal_dispute_details) &&
                            this.formData.cert1_type && this.formData.cert1_issuer && this.formData.cert1_expiry &&
                            this.formData.env_compliance &&
                            (this.formData.env_compliance !== 'no' || this.formData.env_compliance_details);
                        break;
                    case 5:
                        isValid = !!this.uploadedFile && this.uploadedFile.size <= this.maxSizeInBytes;
                        break;
                    case 6:
                        isValid = true; // Review step is always valid
                        break;
                }

                const nextBtn = document.getElementById('nextBtn');
                nextBtn.disabled = !isValid;
                return isValid;
            }

            downloadPDF() {
                const {
                    jsPDF
                } = window.jspdf;
                const doc = new jsPDF();
                doc.setFontSize(16);
                doc.text('Coffee Supply Chain Registration Form', 20, 20);
                doc.setFontSize(12);

                let y = 40;
                const addSection = (title, fields, startY) => {
                    doc.setFontSize(14);
                    doc.text(title, 20, startY);
                    doc.setFontSize(12);
                    y = startY + 10;
                    for (const [label, value] of Object.entries(fields)) {
                        doc.text(`${label}: ${value || 'Not provided'}`, 20, y);
                        y += 10;
                        if (y > 280) {
                            doc.addPage();
                            y = 20;
                        }
                    }
                    return y;
                };

                // Collect form data for PDF
                const fields = {
                    'Company Name': this.formData.company_name,
                    'Company Email': this.formData.company_email,
                    'Company Phone': this.formData.company_phone,
                    'Registration Number': this.formData.registration_number,
                    'Company Address': this.formData.company_address,
                    'Annual Revenue (UGX)': this.formData.annual_revenue,
                    'Years in Business': this.formData.years_in_business,
                    'Business Bank Account': this.formData.business_bank,
                    'Debt-to-Equity Ratio': this.formData.debt_to_equity_ratio,
                    'Cash Flow Summary - Year 1': this.formData.cash_flow_summary_year_1,
                    'Cash Flow Summary - Year 2': this.formData.cash_flow_summary_year_2,
                    'Credit Score': this.formData.credit_score,
                    'Reference 1 - Name': this.formData.ref1_name,
                    'Reference 1 - Contact': this.formData.ref1_contact,
                    'Reference 1 - Relationship': this.formData.ref1_relationship,
                    'Reference 2 - Name': this.formData.ref2_name,
                    'Reference 2 - Contact': this.formData.ref2_contact,
                    'Reference 2 - Relationship': this.formData.ref2_relationship,
                    'Reference 3 - Name': this.formData.ref3_name,
                    'Reference 3 - Contact': this.formData.ref3_contact,
                    'Reference 3 - Relationship': this.formData.ref3_relationship,
                    'Legal Disputes': this.formData.legal_disputes,
                    'Legal Dispute Details': this.formData.legal_dispute_details,
                    'Certification 1 - Type': this.formData.cert1_type,
                    'Certification 1 - Issuer': this.formData.cert1_issuer,
                    'Certification 1 - Expiry': this.formData.cert1_expiry,
                    'Certification 2 - Type': this.formData.cert2_type,
                    'Certification 2 - Issuer': this.formData.cert2_issuer,
                    'Certification 2 - Expiry': this.formData.cert2_expiry,
                    'Environmental Compliance': this.formData.env_compliance,
                    'Environmental Compliance Details': this.formData.env_compliance_details
                };

                y = addSection('Company Information', fields, 30);
                doc.save('coffee_supply_chain_form.pdf');
            }

            nextStep() {
                if (!this.validateCurrentStep()) return;

                if (this.currentStep === 6) {
                    this.submitForm();
                    return;
                }

                this.currentStep++;
                this.updateUI();
                this.animateStep();

                if (this.currentStep === 6) {
                    this.populateReview();
                    document.getElementById('nextBtn').innerHTML = '<i class="fas fa-check"></i> Complete Registration';
                } else {
                    document.getElementById('nextBtn').innerHTML = 'Next <i class="fas fa-arrow-right"></i>';
                }
            }

            prevStep() {
                if (this.currentStep > 1) {
                    this.currentStep--;
                    this.updateUI();
                    this.animateStep();

                    if (this.currentStep < 6) {
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
                        <div class="review-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
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
                        <div class="review-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
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
                        <div style="margin-top: 1rem;">
                            <strong style="color: var(--coffee-dark);">Financial Information:</strong><br>
                            <span style="color: var(--text-dark);">
                                Annual Revenue: ${this.formData.annual_revenue || 'Not provided'}<br>
                                Years in Business: ${this.formData.years_in_business || 'Not provided'}<br>
                                Business Bank Account: ${this.formData.business_bank || 'Not provided'}<br>
                                Debt-to-Equity Ratio: ${this.formData.debt_to_equity_ratio || 'Not provided'}<br>
                                Cash Flow Summary Year 1: ${this.formData.cash_flow_summary_year_1 || 'Not provided'}<br>
                                Cash Flow Summary Year 2: ${this.formData.cash_flow_summary_year_2 || 'Not provided'}<br>
                                Credit Score: ${this.formData.credit_score || 'Not provided'}
                            </span>
                        </div>
                        <div style="margin-top: 1rem;">
                            <strong style="color: var(--coffee-dark);">Reputation:</strong><br>
                            <span style="color: var(--text-dark);">
                                Reference 1: ${this.formData.ref1_name || 'Not provided'} (${this.formData.ref1_contact || 'Not provided'}, ${this.formData.ref1_relationship || 'Not provided'})<br>
                                Reference 2: ${this.formData.ref2_name || 'Not provided'} (${this.formData.ref2_contact || 'Not provided'}, ${this.formData.ref2_relationship || 'Not provided'})<br>
                                Reference 3: ${this.formData.ref3_name || 'Not provided'} (${this.formData.ref3_contact || 'Not provided'}, ${this.formData.ref3_relationship || 'Not provided'})<br>
                                Legal Disputes: ${this.formData.legal_disputes || 'Not provided'}<br>
                                ${this.formData.legal_disputes === 'yes' ? `Details: ${this.formData.legal_dispute_details || 'Not provided'}` : ''}
                            </span>
                        </div>
                        <div style="margin-top: 1rem;">
                            <strong style="color: var(--coffee-dark);">Compliance:</strong><br>
                            <span style="color: var(--text-dark);">
                                Certification 1: ${this.formData.cert1_type || 'Not provided'} (${this.formData.cert1_issuer || 'Not provided'}, Expires: ${this.formData.cert1_expiry || 'Not provided'})<br>
                                Certification 2: ${this.formData.cert2_type || 'Not provided'} (${this.formData.cert2_issuer || 'Not provided'}, Expires: ${this.formData.cert2_expiry || 'Not provided'})<br>
                                Environmental Compliance: ${this.formData.env_compliance || 'Not provided'}<br>
                                ${this.formData.env_compliance === 'no' ? `Details: ${this.formData.env_compliance_details || 'Not provided'}` : ''}
                            </span>
                        </div>
                    </div>

                    <div style="background: rgba(111, 78, 55, 0.05); padding: 1.5rem; border-radius: 12px; border-left: 4px solid var(--coffee-medium);">
                        <h3 style="color: var(--coffee-dark); margin-bottom: 1rem; font-size: 1.2rem;">
                            <i class="fas fa-file-upload" style="margin-right: 0.5rem; color: var(--coffee-medium);"></i>
                            Uploaded PDF
                        </h3>
                        <p style="color: var(--text-dark); font-size: 1.1rem; font-weight: 600;">
                            ${this.uploadedFile ? this.uploadedFile.name : 'No file uploaded'}
                        </p>
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
                nextBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting application...';

                try {
                    // Define fields to send
                    const fieldsToSend = [
                        'name', 'email', 'password', 'password_confirmation', 'phone', 'address', 'user_type',
                        'company_name', 'company_email', 'company_phone', 'registration_number', 'company_address'
                    ];

                    // Create FormData object
                    const formData = new FormData();
                    // Add selected form fields
                    fieldsToSend.forEach(field => {
                        if (this.formData[field] !== undefined) {
                            formData.append(field, this.formData[field]);
                        }
                    });
                    // Add PDF file
                    if (this.uploadedFile) {
                        formData.append('pdf', this.uploadedFile);
                    }

                    // Send request to Laravel
                    const response = await fetch('/register', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                        },
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        if (response.status === 422) {
                            const errors = result.errors;
                            const errorMessages = Object.values(errors).flat().join('\n');
                            throw new Error(errorMessages);
                        }
                        throw new Error(result.message || 'Registration failed');
                    }

                    // Show success screen
                    document.querySelectorAll('.step-content').forEach(content => {
                        content.style.display = 'none';
                    });
                    document.getElementById('success').style.display = 'block';
                    document.querySelector('.step-navigation').style.display = 'none';
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
                    alert(error.message || 'Registration failed. Please try again.');
                }
            }
        }

        // Initialize the onboarding flow
        document.addEventListener('DOMContentLoaded', () => {
            new OnboardingFlow();
        });

        // Add entrance animations
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

        // Legal disputes radio button handling
        document.addEventListener('DOMContentLoaded', function() {
            const radios = document.getElementsByName('legal_disputes');
            const detailsGroup = document.getElementById('legal_dispute_details_group');

            radios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'yes') {
                        detailsGroup.style.display = 'block';
                        document.getElementById('legal_dispute_details').setAttribute('required',
                            'required');
                    } else {
                        detailsGroup.style.display = 'none';
                        document.getElementById('legal_dispute_details').removeAttribute(
                            'required');
                    }
                });
            });
        });

        // Environmental compliance radio button handling
        document.addEventListener('DOMContentLoaded', function() {
            const envRadios = document.getElementsByName('env_compliance');
            const envDetailsGroup = document.getElementById('env_compliance_details_group');
            const envDetailsInput = document.getElementById('env_compliance_details');

            envRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'no') {
                        envDetailsGroup.style.display = 'block';
                        envDetailsInput.setAttribute('required', 'required');
                    } else {
                        envDetailsGroup.style.display = 'none';
                        envDetailsInput.removeAttribute('required');
                    }
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const currentYear = new Date().getFullYear();
            const year1 = currentYear - 1;
            const year2 = currentYear - 2;

            document.getElementById("label_year_1").textContent = `Cash Flow Summary - ${year1} *`;
            document.getElementById("label_year_2").textContent = `Cash Flow Summary - ${year2}`;
        });
    </script>
</body>

</html>
