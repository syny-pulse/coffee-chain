@extends('layouts.processor')

@section('title', 'View Message')

@section('content')
<section class="section" id="messages">
    <div class="section-container">
        <div class="section-header">
            <h2>Message Details</h2>
        </div>

        <div class="feature-card">
            <h3>{{ $message->subject }}</h3>
            <p><strong>From:</strong> {{ $message->sender->company ? $message->sender->company->company_name : 'Unknown' }}</p>
            <p><strong>To:</strong> {{ $message->receiver->company ? $message->receiver->company->company_name : 'Unknown' }}</p>
            <p><strong>Date:</strong> {{ $message->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Priority:</strong> {{ ucfirst($message->priority) }}</p>
            <p><strong>Message:</strong></p>
            <p>{{ $message->message_body }}</p>
        </div>

        <div class="auth-buttons">
            <a href="{{ route('processor.message.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>
                Back to Messages
            </a>
        </div>
    </div>
</section>
@endsection