@if (session('success'))
    <p class="success-message"><i class="fas fa-check-circle"></i> {{ session('success') }}</p>
@endif
@if (session('error'))
    <p class="error-message"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</p>
@endif