@extends('layouts.processor')

@section('title', 'Companies')

@section('content')
    <!-- Dashboard Header -->
    <div class="dashboard-header fade-in">
        <div class="dashboard-title">
            <i class="fas fa-building"></i>
            <div>
                <h1>Company Information</h1>
                <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">
                    View and manage company details
                </p>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="content-section fade-in">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-building"></i>
                <span>Companies</span>
            </div>
        </div>

        @if (session('success'))
            <div class="alert status-success fade-in alert-dismissible" id="success-alert"
                style="padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Registration Number</th>
                        <th>Application</th>
                        <th>Acceptance Status</th>
                        <th>Account Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($companies as $company)
                        <tr>
                            <td>{{ $company->company_name }}</td>
                            <td>{{ $company->email }}</td>
                            <td>{{ ucfirst($company->company_type) }}</td>
                            <td>{{ $company->phone }}</td>
                            <td>{{ $company->address }}</td>
                            <td>{{ $company->registration_number }}</td>
                            <td>
                                @if ($company->pdf_path)
                                    <a href="{{ '/storage' . str_replace(storage_path('app/public'), '', $company->pdf_path) }}"
                                        target="_blank" class="pdf-badge"><i class="fas fa-file-pdf"></i> View PDF</a>
                                @else
                                    <span class="status-badge status-inactive">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge status-{{ $company->acceptance_status }}">
                                    {{ ucfirst($company->acceptance_status) }}
                                </span>
                            </td>
                            <td>
                                @if ($company->account_status)
                                    <span class="status-badge status-{{ $company->account_status }}">
                                        {{ ucfirst($company->account_status) }}
                                    </span>
                                @else
                                    <span class="status-badge status-inactive">N/A</span>
                                @endif
                            </td>
                            <td>
                                <!-- Acceptance Status Dropdown -->
                                <form
                                    action="{{ route('processor.company.updateAcceptanceStatus', $company->company_id) }}"
                                    method="POST" class="d-inline">
                                    @csrf
                                    <select name="acceptance_status" class="status-select" onchange="this.form.submit()">
                                        <option value="" disabled selected>Change Status</option>
                                        <option value="accepted">Accepted</option>
                                        <option value="rejected">Rejected</option>
                                        <option value="pending">Pending</option>
                                        <option value="visit_scheduled">Visit Scheduled</option>
                                    </select>
                                </form>

                                <!-- Account Status Dropdown -->
                                @if ($company->account_status)
                                    <form action="{{ route('processor.company.updateAccountStatus', $company->user_id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <select name="account_status" class="status-select" onchange="this.form.submit()">
                                            <option value="" disabled selected>Change Status</option>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                            <option value="pending">Pending</option>
                                        </select>
                                    </form>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">No companies found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
