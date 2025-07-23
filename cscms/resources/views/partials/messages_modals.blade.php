<!-- Messaging Modals and JS (shared across all roles) -->
<!-- New Message Modal -->
<div id="newMessageModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-paper-plane"></i> Send New Message</h3>
            <button class="modal-close" onclick="closeNewMessageModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('messages.send') }}" method="POST" class="form-container" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="recipient_company_id" class="form-label">Select Recipient</label>
                <select name="recipient_company_id" id="recipient_company_id" class="form-control" required>
                    <option value="">Choose a recipient...</option>
                    @foreach ($recipientCompanies ?? [] as $recipient)
                        <option value="{{ $recipient->company_id }}">{{ $recipient->company_name }} ({{ ucfirst($recipient->company_type) }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="message_type" class="form-label">Message Type</label>
                <select name="message_type" id="message_type" class="form-control" required>
                    <option value="">Select type...</option>
                    <option value="general">General</option>
                    <option value="order_inquiry">Order Inquiry</option>
                    <option value="quality_feedback">Quality Feedback</option>
                    <option value="delivery_update">Delivery Update</option>
                    <option value="system_notification">System Notification</option>
                </select>
            </div>
            <div class="form-group">
                <label for="content" class="form-label">Message</label>
                <textarea name="content" id="content" class="form-control" rows="6" placeholder="Type your message here..." required></textarea>
                <div class="form-text">Maximum 1000 characters</div>
            </div>
            <div class="form-group">
                <label for="attachment" class="form-label">Attachment (optional)</label>
                <input type="file" name="attachment" id="attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.txt,.zip,.rar">
                <div class="form-text">Max size: 10MB. Allowed: PDF, images, docs, zip, txt.</div>
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
    <div class="modal-content" style="max-width: 500px; width: 95%;">
        <div class="modal-header">
            <h3><i class="fas fa-reply"></i> Conversation</h3>
            <button class="modal-close" onclick="closeReplyMessageModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="replyMessageBody" style="padding:0;">
            <!-- Conversation thread will be loaded here -->
            <div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>
        </div>
        <form id="replyForm" class="chat-input" method="POST" action="{{ route('messages.send') }}" enctype="multipart/form-data" style="display:none; border-top:1px solid #eee; padding:0.75rem; background:#fafafa;">
            @csrf
            <input type="hidden" name="recipient_company_id" id="replyRecipientId">
            <input type="hidden" name="message_type" value="general">
            <textarea name="content" id="replyContent" class="form-control" rows="2" placeholder="Type your reply..." style="resize:none; width:80%; display:inline-block;"></textarea>
            <input type="file" name="attachment" id="replyAttachment" style="width:18%; display:inline-block;">
            <button type="submit" class="btn btn-primary" style="margin-top:0.5rem; float:right;">Send</button>
        </form>
    </div>
</div>

<style>
.chat-thread { max-height: 350px; overflow-y: auto; padding: 1rem; background: #f5f6fa; }
.chat-bubble { max-width: 75%; margin-bottom: 0.5rem; padding: 0.6rem 1rem; border-radius: 1.2em; position: relative; word-break: break-word; }
.chat-bubble.left { background: #fff; margin-right: auto; border-bottom-left-radius: 0.3em; box-shadow: 0 1px 4px #0001; }
.chat-bubble.right { background: #dcf8c6; margin-left: auto; border-bottom-right-radius: 0.3em; box-shadow: 0 1px 4px #0001; }
.chat-meta { font-size: 0.75em; color: #888; margin-top: 0.2em; text-align: right; }
.chat-attachment { margin-top: 0.3em; }
</style>

@push('scripts')
<script>
const csrfToken = '{{ csrf_token() }}';
const messagesSendUrl = '{{ route('messages.send') }}';

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
    if(document.getElementById('inboxMessages') && document.getElementById('sentMessages')) {
        document.getElementById('inboxMessages').style.display = 'none';
        document.getElementById('sentMessages').style.display = 'none';
        document.querySelector('.btn-tab.active').classList.remove('active');
        if (tab === 'inbox') {
            document.getElementById('inboxMessages').style.display = 'block';
            document.getElementById('inboxTab').classList.add('active');
        } else {
            document.getElementById('sentMessages').style.display = 'block';
            document.getElementById('sentTab').classList.add('active');
        }
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
            let attachmentHtml = '';
            if (message.attachment_path) {
                const fileName = message.attachment_path.split('/').pop();
                const fileUrl = `/storage/${message.attachment_path}`;
                const fileExt = fileName.split('.').pop().toLowerCase();
                let preview = '';
                if (["jpg","jpeg","png","gif","bmp","webp"].includes(fileExt)) {
                    preview = `<img src="${fileUrl}" alt="Attachment preview" style="max-width:100px;max-height:100px;display:block;margin-bottom:0.5rem;border-radius:6px;box-shadow:0 1px 4px #0001;">`;
                } else if (["pdf"].includes(fileExt)) {
                    preview = `<i class='fas fa-file-pdf' style='font-size:2rem;color:#e53e3e;'></i>`;
                } else if (["doc","docx"].includes(fileExt)) {
                    preview = `<i class='fas fa-file-word' style='font-size:2rem;color:#3182ce;'></i>`;
                } else if (["xls","xlsx"].includes(fileExt)) {
                    preview = `<i class='fas fa-file-excel' style='font-size:2rem;color:#38a169;'></i>`;
                } else if (["zip","rar"].includes(fileExt)) {
                    preview = `<i class='fas fa-file-archive' style='font-size:2rem;color:#d69e2e;'></i>`;
                } else if (["txt"].includes(fileExt)) {
                    preview = `<i class='fas fa-file-alt' style='font-size:2rem;color:#4a5568;'></i>`;
                } else {
                    preview = `<i class='fas fa-file' style='font-size:2rem;color:#718096;'></i>`;
                }
                attachmentHtml = `<div class=\"message-attachment\"><strong>Attachment:</strong><br>${preview}<a href=\"${fileUrl}\" target=\"_blank\" rel=\"noopener\">${fileName} <i class=\"fas fa-paperclip\"></i></a><div class=\"file-path\" style=\"font-size:0.85em;color:#888;word-break:break-all;\">${fileUrl}</div></div>`;
            }
            body.innerHTML = `
                <div class=\"message-header\">\n                    <div class=\"message-sender\">\n                        <strong>${message.sender_company_name || 'Unknown'}</strong>\n                        ${!message.is_read ? '<span class=\"unread-badge\"></span>' : ''}\n                    </div>\n                    <div class=\"message-meta\">\n                        <span class=\"message-date\">${message.created_at}</span>\n                        <span class=\"message-type\">${message.message_type}</span>\n                    </div>\n                </div>\n                <div class=\"message-subject\">\n                    <strong>${message.subject}</strong>\n                </div>\n                <div class=\"message-content\">\n                    ${message.message_body}\n                </div>\n                ${attachmentHtml}\n                <div class=\"message-actions\">\n                    <button class=\"btn btn-sm btn-outline\" onclick=\"closeViewMessageModal()\">\n                        <i class=\"fas fa-times\"></i> Close\n                    </button>\n                </div>\n            `;
        });
}
function replyToMessage(messageId) {
    openReplyMessageModal();
    const body = document.getElementById('replyMessageBody');
    body.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
    document.getElementById('replyForm').style.display = 'none';
    // Fetch original message to get recipient
    fetch(`/messages/${messageId}/reply`)
        .then(response => response.json())
        .then(data => {
            const recipient = data.recipient;
            document.getElementById('replyRecipientId').value = recipient.company_id;
            // Fetch thread
            fetch(`/messages/thread/${recipient.company_id}`)
                .then(res => res.json())
                .then(threadData => {
                    const userCompanyId = {{ auth()->user()->company_id }};
                    let html = '<div class="chat-thread">';
                    threadData.messages.forEach(msg => {
                        const isMe = msg.sender_company_id == userCompanyId;
                        let bubbleClass = isMe ? 'chat-bubble right' : 'chat-bubble left';
                        let sender = isMe ? 'You' : (recipient.company_name || 'Partner');
                        let attachmentHtml = '';
                        if (msg.attachment_path) {
                            const fileName = msg.attachment_path.split('/').pop();
                            const fileUrl = `/storage/${msg.attachment_path}`;
                            const fileExt = fileName.split('.').pop().toLowerCase();
                            let preview = '';
                            if (["jpg","jpeg","png","gif","bmp","webp"].includes(fileExt)) {
                                preview = `<img src="${fileUrl}" alt="Attachment preview" style="max-width:80px;max-height:80px;display:block;margin-bottom:0.3rem;border-radius:6px;box-shadow:0 1px 4px #0001;">`;
                            } else if (["pdf"].includes(fileExt)) {
                                preview = `<i class='fas fa-file-pdf' style='font-size:1.2rem;color:#e53e3e;'></i>`;
                            } else if (["doc","docx"].includes(fileExt)) {
                                preview = `<i class='fas fa-file-word' style='font-size:1.2rem;color:#3182ce;'></i>`;
                            } else if (["xls","xlsx"].includes(fileExt)) {
                                preview = `<i class='fas fa-file-excel' style='font-size:1.2rem;color:#38a169;'></i>`;
                            } else if (["zip","rar"].includes(fileExt)) {
                                preview = `<i class='fas fa-file-archive' style='font-size:1.2rem;color:#d69e2e;'></i>`;
                            } else if (["txt"].includes(fileExt)) {
                                preview = `<i class='fas fa-file-alt' style='font-size:1.2rem;color:#4a5568;'></i>`;
                            } else {
                                preview = `<i class='fas fa-file' style='font-size:1.2rem;color:#718096;'></i>`;
                            }
                            attachmentHtml = `<div class=\"chat-attachment\">${preview}<a href=\"${fileUrl}\" target=\"_blank\">${fileName}</a></div>`;
                        }
                        html += `<div class='${bubbleClass}'>
                            <div>${msg.message_body || ''}</div>
                            ${attachmentHtml}
                            <div class='chat-meta'>${sender} &middot; ${msg.created_at ? new Date(msg.created_at).toLocaleString() : ''}</div>
                        </div>`;
                    });
                    html += '</div>';
                    body.innerHTML = html;
                    document.getElementById('replyForm').style.display = 'block';
                    // Scroll to bottom
                    setTimeout(() => {
                        body.querySelector('.chat-thread').scrollTop = body.querySelector('.chat-thread').scrollHeight;
                    }, 100);
                });
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
</script>
@endpush 