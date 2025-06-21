@extends('layouts.processor')

@section('title', 'Messages')

@section('content')
    <!-- Dashboard Header -->
    <div class="dashboard-header fade-in">
        <div class="dashboard-title">
            <i class="fas fa-envelope"></i>
            <div>
                <h1>Communication Management</h1>
                <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">
                    Manage communications as of {{ now()->format('h:i A T, l, F d, Y') }}
                </p>
            </div>
        </div>
        <div class="dashboard-actions">
            <a href="{{ route('processor.message.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                New Message
            </a>
        </div>
    </div>

    <!-- Messages Overview -->
    <div class="stats-grid fade-in">
        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Total Messages</span>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);">
                    <i class="fas fa-envelope"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $messages->count() }}</div>
            <div class="stat-card-change">
                <i class="fas fa-chart-line"></i>
                <span class="change-positive">All messages</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Unread Messages</span>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);">
                    <i class="fas fa-envelope-open"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $messages->where('is_read', false)->count() }}</div>
            <div class="stat-card-change">
                <i class="fas fa-exclamation"></i>
                <span class="change-negative">Need attention</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">High Priority</span>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $messages->where('priority', 'high')->count() }}</div>
            <div class="stat-card-change">
                <i class="fas fa-arrow-up"></i>
                <span class="change-negative">Urgent</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">This Month</span>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);">
                    <i class="fas fa-calendar"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $messages->where('created_at', '>=', now()->startOfMonth())->count() }}</div>
            <div class="stat-card-change">
                <i class="fas fa-chart-line"></i>
                <span class="change-positive">Recent activity</span>
            </div>
        </div>
    </div>

    <!-- Messages List -->
    <div class="content-section fade-in">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-envelope"></i>
                <span>All Messages</span>
            </div>
            <a href="{{ route('processor.message.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                New Message
            </a>
        </div>
        
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>From</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-envelope{{ $message->is_read ? '' : '-open' }}" 
                                   style="color: {{ $message->is_read ? 'var(--text-light)' : 'var(--coffee-medium)' }};"></i>
                                <strong>{{ $message->subject ?: 'No Subject' }}</strong>
                            </div>
                        </td>
                        <td>{{ $message->sender->company ? $message->sender->company->company_name : 'Unknown' }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $message->message_type)) }}</td>
                        <td>{{ $message->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($message->is_read)
                                <span class="status-badge status-high">Read</span>
                            @else
                                <span class="status-badge status-low">Unread</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('processor.message.show', $message->message_id) }}" 
                               class="btn" style="padding: 0.25rem 0.5rem; font-size: 0.8rem; background: var(--coffee-medium); color: white;">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No messages found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection