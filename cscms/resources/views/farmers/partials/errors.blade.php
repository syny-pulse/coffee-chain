@if ($errors->any())
    <ul class="error-list">
        @foreach ($errors->all() as $error)
            <li><i class="fas fa-exclamation-triangle"></i> {{ $error }}</li>
        @endforeach
    </ul>
@endif