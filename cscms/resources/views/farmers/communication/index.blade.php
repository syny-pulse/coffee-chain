@extends('farmers.layouts.app')

@section('title', 'Communication')
@section('page-title', 'Communication Center')
@section('page-subtitle', 'Connect with processors, send messages, and manage your business communications')

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
        <div class="stat-card">
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
        
        <div class="stat-card">
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
        
        <div class="stat-card">
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
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    +1
                </div>
            </div>
            <div class="stat-value">{{ $processors->count() }}</div>
            <div class="stat-label">Active Processors</div>
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

    <!-- New Message Modal -->
    <div id="newMessageModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-paper-plane"></i> Send New Message</h3>
                <button class="modal-close" onclick="closeNewMessageModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('farmers.communication.send') }}" method="POST" class="form-container">
                @csrf
                <div class="form-group">
                    <label for="processor_id" class="form-label">Select Processor</label>
                    <select name="processor_id" id="processor_id" class="form-control" required>
                        <option value="">Choose a processor...</option>
                        @foreach ($processors as $processor)
                            <option value="{{ $processor['company_id'] }}">{{ $processor['company_name'] }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="content" class="form-label">Message</label>
                    <textarea name="content" id="content" class="form-control" rows="6" 
                              placeholder="Type your message here..." required></textarea>
                    <div class="form-text">Maximum 1000 characters</div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-outline" onclick="closeNewMessageModal()">
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
                <button class="modal-close" onclick="closeViewMessageModal()">
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
                <button class="modal-close" onclick="closeReplyMessageModal()">
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

// AJAX: View Message
function viewMessage(messageId) {
    openViewMessageModal();
    const body = document.getElementById('viewMessageBody');
    body.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
    fetch(`/farmers/communication/message/${messageId}`)
        .then(response => response.json())
        .then(data => {
            const msg = data.message;
            body.innerHTML = `
                <div><strong>From:</strong> ${data.sender || 'Unknown'}</div>
                <div><strong>To:</strong> ${data.receiver || 'Unknown'}</div>
                <div><strong>Date:</strong> ${msg.created_at ? new Date(msg.created_at).toLocaleString() : ''}</div>
                <div><strong>Subject:</strong> ${msg.subject || ''}</div>
                <hr/>
                <div style="white-space: pre-line;">${msg.message_body || ''}</div>
            `;
        })
        .catch(() => {
            body.innerHTML = '<div class="text-danger">Failed to load message.</div>';
        });
}

// AJAX: Reply to Message
function replyToMessage(messageId) {
    openReplyMessageModal();
    const body = document.getElementById('replyMessageBody');
    body.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
    fetch(`/farmers/communication/message/${messageId}/reply`)
        .then(response => response.json())
        .then(data => {
            const processor = data.processor;
            const original = data.original_message;
            body.innerHTML = `
                <form action=\"{{ route('farmers.communication.send') }}\" method=\"POST\" class=\"form-container\">
                    @csrf
                    <input type=\"hidden\" name=\"processor_id\" value=\"${processor.company_id}\">
                    <div class=\"form-group\">
                        <label class=\"form-label\">To</label>
                        <input type=\"text\" class=\"form-control\" value=\"${processor.company_name}\" readonly>
                    </div>
                    <div class=\"form-group\">
                        <label class=\"form-label\">Subject</label>
                        <input type=\"text\" name=\"subject\" class=\"form-control\" value=\"Re: ${original.subject || ''}\" required>
                    </div>
                    <div class=\"form-group\">
                        <label class=\"form-label\">Message</label>
                        <textarea name=\"content\" class=\"form-control\" rows=\"6\" placeholder=\"Type your reply here...\" required></textarea>
                        <div class=\"form-text\">Maximum 1000 characters</div>
                    </div>
                    <div class=\"form-actions\">
                        <button type=\"button\" class=\"btn btn-outline\" onclick=\"closeReplyMessageModal()\"><i class="fas fa-times"></i> Cancel</button>
                        <button type=\"submit\" class=\"btn btn-primary\"><i class="fas fa-paper-plane"></i> Send Reply</button>
                    </div>
                </form>
                <hr/>
                <div class=\"mt-2\"><strong>Original Message:</strong><br><div style=\"white-space: pre-line; color: #888;\">${original.message_body || ''}</div></div>
            `;
        })
        .catch(() => {
            body.innerHTML = '<div class="text-danger">Failed to load reply form.</div>';
        });
}

function markAllAsRead() {
    // In a real app, this would mark all messages as read
    alert('Marking all messages as read - This would update the database');
}

function refreshMessages() {
    // In a real app, this would refresh messages from server
    location.reload();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const newMsgModal = document.getElementById('newMessageModal');
    const viewMsgModal = document.getElementById('viewMessageModal');
    const replyMsgModal = document.getElementById('replyMessageModal');
    if (event.target === newMsgModal) closeNewMessageModal();
    if (event.target === viewMsgModal) closeViewMessageModal();
    if (event.target === replyMsgModal) closeReplyMessageModal();
}

// Initialize communication page
document.addEventListener('DOMContentLoaded', function() {
    // Highlight communication nav item
    const communicationLink = document.querySelector('a[href*="communication"]');
    if (communicationLink) {
        communicationLink.classList.add('active');
    }
    
    // Add hover effects to message items
    const messageItems = document.querySelectorAll('.message-item');
    messageItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(8px)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
});

function showMessagesTab(tab) {
    document.getElementById('inboxMessages').style.display = (tab === 'inbox') ? '' : 'none';
    document.getElementById('sentMessages').style.display = (tab === 'sent') ? '' : 'none';
    document.getElementById('inboxTab').classList.toggle('active', tab === 'inbox');
    document.getElementById('sentTab').classList.toggle('active', tab === 'sent');
}
</script>
@endpush

<style>
/* Modal Styles */
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: var(--bg-secondary);
    border-radius: 16px;
    padding: 0;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: var(--shadow-xl);
    border: 1px solid var(--border);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--border-light);
}

.modal-header h3 {
    margin: 0;
    color: var(--text-primary);
    font-size: 1.25rem;
    font-weight: 700;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.25rem;
    color: var(--text-muted);
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.modal-close:hover {
    background: var(--bg-tertiary);
    color: var(--text-primary);
}

/* Messages Container */
.messages-container {
    padding: 1rem;
}

.message-item {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.message-item:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--coffee-medium);
}

.message-item.unread {
    border-left: 4px solid var(--coffee-medium);
    background: var(--bg-tertiary);
}

.message-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.message-sender {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-primary);
    font-size: 0.875rem;
}

.unread-badge {
    width: 8px;
    height: 8px;
    background: var(--coffee-medium);
    border-radius: 50%;
}

.message-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.75rem;
    color: var(--text-muted);
}

.message-type {
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.625rem;
    letter-spacing: 0.05em;
}

.message-type.incoming {
    background: rgba(107, 142, 35, 0.1);
    color: var(--success);
}

.message-type.outgoing {
    background: rgba(112, 128, 144, 0.1);
    color: var(--info);
}

.message-subject {
    margin-bottom: 0.75rem;
    color: var(--text-primary);
    font-size: 0.875rem;
}

.message-content {
    color: var(--text-secondary);
    font-size: 0.875rem;
    line-height: 1.5;
    margin-bottom: 1rem;
}

.message-actions {
    display: flex;
    gap: 0.5rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .modal-content {
        width: 95%;
        margin: 1rem;
    }
    
    .message-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .message-actions {
        flex-direction: column;
    }
    
    .message-actions .btn {
        width: 100%;
        justify-content: center;
    }
}

.right-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    width: 100%;
}

.btn-tab {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 0.5rem 1.25rem;
    font-size: 1rem;
    color: var(--text-primary);
    cursor: pointer;
    transition: background 0.2s, color 0.2s, border 0.2s, box-shadow 0.2s;
    outline: none;
}
.btn-tab.active, .btn-tab:focus {
    background: var(--coffee-medium);
    color: #fff;
    border-color: var(--coffee-medium);
    font-weight: bold;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    z-index: 1;
}
.btn-tab:not(.active):hover {
    background: var(--bg-tertiary);
    color: var(--text-primary);
    border-color: var(--coffee-medium);
}
</style>