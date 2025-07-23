@if(isset($notifications) && count($notifications) > 0)
    <div class="notification-popup" id="notification-popup">
        <div class="notification-popup-content">
            <div class="notification-popup-header">
                <h3><i class="fas fa-bell"></i> New Notifications</h3>
                <button class="notification-popup-close" onclick="closeNotificationPopup()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="notification-popup-list">
                @foreach($notifications as $notification)
                    <div class="notification-popup-item notification-{{ $notification['color'] }}" id="popup-notification-{{ $loop->index }}">
                        <div class="notification-popup-icon">
                            <i class="{{ $notification['icon'] }}"></i>
                        </div>
                        <div class="notification-popup-content-text">
                            <div class="notification-popup-title">{{ $notification['title'] }}</div>
                            <div class="notification-popup-message">{{ $notification['message'] }}</div>
                        </div>
                        <a href="{{ $notification['link'] }}" class="notification-popup-link">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                @endforeach
            </div>
            
            <div class="notification-popup-footer">
                <button class="btn btn-sm btn-primary" onclick="markAllAsRead()">
                    <i class="fas fa-check"></i> Mark All as Read
                </button>
                <button class="btn btn-sm btn-outline-secondary" onclick="closeNotificationPopup()">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>

    <script>
        function closeNotificationPopup() {
            const popup = document.getElementById('notification-popup');
            if (popup) {
                popup.classList.add('closing');
                setTimeout(() => {
                    popup.remove();
                }, 300);
            }
        }

        function markAllAsRead() {
            fetch('{{ route("farmers.notifications.mark-read") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update specific notification counts in navigation
                    const orderBadges = document.querySelectorAll('a[href*="orders"] .notification-badge');
                    const messageBadges = document.querySelectorAll('a[href*="communication"] .notification-badge');
                    
                    // Update order badges
                    orderBadges.forEach(badge => {
                        if (data.pendingOrdersCount > 0) {
                            badge.textContent = data.pendingOrdersCount;
                        } else {
                            badge.style.display = 'none';
                        }
                    });
                    
                    // Update message badges
                    messageBadges.forEach(badge => {
                        if (data.unreadMessagesCount > 0) {
                            badge.textContent = data.unreadMessagesCount;
                        } else {
                            badge.style.display = 'none';
                        }
                    });
                    
                    // Show success message and close popup
                    const popup = document.getElementById('notification-popup');
                    if (popup) {
                        popup.classList.add('closing');
                        setTimeout(() => {
                            popup.remove();
                        }, 1000);
                    }
                }
            })
            .catch(error => {
                console.error('Error marking notifications as read:', error);
            });
        }

        // Auto-close popup after 15 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const popup = document.getElementById('notification-popup');
                if (popup) {
                    closeNotificationPopup();
                }
            }, 15000);
        });

        // Close popup when clicking outside
        document.addEventListener('DOMContentLoaded', function() {
            const popup = document.getElementById('notification-popup');
            if (popup) {
                popup.addEventListener('click', function(e) {
                    if (e.target === popup) {
                        closeNotificationPopup();
                    }
                });
            }
        });
    </script>
@endif 