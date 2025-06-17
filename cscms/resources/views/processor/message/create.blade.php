@extends('layouts.processor')

@section('title', 'New Message')

@section('content')
    <div class="content-section fade-in">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="{{ route('processor.dashboard') }}">Dashboard</a> / <a href="{{ route('processor.message.index') }}">Messages</a> / New Message
        </div>

        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-plus"></i>
                <span>Compose New Message</span>
            </div>
        </div>

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

        <form action="{{ route('processor.message.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="receiver_company_id">Recipient Company</label>
                <select id="receiver_company_id" name="receiver_company_id" class="form-control" required>
                    <option value="">Select Company</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->company_id }}" data-type="{{ $company->company_type }}">{{ $company->company_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="receiver_user_id">Recipient User (Optional)</label>
                <select id="receiver_user_id" name="receiver_user_id" class="form-control">
                    <option value="">Select User (Optional)</option>
                    <!-- Options populated by JavaScript -->
                </select>
            </div>

            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" class="form-control" maxlength="200" value="{{ old('subject') }}">
            </div>

            <div class="form-group">
                <label for="message_body">Message</label>
                <textarea id="message_body" name="message_body" class="form-control" rows="6" required>{{ old('message_body') }}</textarea>
            </div>

            <div class="form-group">
                <label for="message_type">Message Type</label>
                <select id="message_type" name="message_type" class="form-control" required>
                    <option value="general" {{ old('message_type') == 'general' ? 'selected' : '' }}>General</option>
                    <option value="order_inquiry" {{ old('message_type') == 'order_inquiry' ? 'selected' : '' }}>Order Inquiry</option>
                    <option value="quality_feedback" {{ old('message_type') == 'quality_feedback' ? 'selected' : '' }}>Quality Feedback</option>
                    <option value="delivery_update" {{ old('message_type') == 'delivery_update' ? 'selected' : '' }}>Delivery Update</option>
                    <option value="system_notification" {{ old('message_type') == 'system_notification' ? 'selected' : '' }}>System Notification</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-paper-plane"></i>
                    Send Message
                </button>
                <a href="{{ route('processor.message.index') }}" class="btn btn-primary">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    document.getElementById('receiver_company_id').addEventListener('change', function() {
        const userSelect = document.getElementById('receiver_user_id');
        const selectedCompanyId = this.value;
        
        // Clear current options
        userSelect.innerHTML = '<option value="">Select User (Optional)</option>';
        
        // Populate users based on selected company
        @foreach($users as $user)
            @if($user->company)
                if ('{{ $user->company->company_id }}' == selectedCompanyId) {
                    const option = document.createElement('option');
                    option.value = '{{ $user->id }}';
                    option.textContent = '{{ $user->name }}';
                    userSelect.appendChild(option);
                }
            @endif
        @endforeach
    });
</script>
@endsection