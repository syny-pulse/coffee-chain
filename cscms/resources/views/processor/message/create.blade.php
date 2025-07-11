@extends('layouts.processor')

@section('title', 'New Message')

@section('content')
    <!-- Dashboard Header -->
    <div class="dashboard-header fade-in">
        <div class="dashboard-title">
            <i class="fas fa-envelope"></i>
            <div>
                <h1>Compose New Message</h1>
                <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">
                    Send a message to other companies in the coffee chain
                </p>
            </div>
        </div>
        <div class="dashboard-actions">
            <a href="{{ route('processor.message.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>
                Back to Messages
            </a>
        </div>
    </div>
    <!-- Alerts -->
    @if (session('success'))
        <div class="alert status-success auto-dismiss">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert status-error auto-dismiss">
            {{ session('error') }}
        </div>
    @endif
    @if (session('warning'))
        <div class="alert status-warning auto-dismiss">
            {{ session('warning') }}
        </div>
    @endif

    <!-- Message Form -->
    <div class="content-section fade-in">
        {{-- Display success message --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Display validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Display error message from controller --}}
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('processor.message.store') }}" method="POST" class="form-container">
            @csrf
            <!-- Hidden fields for sender information -->
            <input type="hidden" name="sender_company_id" value="{{ auth()->user()->company->company_id }}">
            <input type="hidden" name="sender_user_id" value="{{ auth()->id() }}">

            <div class="form-group">
                <label for="receiver_company_id">Recipient Company <span class="text-danger">*</span></label>
                <select id="receiver_company_id" name="receiver_company_id" class="form-control" required>
                    <option value="">Select Company</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->company_id }}" data-type="{{ $company->company_type }}"
                            {{ old('receiver_company_id') == $company->company_id ? 'selected' : '' }}>
                            {{ $company->company_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="subject">Subject (Optional)</label>
                <input type="text" id="subject" name="subject" class="form-control" maxlength="200"
                    value="{{ old('subject') }}" placeholder="Maximum 200 characters">
                <small class="text-muted">Characters remaining: <span id="subject-chars">200</span></small>
            </div>

            <div class="form-group">
                <label for="message_body">Message <span class="text-danger">*</span></label>
                <textarea id="message_body" name="message_body" class="form-control" rows="6" required
                    placeholder="Enter your message here...">{{ old('message_body') }}</textarea>
            </div>

            <div class="form-group">
                <label for="message_type">Message Type <span class="text-danger">*</span></label>
                <select id="message_type" name="message_type" class="form-control" required>
                    <option value="">Select Type</option>
                    <option value="general" {{ old('message_type') == 'general' ? 'selected' : '' }}>General</option>
                    <option value="order_inquiry" {{ old('message_type') == 'order_inquiry' ? 'selected' : '' }}>Order
                        Inquiry</option>
                    <option value="quality_feedback" {{ old('message_type') == 'quality_feedback' ? 'selected' : '' }}>
                        Quality Feedback</option>
                    <option value="delivery_update" {{ old('message_type') == 'delivery_update' ? 'selected' : '' }}>
                        Delivery Update</option>
                    <option value="system_notification"
                        {{ old('message_type') == 'system_notification' ? 'selected' : '' }}>System Notification</option>
                </select>
            </div>

            <div class="auth-buttons">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    Send Message
                </button>
                <a href="{{ route('processor.message.index') }}" class="btn btn-outline">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        // Handle company selection and user population
        document.getElementById('receiver_company_id').addEventListener('change', function() {
            const userSelect = document.getElementById('receiver_user_id');
            const selectedCompanyId = this.value;

            // Clear current options
            userSelect.innerHTML = '<option value="">Select User (Optional)</option>';

            // Populate users based on selected company
            @foreach ($users as $user)
                @if ($user->company)
                    if ('{{ $user->company->company_id }}' == selectedCompanyId) {
                        const option = document.createElement('option');
                        option.value = '{{ $user->id }}';
                        option.textContent = '{{ $user->name }}';
                        if ('{{ old('receiver_user_id') }}' == '{{ $user->id }}') {
                            option.selected = true;
                        }
                        userSelect.appendChild(option);
                    }
                @endif
            @endforeach
        });

        // Handle subject character count
        document.getElementById('subject').addEventListener('input', function() {
            const remaining = 200 - this.value.length;
            document.getElementById('subject-chars').textContent = remaining;
        });

        // Trigger initial character count
        document.getElementById('subject').dispatchEvent(new Event('input'));

        // Trigger initial user population if company is selected
        if (document.getElementById('receiver_company_id').value) {
            document.getElementById('receiver_company_id').dispatchEvent(new Event('change'));
        }
    </script>

    <style>
        /* Form Styles */
        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--coffee-dark);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid rgba(111, 78, 55, 0.2);
            border-radius: 8px;
            font-size: 0.9rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--coffee-medium);
            box-shadow: 0 0 0 3px rgba(111, 78, 55, 0.1);
        }

        .form-control::placeholder {
            color: var(--text-light);
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            justify-content: flex-start;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border: 1px solid;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success);
            border-color: rgba(40, 167, 69, 0.2);
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger);
            border-color: rgba(220, 53, 69, 0.2);
        }

        .text-danger {
            color: var(--danger);
        }

        .text-muted {
            color: var(--text-light);
            font-size: 0.85rem;
        }
    </style>
@endsection
