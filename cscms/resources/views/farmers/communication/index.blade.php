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
            <div class="stat-value">{{ $messages->count() }}</div>
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
            <div class="stat-value">{{ $messages->where('is_read', true)->count() }}</div>
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
            <div class="stat-value">{{ $messages->where('is_read', false)->count() }}</div>
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
            <div class="card-actions">
                <button class="btn btn-sm btn-outline" onclick="markAllAsRead()">
                    <i class="fas fa-check-double"></i> Mark All Read
                </button>
            </div>
        </div>
        
        <div class="messages-container">
            @forelse ($messages as $message)
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
                    <h3>No Messages Yet</h3>
                    <p>Start communicating with processors to see messages here.</p>
                    <button class="btn btn-primary" onclick="openNewMessageModal()">
                        <i class="fas fa-plus"></i> Send First Message
                    </button>
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

// Message functions
function viewMessage(messageId) {
    // In a real app, this would open a detailed view
    alert('Viewing message ' + messageId + ' - This would open a detailed view');
}

function replyToMessage(messageId) {
    // In a real app, this would open reply form
    alert('Replying to message ' + messageId + ' - This would open reply form');
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
    const modal = document.getElementById('newMessageModal');
    if (event.target === modal) {
        closeNewMessageModal();
    }
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
</style>