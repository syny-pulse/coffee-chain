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

    <style>
        .notification-popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            animation: fadeIn 0.3s ease;
        }

        .notification-popup-content {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow: hidden;
            animation: slideIn 0.3s ease;
        }

        .notification-popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
            color: white;
        }

        .notification-popup-header h3 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .notification-popup-header h3 i {
            margin-right: 0.5rem;
        }

        .notification-popup-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .notification-popup-close:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .notification-popup-list {
            max-height: 400px;
            overflow-y: auto;
            padding: 0;
        }

        .notification-popup-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
            transition: all 0.3s ease;
            position: relative;
        }

        .notification-popup-item:last-child {
            border-bottom: none;
        }

        .notification-popup-item:hover {
            background: #f8f9fa;
        }

        .notification-popup-item.notification-warning {
            border-left: 4px solid #8B4513;
        }

        .notification-popup-item.notification-info {
            border-left: 4px solid #A0522D;
        }

        .notification-popup-item.notification-danger {
            border-left: 4px solid #8B4513;
        }

        .notification-popup-item.notification-success {
            border-left: 4px solid #A0522D;
        }

        .notification-popup-icon {
            margin-right: 1rem;
            padding: 0.75rem;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            min-width: 50px;
            text-align: center;
            font-size: 1.1rem;
        }

        .notification-popup-item.notification-warning .notification-popup-icon {
            background: #F5F5DC;
            color: #8B4513;
        }

        .notification-popup-item.notification-info .notification-popup-icon {
            background: #F5F5DC;
            color: #A0522D;
        }

        .notification-popup-item.notification-danger .notification-popup-icon {
            background: #F5F5DC;
            color: #8B4513;
        }

        .notification-popup-item.notification-success .notification-popup-icon {
            background: #F5F5DC;
            color: #A0522D;
        }

        .notification-popup-content-text {
            flex: 1;
        }

        .notification-popup-title {
            font-weight: 600;
            color: #212529;
            margin-bottom: 0.25rem;
            font-size: 1rem;
        }

        .notification-popup-message {
            color: #6c757d;
            line-height: 1.4;
            font-size: 0.9rem;
        }

        .notification-popup-link {
            color: #8B4513;
            text-decoration: none;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.2s ease;
            margin-left: 0.5rem;
        }

        .notification-popup-link:hover {
            background: #F5F5DC;
            color: #A0522D;
        }

        .notification-popup-footer {
            display: flex;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }

        .notification-popup-footer .btn {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }

        .notification-popup-footer .btn-primary {
            background-color: #8B4513;
            border-color: #8B4513;
            color: white;
        }

        .notification-popup-footer .btn-primary:hover {
            background-color: #A0522D;
            border-color: #A0522D;
            color: white;
        }

        .notification-popup-footer .btn-outline-secondary {
            color: #8B4513;
            border-color: #8B4513;
            background-color: transparent;
        }

        .notification-popup-footer .btn-outline-secondary:hover {
            background-color: #8B4513;
            border-color: #8B4513;
            color: white;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { 
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }
            to { 
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes slideOut {
            from { 
                opacity: 1;
                transform: translateY(0) scale(1);
            }
            to { 
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }
        }

        .notification-popup.closing {
            animation: fadeOut 0.3s ease;
        }

        .notification-popup.closing .notification-popup-content {
            animation: slideOut 0.3s ease;
        }

        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }

        @media (max-width: 768px) {
            .notification-popup-content {
                width: 95%;
                max-height: 90vh;
            }

            .notification-popup-item {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
            }

            .notification-popup-icon {
                align-self: center;
                margin-bottom: 0.5rem;
                margin-right: 0;
            }

            .notification-popup-footer {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>

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