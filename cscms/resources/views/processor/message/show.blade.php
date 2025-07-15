@extends('layouts.processor')

@section('title', 'View Message')

@section('content')
    <!-- Alerts -->
    @if (session('success'))
        <div class="alert status-success auto-dismiss fade-in">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert status-error auto-dismiss fade-in">
            {{ session('error') }}
        </div>
    @endif
    @if (session('warning'))
        <div class="alert status-warning auto-dismiss fade-in">
            {{ session('warning') }}
        </div>
    @endif

    <section class="content-section fade-in">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-envelope"></i>
                <span>Message Details</span>
            </div>
        </div>

        <div class="feature-card">
            <h3>{{ $message->subject }}</h3>
            <p><strong>From:</strong> {{ $message->sender->company ? $message->sender->company->company_name : 'Unknown' }}
            </p>
            <p><strong>To:</strong>
                {{ $message->receiver->company ? $message->receiver->company->company_name : 'Unknown' }}</p>
            <p><strong>Date:</strong> {{ $message->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Priority:</strong> {{ ucfirst($message->priority) }}</p>
            <p><strong>Message:</strong></p>
            @foreach (Str::of($message->message_body)->explode("\n")->filter()->all() as $paragraph)
                <p style="margin-bottom: 1rem;">{{ $paragraph }}</p>
            @endforeach
        </div>

        <div class="auth-buttons">
            <a href="{{ route('processor.message.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>
                Back to Messages
            </a>
        </div>
    </section>
@endsection
