@extends('layouts.processor')

@section('title', 'Messages')

@section('content')
<section class="section" id="messages">
    <div class="section-container">
        <!-- Breadcrumb -->
     <div class="dashboard-header fade-in">
        <div class="dashboard-title">
            <i class="fas fa-envelope"></i>
            <div>
                <h1>Communication Management</h1>
                <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">
                    Make Communication as of {{ now()->format('h:i A T, l, F d, Y') }}
                </p>
            </div>
        </div>
        <div class="dashboard-actions">
            <a href="{{ route('processor.message.create') }}" class="btn btn-success">
                <i class="fas fa-envelope"></i>
                Compose a new message
            </a>
        </div>
    </div>

        <div class="section-header">
            <h2>Messages</h2>
            <p>Communicate with farmers and retailers</p>
        </div>

        <div class="auth-buttons" style="margin-bottom: 2rem;">
            <a href="{{ route('processor.message.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                New Message
            </a>
        </div>

        <div class="features-grid">
            @forelse($messages as $message)
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-envelope{{ $message->is_read ? '' : '-open' }}"></i>
                    </div>
                    <h3>{{ $message->subject }}</h3>
                    <p>From: {{ $message->sender->company ? $message->sender->company->company_name : 'Unknown' }}</p>
                    <p>Date: {{ $message->created_at->format('d/m/Y') }}</p>
                    <p>Priority: <span class="status-badge status-{{ $message->priority }}">{{ ucfirst($message->priority) }}</span></p>
                    <a href="{{ route('processor.message.show', $message->message_id) }}" class="btn btn-outline">Read</a>
                </div>
            @empty
                <p>No messages found.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection