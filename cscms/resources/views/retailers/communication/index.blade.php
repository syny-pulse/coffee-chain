<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Shop Dashboard - Communication</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2D3748;
            --primary-light: #4A5568;
            --accent: #8B7355;
            --accent-light: #A68B5B;
            --success: #48BB78;
            --warning: #ED8936;
            --danger: #F56565;
            --info: #4299E1;
            
            --bg-primary: #FFFFFF;
            --bg-secondary: #F7FAFC;
            --bg-tertiary: #EDF2F7;
            
            --text-primary: #2D3748;
            --text-secondary: #4A5568;
            --text-muted: #718096;
            
            --border: #E2E8F0;
            --border-light: #F1F5F9;
            
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            
            --radius-sm: 6px;
            --radius: 8px;
            --radius-lg: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            background: var(--bg-secondary);
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--bg-primary);
            border-right: 1px solid var(--border);
            padding: 2rem 0;
            display: flex;
            flex-direction: column;
        }

        .logo {
            padding: 0 2rem;
            margin-bottom: 3rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: var(--accent);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .logo-text {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .nav-menu {
            flex: 1;
            padding: 0 1rem;
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            padding: 0 1rem;
            margin-bottom: 0.5rem;
        }

        .nav-item {
            margin-bottom: 0.25rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: var(--radius);
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .nav-link:hover {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .nav-link.active {
            background: var(--accent);
            color: white;
        }

        .nav-link .icon {
            margin-right: 0.75rem;
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }

        .user-section {
            padding: 1rem;
            border-top: 1px solid var(--border);
            margin-top: auto;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: var(--bg-secondary);
            border-radius: var(--radius);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text-primary);
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
            width: 100%;
        }

        .page-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 0.5rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--bg-primary);
            border-radius: var(--radius);
            padding: 1rem;
            border: 1px solid var(--border);
            transition: all 0.2s ease;
            min-width: 0;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }

        .stat-icon {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            color: white;
            background: var(--accent);
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.625rem;
            font-weight: 600;
            padding: 0.125rem 0.375rem;
            border-radius: 12px;
        }

        .stat-trend.positive {
            background: rgba(72, 187, 120, 0.1);
            color: var(--success);
        }

        .stat-trend.negative {
            background: rgba(245, 101, 101, 0.1);
            color: var(--danger);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
            line-height: 1.2;
        }

        .stat-label {
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 0.75rem;
            line-height: 1.3;
        }

        /* Messages Section */
        .card {
            background: var(--bg-primary);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .card-actions {
            display: flex;
            gap: 0.5rem;
        }

        .messages-container {
            padding: 1.5rem;
        }

        .message-item {
            padding: 1.25rem;
            border-bottom: 1px solid var(--border);
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .message-item:last-child {
            border-bottom: none;
        }

        .message-item:hover {
            background: var(--bg-secondary);
        }

        .message-item.unread {
            background: rgba(66, 153, 225, 0.05);
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .message-sender {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .unread-badge {
            width: 8px;
            height: 8px;
            background: var(--info);
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
            background: var(--bg-tertiary);
        }

        .message-subject {
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
        }

        .message-content {
            color: var(--text-secondary);
            font-size: 0.875rem;
            line-height: 1.5;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .message-actions {
            display: flex;
            gap: 0.5rem;
        }

        /* Button Styles */
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: var(--accent);
            color: white;
            border-radius: var(--radius);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn:hover {
            background: var(--accent-light);
            box-shadow: var(--shadow);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-secondary);
        }

        .btn-outline:hover {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .btn-sm {
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
        }

        /* Modal Styles */
        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            display: none;
        }

        .modal-content {
            background: var(--bg-primary);
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 500px;
            position: relative;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .close {
            color: var(--text-muted);
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .close:hover {
            color: var(--text-primary);
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
            font-size: 0.875rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-family: inherit;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(139, 115, 85, 0.1);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1rem;
        }

        /* Empty State */
        .empty-state {
            padding: 3rem 2rem;
            text-align: center;
            border: 1px dashed var(--border);
            border-radius: var(--radius);
            background: var(--bg-secondary);
        }

        .empty-state-icon {
            font-size: 3rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--text-muted);
            margin-bottom: 1.5rem;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 1rem 0;
                order: 2;
            }

            .main-content {
                order: 1;
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .page-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .message-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .message-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-mug-hot"></i>
            </div>
            <div class="logo-text">Coffee Shop</div>
        </div>
        
        <nav class="nav-menu">
            <div class="nav-section">
                <div class="nav-section-title">Main</div>
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="icon"><i class="fas fa-grid-2"></i></span>
                        Dashboard
                    </a>
                </div>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Farm Management</div>
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="icon"><i class="fas fa-seedling"></i></span>
                        Harvest
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="icon"><i class="fas fa-warehouse"></i></span>
                        Inventory
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="icon"><i class="fas fa-shopping-bag"></i></span>
                        Orders
                    </a>
                </div>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Business</div>
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="icon"><i class="fas fa-dollar-sign"></i></span>
                        Financials
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="icon"><i class="fas fa-chart-line"></i></span>
                        Analytics
                    </a>
                </div>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Communication</div>
                <div class="nav-item">
                    <a href="#" class="nav-link active">
                        <span class="icon"><i class="fas fa-message"></i></span>
                        Messages
                    </a>
                </div>
            </div>
        </nav>

        <div class="user-section">
            <div class="user-profile">
                <div class="user-avatar">JD</div>
                <div class="user-info">
                    <div class="user-name">John Doe</div>
                    <div class="user-role">Coffee Farmer</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <div>
                <h1 class="page-title">Communication Center</h1>
                <p class="page-subtitle">Connect with processors, send messages, and manage your business communications</p>
            </div>
            <div class="page-actions">
                <button class="btn" onclick="openNewMessageModal()">
                    <i class="fas fa-plus"></i> New Message
                </button>
                <button class="btn btn-outline" onclick="refreshMessages()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>

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
                <div class="stat-value">42</div>
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
                <div class="stat-value">32</div>
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
                <div class="stat-value">10</div>
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
                <div class="stat-value">8</div>
                <div class="stat-label">Active Processors</div>
            </div>
        </div>

        <!-- Messages Section -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Recent Messages</h2>
                <div class="card-actions">
                    <button class="btn btn-outline btn-sm" onclick="markAllAsRead()">
                        <i class="fas fa-check-double"></i> Mark All Read
                    </button>
                </div>
            </div>
            
            <div class="messages-container">
                <div class="message-item unread">
                    <div class="message-header">
                        <div class="message-sender">
                            <span>Premium Coffee Processors</span>
                            <span class="unread-badge"></span>
                        </div>
                        <div class="message-meta">
                            <span class="message-date">Oct 15, 2023 10:30 AM</span>
                            <span class="message-type">Incoming</span>
                        </div>
                    </div>
                    <div class="message-subject">
                        New Contract Opportunity
                    </div>
                    <div class="message-content">
                        We're interested in your Arabica harvest from last season. Let's discuss a potential contract for next year's harvest.
                    </div>
                    <div class="message-actions">
                        <button class="btn btn-outline btn-sm" onclick="viewMessage(1)">
                            <i class="fas fa-eye"></i> View
                        </button>
                        <button class="btn btn-outline btn-sm" onclick="replyToMessage(1)">
                            <i class="fas fa-reply"></i> Reply
                        </button>
                    </div>
                </div>
                
                <div class="message-item">
                    <div class="message-header">
                        <div class="message-sender">
                            <span>Green Valley Roasters</span>
                        </div>
                        <div class="message-meta">
                            <span class="message-date">Oct 12, 2023 2:15 PM</span>
                            <span class="message-type">Outgoing</span>
                        </div>
                    </div>
                    <div class="message-subject">
                        Shipment Confirmation
                    </div>
                    <div class="message-content">
                        Just confirming that the 50kg of Robusta beans shipped yesterday should arrive at your facility by Thursday.
                    </div>
                    <div class="message-actions">
                        <button class="btn btn-outline btn-sm" onclick="viewMessage(2)">
                            <i class="fas fa-eye"></i> View
                        </button>
                        <button class="btn btn-outline btn-sm" onclick="replyToMessage(2)">
                            <i class="fas fa-reply"></i> Reply
                        </button>
                    </div>
                </div>
                
                <div class="message-item">
                    <div class="message-header">
                        <div class="message-sender">
                            <span>Mountain Peak Processing</span>
                        </div>
                        <div class="message-meta">
                            <span class="message-date">Oct 8, 2023 9:45 AM</span>
                            <span class="message-type">Incoming</span>
                        </div>
                    </div>
                    <div class="message-subject">
                        Quality Report
                    </div>
                    <div class="message-content">
                        We've completed the quality assessment of your recent shipment. Overall score: 92/100. Excellent quality as always!
                    </div>
                    <div class="message-actions">
                        <button class="btn btn-outline btn-sm" onclick="viewMessage(3)">
                            <i class="fas fa-eye"></i> View
                        </button>
                        <button class="btn btn-outline btn-sm" onclick="replyToMessage(3)">
                            <i class="fas fa-reply"></i> Reply
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Message Modal -->
        <div id="newMessageModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Send New Message</h2>
                    <span class="close" onclick="closeNewMessageModal()">&times;</span>
                </div>
                <form>
                    <div class="form-group">
                        <label for="processor_id">Select Processor</label>
                        <select name="processor_id" id="processor_id" class="form-control" required>
                            <option value="">Choose a processor...</option>
                            <option value="1">Premium Coffee Processors</option>
                            <option value="2">Green Valley Roasters</option>
                            <option value="3">Mountain Peak Processing</option>
                            <option value="4">Artisan Bean Co.</option>
                            <option value="5">Sunrise Coffee Mills</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" name="subject" id="subject" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="content">Message</label>
                        <textarea name="content" id="content" class="form-control" rows="6" required></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-outline" onclick="closeNewMessageModal()">
                            Cancel
                        </button>
                        <button type="submit" class="btn">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
            alert('Viewing message ' + messageId + ' - This would open a detailed view');
        }

        function replyToMessage(messageId) {
            openNewMessageModal();
            document.getElementById('subject').value = 'Re: Your recent message';
            document.getElementById('content').value = 'Thank you for your message...';
        }

        function markAllAsRead() {
            document.querySelectorAll('.unread').forEach(item => {
                item.classList.remove('unread');
            });
            document.querySelectorAll('.unread-badge').forEach(badge => {
                badge.style.display = 'none';
            });
            alert('All messages marked as read');
        }

        function refreshMessages() {
            alert('Refreshing messages...');
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
            // Add hover effects to message items
            const messageItems = document.querySelectorAll('.message-item');
            messageItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(5px)';
                    this.style.transition = 'transform 0.2s ease';
                });
                
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });
        });
    </script>
</body>
</html>