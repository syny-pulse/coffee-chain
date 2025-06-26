@extends('layouts.processor')

@section('title', 'Application Report')

@section('content')
    <div class="dashboard-header fade-in">
        <div class="dashboard-title">
            <i class="fas fa-file-alt"></i>
            <div>
                <h1>Application Report</h1>
                <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">
                    Comprehensive analysis of company applications - {{ $dateRange['label'] }}
                </p>
            </div>
        </div>
        <div class="dashboard-actions">
            <form method="GET" action="{{ route('processor.reports.application') }}" class="date-filter-form">
                <select name="period" onchange="toggleCustomDates(this.value)" class="status-select">
                    <option value="last_7_days" {{ request('period') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days
                    </option>
                    <option value="last_30_days"
                        {{ request('period') == 'last_30_days' || !request('period') ? 'selected' : '' }}>Last 30 Days
                    </option>
                    <option value="this_month" {{ request('period') == 'this_month' ? 'selected' : '' }}>This Month</option>
                    <option value="last_month" {{ request('period') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                    <option value="this_year" {{ request('period') == 'this_year' ? 'selected' : '' }}>This Year</option>
                    <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                </select>

                <div id="custom-dates"
                    style="display: {{ request('period') == 'custom' ? 'flex' : 'none' }}; gap: 0.5rem; margin-left: 0.5rem;">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="status-select">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="status-select">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Apply Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="stats-grid fade-in">
        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Total Applications</span>
                <div class="stat-card-icon" style="background: {{ $stats['total_applications']['color'] }}">
                    <i class="{{ $stats['total_applications']['icon'] }}"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($stats['total_applications']['value']) }}</div>
            <div
                class="stat-card-change {{ $stats['total_applications']['change'] >= 0 ? 'change-positive' : 'change-negative' }}">
                <i class="fas fa-{{ $stats['total_applications']['change'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                {{ abs($stats['total_applications']['change']) }}% from previous period
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Accepted</span>
                <div class="stat-card-icon" style="background: {{ $stats['accepted_applications']['color'] }}">
                    <i class="{{ $stats['accepted_applications']['icon'] }}"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($stats['accepted_applications']['value']) }}</div>
            <div
                class="stat-card-change {{ $stats['accepted_applications']['change'] >= 0 ? 'change-positive' : 'change-negative' }}">
                <i class="fas fa-{{ $stats['accepted_applications']['change'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                {{ abs($stats['accepted_applications']['change']) }}% from previous period
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Pending</span>
                <div class="stat-card-icon" style="background: {{ $stats['pending_applications']['color'] }}">
                    <i class="{{ $stats['pending_applications']['icon'] }}"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($stats['pending_applications']['value']) }}</div>
            <div
                class="stat-card-change {{ $stats['pending_applications']['change'] >= 0 ? 'change-positive' : 'change-negative' }}">
                <i class="fas fa-{{ $stats['pending_applications']['change'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                {{ abs($stats['pending_applications']['change']) }}% from previous period
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Acceptance Rate</span>
                <div class="stat-card-icon" style="background: {{ $stats['acceptance_rate']['color'] }}">
                    <i class="{{ $stats['acceptance_rate']['icon'] }}"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($stats['acceptance_rate']['value'], 1) }}%</div>
            <div
                class="stat-card-change {{ $stats['acceptance_rate']['change'] >= 0 ? 'change-positive' : 'change-negative' }}">
                <i class="fas fa-{{ $stats['acceptance_rate']['change'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                {{ abs($stats['acceptance_rate']['change']) }}% from previous period
            </div>
        </div>
    </div>

    <!-- Risk Analytics -->
    <div class="content-section fade-in">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-chart-bar"></i>
                <span>Risk Analytics Overview</span>
            </div>
        </div>
        <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
            <div class="stat-card">
                <div class="stat-card-header">
                    <span class="stat-card-title">Avg Financial Risk</span>
                    <div class="stat-card-icon" style="background: {{ $stats['avg_financial_risk']['color'] }}">
                        <i class="{{ $stats['avg_financial_risk']['icon'] }}"></i>
                    </div>
                </div>
                <div class="stat-card-value">{{ number_format($stats['avg_financial_risk']['value'], 1) }}</div>
                <div class="text-muted">Out of 10.0</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-header">
                    <span class="stat-card-title">Avg Reputational Risk</span>
                    <div class="stat-card-icon" style="background: {{ $stats['avg_reputational_risk']['color'] }}">
                        <i class="{{ $stats['avg_reputational_risk']['icon'] }}"></i>
                    </div>
                </div>
                <div class="stat-card-value">{{ number_format($stats['avg_reputational_risk']['value'], 1) }}</div>
                <div class="text-muted">Out of 10.0</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-header">
                    <span class="stat-card-title">Avg Compliance Risk</span>
                    <div class="stat-card-icon" style="background: {{ $stats['avg_compliance_risk']['color'] }}">
                        <i class="{{ $stats['avg_compliance_risk']['icon'] }}"></i>
                    </div>
                </div>
                <div class="stat-card-value">{{ number_format($stats['avg_compliance_risk']['value'], 1) }}</div>
                <div class="text-muted">Out of 10.0</div>
            </div>
        </div>
    </div>

    <!-- Applications by Status -->
    @foreach (['pending' => 'Pending Applications', 'accepted' => 'Accepted Applications', 'rejected' => 'Rejected Applications', 'visit_scheduled' => 'Visit Scheduled Applications'] as $status => $title)
        @if ($companiesByStatus[$status]['count'] > 0)
            <div class="content-section fade-in">
                <div class="section-header">
                    <div class="section-title">
                        <i
                            class="fas fa-{{ $status == 'pending' ? 'clock' : ($status == 'accepted' ? 'check-circle' : ($status == 'rejected' ? 'times-circle' : 'calendar-check')) }}"></i>
                        <span>{{ $title }} ({{ $companiesByStatus[$status]['count'] }})</span>
                    </div>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Company Name</th>
                                <th>Type</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Registration Number</th>
                                <th>Financial Risk</th>
                                <th>Reputational Risk</th>
                                <th>Compliance Risk</th>
                                <th>Application Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($companiesByStatus[$status]['companies'] as $company)
                                <tr>
                                    <td>
                                        <strong>{{ $company->company_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $company->address }}</small>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $company->company_type }}">
                                            {{ ucfirst($company->company_type) }}
                                        </span>
                                    </td>
                                    <td>{{ $company->email }}</td>
                                    <td>{{ $company->phone }}</td>
                                    <td>{{ $company->registration_number }}</td>
                                    <td>
                                        <span
                                            class="status-badge status-{{ $company->financial_risk_rating <= 3 ? 'low' : ($company->financial_risk_rating <= 6 ? 'medium' : 'high') }}">
                                            {{ $company->financial_risk_rating }}/10
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="status-badge status-{{ $company->reputational_risk_rating <= 3 ? 'low' : ($company->reputational_risk_rating <= 6 ? 'medium' : 'high') }}">
                                            {{ $company->reputational_risk_rating }}/10
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="status-badge status-{{ $company->compliance_risk_rating <= 3 ? 'low' : ($company->compliance_risk_rating <= 6 ? 'medium' : 'high') }}">
                                            {{ $company->compliance_risk_rating }}/10
                                        </span>
                                    </td>
                                    <td>{{ $company->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endforeach

    <!-- Recent Applications Overview -->
    <div class="content-section fade-in">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-history"></i>
                <span>Recent Applications</span>
            </div>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Risk Summary</th>
                        <th>Applied</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentApplications as $company)
                        <tr>
                            <td>
                                <strong>{{ $company->company_name }}</strong>
                                <br>
                                <small class="text-muted">{{ $company->email }}</small>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $company->company_type }}">
                                    {{ ucfirst($company->company_type) }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $company->acceptance_status }}">
                                    {{ ucfirst(str_replace('_', ' ', $company->acceptance_status)) }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.25rem;">
                                    <small
                                        class="status-badge status-{{ $company->financial_risk_rating <= 3 ? 'low' : ($company->financial_risk_rating <= 6 ? 'medium' : 'high') }}">
                                        F: {{ $company->financial_risk_rating }}
                                    </small>
                                    <small
                                        class="status-badge status-{{ $company->reputational_risk_rating <= 3 ? 'low' : ($company->reputational_risk_rating <= 6 ? 'medium' : 'high') }}">
                                        R: {{ $company->reputational_risk_rating }}
                                    </small>
                                    <small
                                        class="status-badge status-{{ $company->compliance_risk_rating <= 3 ? 'low' : ($company->compliance_risk_rating <= 6 ? 'medium' : 'high') }}">
                                        C: {{ $company->compliance_risk_rating }}
                                    </small>
                                </div>
                            </td>
                            <td>{{ $company->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function toggleCustomDates(period) {
            const customDates = document.getElementById('custom-dates');
            if (period === 'custom') {
                customDates.style.display = 'flex';
            } else {
                customDates.style.display = 'none';
                document.querySelector('.date-filter-form').submit();
            }
        }
    </script>
@endsection
