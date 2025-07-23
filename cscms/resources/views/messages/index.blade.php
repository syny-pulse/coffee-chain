@php
    $role = isset($role) ? $role : (auth()->check() ? auth()->user()->user_type : null);
    if ($role === 'processor') {
        $layout = 'layouts.processor';
    } elseif ($role === 'retailer') {
        $layout = 'retailers.dashboard'; // fallback to dashboard layout for retailer
    } else {
        $layout = 'farmers.layouts.app';
    }
@endphp
@extends($layout)

@section('title', 'Communication')
@section('page-title', 'Communication Center')
@section('page-subtitle', 'Connect with other companies, send messages, and manage your business communications')

@section('page-actions')
    <button class="btn btn-primary" onclick="openNewMessageModal()">
        <i class="fas fa-plus"></i> New Message
    </button>
    <button class="btn btn-outline" onclick="refreshMessages()">
        <i class="fas fa-sync-alt"></i> Refresh
    </button>
@endsection
@section('content')
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card" id="stat-total" onclick="filterMessages('all')" style="cursor:pointer;">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    +5
                </div>
            </div>
            <div class="stat-value">{{ $inboxMessages->count() + $sentMessages->count() }}</div>
            <div class="stat-label">Total Messages</div>
        </div>
        <div class="stat-card" id="stat-read" onclick="filterMessages('read')" style="cursor:pointer;">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-envelope-open"></i>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    +2
                </div>
            </div>
            <div class="stat-value">{{ $inboxMessages->where('is_read', true)->count() }}</div>
            <div class="stat-label">Read Messages</div>
        </div>
        <div class="stat-card" id="stat-unread" onclick="filterMessages('unread')" style="cursor:pointer;">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-envelope-circle-check"></i>
                </div>
                <div class="stat-trend negative">
                    <i class="fas fa-arrow-down"></i>
                    -1
                </div>
            </div>
            <div class="stat-value">{{ $inboxMessages->where('is_read', false)->count() }}</div>
            <div class="stat-label">Unread Messages</div>
        </div>
        <div class="stat-card" id="stat-sent" onclick="filterMessages('sent')" style="cursor:pointer;">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    +1
                </div>
            </div>
            <div class="stat-value">{{ $sentMessages->count() }}</div>
            <div class="stat-label">Sent Messages</div>
        </div>
        <div class="stat-card" id="stat-recipients">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    +1
                </div>
            </div>
            <div class="stat-value">{{ $recipientCompanies->count() }}</div>
            <div class="stat-label">Active Recipients</div>
        </div>
    </div>
    <!-- Messages Section -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Recent Messages</h2>
            <div class="card-actions right-actions">
                <button class="btn btn-sm btn-outline" onclick="markAllAsRead()">
                    <i class="fas fa-check-double"></i> Mark All Read
                </button>
            </div>
        </div>
        <div class="message-tabs" style="margin-top: 1rem; display: flex; gap: 0.5rem;">
            <button class="btn btn-tab active" id="inboxTab" onclick="showMessagesTab('inbox')"><i class="fas fa-inbox"></i> Inbox</button>
            <button class="btn btn-tab" id="sentTab" onclick="showMessagesTab('sent')"><i class="fas fa-paper-plane"></i> Sent</button>
        </div>
        <div class="messages-container" id="inboxMessages">
            @forelse ($inboxMessages as $message)
                <div class="message-item {{ $message->is_read ? 'read' : 'unread' }}">
                    <div class="message-header">
                        <div class="message-sender">
                            <strong>{{ optional($message->senderCompany)->company_name ?? 'Unknown' }}</strong>
                            @if (!$message->is_read)
                                <span class="unread-badge"></span>
                            @endif
                        </div>
                        <div class="message-meta">
                            <span class="message-date">{{ $message->created_at->format('M d, Y H:i') }}</span>
                            <span class="message-type">{{ ucfirst($message->message_type) }}</span>
                        </div>
                    </div>
                    <div class="message-subject">
                        <strong>{{ $message->subject }}</strong>
                    </div>
                    <div class="message-content">
                        {{ Str::limit($message->message_body, 150) }}
                    </div>
                    <div class="message-actions">
                        <button class="btn btn-sm btn-outline" onclick="viewMessage({{ $message->message_id }})">
                            <i class="fas fa-eye"></i> View
                        </button>
                        <button class="btn btn-sm btn-outline" onclick="replyToMessage({{ $message->message_id }})">
                            <i class="fas fa-reply"></i> Reply
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-envelope-open"></i>
                    <h3>No Inbox Messages</h3>
                    <p>Your inbox is empty.</p>
                </div>
            @endforelse
        </div>
        <div class="messages-container" id="sentMessages" style="display:none;">
            @forelse ($sentMessages as $message)
                <div class="message-item read">
                    <div class="message-header">
                        <div class="message-sender">
                            <strong>To: {{ optional($message->receiverCompany)->company_name ?? 'Unknown' }}</strong>
                        </div>
                        <div class="message-meta">
                            <span class="message-date">{{ $message->created_at->format('M d, Y H:i') }}</span>
                            <span class="message-type">{{ ucfirst($message->message_type) }}</span>
                        </div>
                    </div>
                    <div class="message-subject">
                        <strong>{{ $message->subject }}</strong>
                    </div>
                    <div class="message-content">
                        {{ Str::limit($message->message_body, 150) }}
                    </div>
                    <div class="message-actions">
                        <button class="btn btn-sm btn-outline" onclick="viewMessage({{ $message->message_id }})">
                            <i class="fas fa-eye"></i> View
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-paper-plane"></i>
                    <h3>No Sent Messages</h3>
                    <p>You haven't sent any messages yet.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection 