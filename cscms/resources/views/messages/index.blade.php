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

@section('head')
    <link href="{{ asset('css/messages.css') }}" rel="stylesheet">
@endsection

@section('page-actions')
    <button class="btn btn-primary" id="newMessageBtn">
        <i class="fas fa-plus"></i> New Message
    </button>
    <button class="btn btn-outline" id="refreshMessagesBtn">
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
                <button class="btn btn-sm btn-outline" id="markAllReadBtn">
                    <i class="fas fa-check-double"></i> Mark All Read
                </button>
            </div>
        </div>
        <div class="message-tabs" style="margin-top: 1rem; display: flex; gap: 0.5rem;">
            <button class="btn btn-tab active" id="inboxTab">
                <i class="fas fa-inbox"></i> Inbox
            </button>
            <button class="btn btn-tab" id="sentTab">
                <i class="fas fa-paper-plane"></i> Sent
            </button>
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
                        <button class="btn btn-sm btn-outline view-message-btn" data-message-id="{{ $message->message_id }}">
                            <i class="fas fa-eye"></i> View
                        </button>
                        <button class="btn btn-sm btn-outline reply-message-btn" data-message-id="{{ $message->message_id }}">
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
    <!-- New Message Modal -->
    <div id="newMessageModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-paper-plane"></i> Send New Message</h3>
                <button class="modal-close" id="closeNewMessageModalBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('messages.send') }}" method="POST" class="form-container">
                @csrf
                <div class="form-group">
                    <label for="recipient_company_id" class="form-label">Select Recipient</label>
                    <select name="recipient_company_id" id="recipient_company_id" class="form-control" required>
                        <option value="">Choose a recipient...</option>
                        @foreach ($recipientCompanies as $recipient)
                            <option value="{{ $recipient->company_id }}">{{ $recipient->company_name }} ({{ ucfirst($recipient->company_type) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="content" class="form-label">Message</label>
                    <textarea name="content" id="content" class="form-control" rows="6" placeholder="Type your message here..." required></textarea>
                    <div class="form-text">Maximum 1000 characters</div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-outline" id="cancelNewMessageBtn">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- View Message Modal -->
    <div id="viewMessageModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-eye"></i> View Message</h3>
                <button class="modal-close" id="closeViewMessageModalBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="viewMessageBody">
                <!-- Message details will be loaded here -->
                <div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>
            </div>
        </div>
    </div>
    <!-- Reply Message Modal -->
    <div id="replyMessageModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-reply"></i> Reply to Message</h3>
                <button class="modal-close" id="closeReplyMessageModalBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="replyMessageBody">
                <!-- Reply form will be loaded here -->
                <div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
const csrfToken = '{{ csrf_token() }}';
const messagesSendUrl = '{{ route('messages.send') }}';

// Modal functions
function openNewMessageModal() {
    document.getElementById('newMessageModal').style.display = 'flex';
}
function closeNewMessageModal() {
    document.getElementById('newMessageModal').style.display = 'none';
}
function openViewMessageModal() {
    document.getElementById('viewMessageModal').style.display = 'flex';
}
function closeViewMessageModal() {
    document.getElementById('viewMessageModal').style.display = 'none';
}
function openReplyMessageModal() {
    document.getElementById('replyMessageModal').style.display = 'flex';
}
function closeReplyMessageModal() {
    document.getElementById('replyMessageModal').style.display = 'none';
}
function showMessagesTab(tab) {
    document.getElementById('inboxMessages').style.display = 'none';
    document.getElementById('sentMessages').style.display = 'none';
    document.querySelectorAll('.btn-tab').forEach(btn => btn.classList.remove('active'));
    if (tab === 'inbox') {
        document.getElementById('inboxMessages').style.display = 'block';
        document.getElementById('inboxTab').classList.add('active');
    } else {
        document.getElementById('sentMessages').style.display = 'block';
        document.getElementById('sentTab').classList.add('active');
    }
}

function viewMessage(messageId) {
    openViewMessageModal();
    const body = document.getElementById('viewMessageBody');
    body.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
    fetch(`/messages/${messageId}`)
        .then(response => response.json())
        .then(data => {
            const message = data.message;
            body.innerHTML = `
                <div class="message-header">
                    <div class="message-sender">
                        <strong>${message.sender_company_name || 'Unknown'}</strong>
                        ${!message.is_read ? '<span class="unread-badge"></span>' : ''}
                    </div>
                    <div class="message-meta">
                        <span class="message-date">${message.created_at}</span>
                        <span class="message-type">${message.message_type}</span>
                    </div>
                </div>
                <div class="message-subject">
                    <strong>${message.subject}</strong>
                </div>
                <div class="message-content">
                    ${message.message_body}
                </div>
                <div class="message-actions">
                    <button class="btn btn-sm btn-outline" onclick="closeViewMessageModal()">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
            `;
        });
}

function replyToMessage(messageId) {
    openReplyMessageModal();
    const body = document.getElementById('replyMessageBody');
    body.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
    fetch(`/messages/${messageId}/reply`)
        .then(response => response.json())
        .then(data => {
            const recipient = data.recipient;
            const original = data.original_message;
            body.innerHTML = `
                <form action="${messagesSendUrl}" method="POST" class="form-container">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="recipient_company_id" value="${recipient.company_id}">
                    <div class="form-group">
                        <label class="form-label">To</label>
                        <input type="text" class="form-control" value="${recipient.company_name}" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Subject</label>
                        <input type="text" name="subject" class="form-control" value="Re: ${original.subject || ''}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Message</label>
                        <textarea name="content" class="form-control" rows="6" placeholder="Type your reply here..." required></textarea>
                        <div class="form-text">Maximum 1000 characters</div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-outline" onclick="closeReplyMessageModal()"><i class="fas fa-times"></i> Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send Reply</button>
                    </div>
                </form>
            `;
        });
}

function markAllAsRead() {
    if (confirm('Are you sure you want to mark all messages as read?')) {
        fetch('{{ route('messages.mark-all-read') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                _token: csrfToken,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('All messages marked as read!');
                refreshMessages();
            } else {
                alert('Failed to mark messages as read: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error marking messages as read:', error);
            alert('An error occurred while marking messages as read.');
        });
    }
}

function refreshMessages() {
    location.reload();
}

function filterMessages(type) {
    // Show inbox tab by default for all except 'sent'
    if (type === 'sent') {
        showMessagesTab('sent');
        return;
    } else {
        showMessagesTab('inbox');
    }
    const all = document.querySelectorAll('#inboxMessages .message-item');
    all.forEach(item => {
        item.style.display = 'flex';
    });
    if (type === 'read') {
        all.forEach(item => {
            if (!item.classList.contains('read')) item.style.display = 'none';
        });
    } else if (type === 'unread') {
        all.forEach(item => {
            if (!item.classList.contains('unread')) item.style.display = 'none';
        });
    }
}

// Event delegation for message actions
window.addEventListener('DOMContentLoaded', function() {
    // New Message
    const newMsgBtn = document.getElementById('newMessageBtn');
    if (newMsgBtn) newMsgBtn.onclick = openNewMessageModal;
    // Refresh
    const refreshBtn = document.getElementById('refreshMessagesBtn');
    if (refreshBtn) refreshBtn.onclick = refreshMessages;
    // Inbox/Sent tabs
    const inboxTab = document.getElementById('inboxTab');
    const sentTab = document.getElementById('sentTab');
    if (inboxTab) inboxTab.onclick = function() { showMessagesTab('inbox'); };
    if (sentTab) sentTab.onclick = function() { showMessagesTab('sent'); };
    // View/Reply buttons
    document.body.addEventListener('click', function(e) {
        if (e.target.closest('.view-message-btn')) {
            const btn = e.target.closest('.view-message-btn');
            const id = btn.getAttribute('data-message-id');
            viewMessage(id);
        }
        if (e.target.closest('.reply-message-btn')) {
            const btn = e.target.closest('.reply-message-btn');
            const id = btn.getAttribute('data-message-id');
            replyToMessage(id);
        }
    });
    const markAllReadBtn = document.getElementById('markAllReadBtn');
    if (markAllReadBtn) markAllReadBtn.onclick = markAllAsRead;
    // New Message Modal Cancel Button
    const cancelNewMessageBtn = document.getElementById('cancelNewMessageBtn');
    if (cancelNewMessageBtn) cancelNewMessageBtn.onclick = closeNewMessageModal;
    const closeNewMessageModalBtn = document.getElementById('closeNewMessageModalBtn');
    if (closeNewMessageModalBtn) closeNewMessageModalBtn.onclick = closeNewMessageModal;
    // View Message Modal Close Button
    const closeViewMessageModalBtn = document.getElementById('closeViewMessageModalBtn');
    if (closeViewMessageModalBtn) closeViewMessageModalBtn.onclick = closeViewMessageModal;
    // Reply Message Modal Close Button
    const closeReplyMessageModalBtn = document.getElementById('closeReplyMessageModalBtn');
    if (closeReplyMessageModalBtn) closeReplyMessageModalBtn.onclick = closeReplyMessageModal;
});
</script>
@endpush 