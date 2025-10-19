@extends('layouts.admin')

@section('page-title', 'Security Logs')

@section('admin-content')
<!-- Page Header -->
<div class="intel-page-header">
    <div class="intel-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="intel-page-title">
                    <i class="fas fa-shield-alt"></i>
                    Security Logs
                </h1>
                <p class="intel-page-subtitle">Monitor and review system security events</p>
            </div>
            <div style="display: flex; gap: var(--space-md);">
                <button type="button" class="intel-btn intel-btn-secondary" onclick="exportLogs()">
                    <i class="fas fa-download"></i>
                    Export Logs
                </button>
                <button type="button" class="intel-btn intel-btn-warning" onclick="bulkMarkReviewed()">
                    <i class="fas fa-check-double"></i>
                    Mark as Reviewed
                </button>
            </div>
        </div>
    </div>
</div>

<div class="intel-container">
    <!-- Security Statistics -->
    <div class="intel-stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: var(--space-lg);">
        <!-- Total Events -->
        <div class="intel-stat-card">
            <div class="intel-stat-header">
                <div class="intel-stat-content">
                    <h3>Total Events</h3>
                    <p class="value">{{ $stats['total_events'] ?? 1247 }}</p>
                </div>
                <div class="intel-stat-icon blue">
                    <i class="fas fa-list"></i>
                </div>
            </div>
            <div class="intel-stat-footer">
                <span class="trend positive">
                    <i class="fas fa-arrow-up"></i>
                    All time events
                </span>
            </div>
        </div>
        
        <!-- Today's Events -->
        <div class="intel-stat-card">
            <div class="intel-stat-header">
                <div class="intel-stat-content">
                    <h3>Today's Events</h3>
                    <p class="value">{{ $stats['today_events'] ?? 23 }}</p>
                </div>
                <div class="intel-stat-icon green">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
            <div class="intel-stat-footer">
                <span class="trend positive">
                    <i class="fas fa-arrow-up"></i>
                    +12% from yesterday
                </span>
            </div>
        </div>
        
        <!-- Critical Events -->
        <div class="intel-stat-card">
            <div class="intel-stat-header">
                <div class="intel-stat-content">
                    <h3>Critical Events</h3>
                    <p class="value">{{ $stats['critical_events'] ?? 3 }}</p>
                </div>
                <div class="intel-stat-icon red">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
            <div class="intel-stat-footer">
                <span class="trend negative">
                    <i class="fas fa-arrow-down"></i>
                    Requires attention
                </span>
            </div>
        </div>
        
        <!-- Failed Logins -->
        <div class="intel-stat-card">
            <div class="intel-stat-header">
                <div class="intel-stat-content">
                    <h3>Failed Logins</h3>
                    <p class="value">{{ $stats['failed_logins'] ?? 7 }}</p>
                </div>
                <div class="intel-stat-icon yellow">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
            <div class="intel-stat-footer">
                <span class="trend neutral">
                    <i class="fas fa-minus"></i>
                    Today's attempts
                </span>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="intel-card" style="margin-bottom: var(--space-lg);">
        <div class="intel-card-header">
            <h2 class="intel-card-title">
                <i class="fas fa-filter"></i>
                Filter Security Logs
            </h2>
            <p class="intel-card-subtitle">Filter and search security events</p>
        </div>
        <div class="intel-card-body">
            <form method="GET" action="{{ route('admin.security-logs.index') }}">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-lg); margin-bottom: var(--space-lg);">
                    <!-- Search -->
                    <div class="intel-form-group">
                        <label class="intel-form-label">Search</label>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               class="intel-form-input"
                               placeholder="Search logs...">
                    </div>
                    
                    <!-- Severity -->
                    <div class="intel-form-group">
                        <label class="intel-form-label">Severity</label>
                        <select name="severity" class="intel-form-select">
                            <option value="">All Severities</option>
                            <option value="critical" {{ request('severity') === 'critical' ? 'selected' : '' }}>Critical</option>
                            <option value="high" {{ request('severity') === 'high' ? 'selected' : '' }}>High</option>
                            <option value="medium" {{ request('severity') === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="low" {{ request('severity') === 'low' ? 'selected' : '' }}>Low</option>
                        </select>
                    </div>
                    
                    <!-- Event Type -->
                    <div class="intel-form-group">
                        <label class="intel-form-label">Event Type</label>
                        <select name="event_type" class="intel-form-select">
                            <option value="">All Types</option>
                            @if(isset($eventTypes))
                                @foreach($eventTypes as $type)
                                <option value="{{ $type }}" {{ request('event_type') === $type ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                                </option>
                                @endforeach
                            @else
                                <option value="login" {{ request('event_type') === 'login' ? 'selected' : '' }}>Login</option>
                                <option value="failed_login" {{ request('event_type') === 'failed_login' ? 'selected' : '' }}>Failed Login</option>
                                <option value="logout" {{ request('event_type') === 'logout' ? 'selected' : '' }}>Logout</option>
                                <option value="permission_denied" {{ request('event_type') === 'permission_denied' ? 'selected' : '' }}>Permission Denied</option>
                            @endif
                        </select>
                    </div>
                    
                    <!-- Date From -->
                    <div class="intel-form-group">
                        <label class="intel-form-label">Date From</label>
                        <input type="date" 
                               name="date_from" 
                               value="{{ request('date_from') }}"
                               class="intel-form-input">
                    </div>
                    
                    <!-- Date To -->
                    <div class="intel-form-group">
                        <label class="intel-form-label">Date To</label>
                        <input type="date" 
                               name="date_to" 
                               value="{{ request('date_to') }}"
                               class="intel-form-input">
                    </div>
                </div>
                
                <div style="display: flex; gap: var(--space-md);">
                    <button type="submit" class="intel-btn intel-btn-primary">
                        <i class="fas fa-search"></i>
                        Apply Filters
                    </button>
                    
                    <a href="{{ route('admin.security-logs.index') }}" class="intel-btn intel-btn-secondary">
                        <i class="fas fa-times"></i>
                        Clear Filters
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Security Logs Table -->
    <div class="intel-card">
        <div class="intel-card-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 class="intel-card-title">
                        <i class="fas fa-list"></i>
                        Security Events
                    </h2>
                    <p class="intel-card-subtitle">Recent security events and activities</p>
                </div>
                <div style="display: flex; gap: var(--space-md);">
                    <button type="button" class="intel-btn intel-btn-secondary intel-btn-sm" onclick="selectAll()">
                        <i class="fas fa-check-square"></i>
                        Select All
                    </button>
                    <button type="button" class="intel-btn intel-btn-danger intel-btn-sm" onclick="bulkDelete()">
                        <i class="fas fa-trash"></i>
                        Delete Selected
                    </button>
                </div>
            </div>
        </div>
        <div class="intel-card-body" style="padding: 0;">
            <div class="intel-table-container">
                <table class="intel-table">
                    <thead>
                        <tr>
                            <th style="width: 40px;">
                                <input type="checkbox" id="select-all" onchange="toggleSelectAll()">
                            </th>
                            <th>
                                <i class="fas fa-calendar mr-2"></i>
                                Date & Time
                            </th>
                            <th>
                                <i class="fas fa-tag mr-2"></i>
                                Event
                            </th>
                            <th>
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Severity
                            </th>
                            <th>
                                <i class="fas fa-user mr-2"></i>
                                User
                            </th>
                            <th>
                                <i class="fas fa-globe mr-2"></i>
                                IP Address
                            </th>
                            <th>
                                <i class="fas fa-eye mr-2"></i>
                                Status
                            </th>
                            <th>
                                <i class="fas fa-cogs mr-2"></i>
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample Security Log 1 -->
                        <tr>
                            <td>
                                <input type="checkbox" name="log_ids[]" value="1" class="log-checkbox">
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--intel-gray-900);">08/09/2025</div>
                                <div style="font-size: 0.875rem; color: var(--intel-gray-600);">14:32:15</div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: var(--space-sm);">
                                    <i class="fas fa-sign-in-alt text-blue-600"></i>
                                    <div>
                                        <div style="font-weight: 600; color: var(--intel-gray-900);">User Login</div>
                                        <div style="font-size: 0.875rem; color: var(--intel-gray-600);">Successful authentication</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="intel-badge intel-badge-success">
                                    <i class="fas fa-check"></i>Low
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--intel-gray-900);">John Doe</div>
                                <div style="font-size: 0.875rem; color: var(--intel-gray-600);">john.doe@example.com</div>
                            </td>
                            <td>
                                <code style="background: var(--intel-gray-100); padding: var(--space-xs) var(--space-sm); border-radius: var(--radius-sm); font-size: 0.875rem;">192.168.1.100</code>
                            </td>
                            <td>
                                <span class="intel-badge intel-badge-success">
                                    <i class="fas fa-check"></i>Reviewed
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: var(--space-xs);">
                                    <button type="button" class="intel-btn intel-btn-secondary intel-btn-sm" onclick="viewLog(1)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="intel-btn intel-btn-success intel-btn-sm" onclick="markReviewed(1)">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="intel-btn intel-btn-danger intel-btn-sm" onclick="deleteLog(1)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Sample Security Log 2 -->
                        <tr>
                            <td>
                                <input type="checkbox" name="log_ids[]" value="2" class="log-checkbox">
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--intel-gray-900);">08/09/2025</div>
                                <div style="font-size: 0.875rem; color: var(--intel-gray-600);">14:28:42</div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: var(--space-sm);">
                                    <i class="fas fa-times-circle text-red-600"></i>
                                    <div>
                                        <div style="font-weight: 600; color: var(--intel-gray-900);">Failed Login</div>
                                        <div style="font-size: 0.875rem; color: var(--intel-gray-600);">Invalid credentials</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="intel-badge intel-badge-warning">
                                    <i class="fas fa-exclamation-triangle"></i>Medium
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--intel-gray-900);">Unknown</div>
                                <div style="font-size: 0.875rem; color: var(--intel-gray-600);">admin@example.com</div>
                            </td>
                            <td>
                                <code style="background: var(--intel-gray-100); padding: var(--space-xs) var(--space-sm); border-radius: var(--radius-sm); font-size: 0.875rem;">203.0.113.45</code>
                            </td>
                            <td>
                                <span class="intel-badge intel-badge-warning">
                                    <i class="fas fa-clock"></i>Pending
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: var(--space-xs);">
                                    <button type="button" class="intel-btn intel-btn-secondary intel-btn-sm" onclick="viewLog(2)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="intel-btn intel-btn-success intel-btn-sm" onclick="markReviewed(2)">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="intel-btn intel-btn-danger intel-btn-sm" onclick="deleteLog(2)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Sample Security Log 3 -->
                        <tr>
                            <td>
                                <input type="checkbox" name="log_ids[]" value="3" class="log-checkbox">
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--intel-gray-900);">08/09/2025</div>
                                <div style="font-size: 0.875rem; color: var(--intel-gray-600);">14:15:33</div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: var(--space-sm);">
                                    <i class="fas fa-ban text-red-600"></i>
                                    <div>
                                        <div style="font-weight: 600; color: var(--intel-gray-900);">Permission Denied</div>
                                        <div style="font-size: 0.875rem; color: var(--intel-gray-600);">Unauthorized access attempt</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="intel-badge intel-badge-error">
                                    <i class="fas fa-exclamation-circle"></i>High
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--intel-gray-900);">Jane Smith</div>
                                <div style="font-size: 0.875rem; color: var(--intel-gray-600);">jane.smith@example.com</div>
                            </td>
                            <td>
                                <code style="background: var(--intel-gray-100); padding: var(--space-xs) var(--space-sm); border-radius: var(--radius-sm); font-size: 0.875rem;">192.168.1.105</code>
                            </td>
                            <td>
                                <span class="intel-badge intel-badge-warning">
                                    <i class="fas fa-clock"></i>Pending
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: var(--space-xs);">
                                    <button type="button" class="intel-btn intel-btn-secondary intel-btn-sm" onclick="viewLog(3)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="intel-btn intel-btn-success intel-btn-sm" onclick="markReviewed(3)">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="intel-btn intel-btn-danger intel-btn-sm" onclick="deleteLog(3)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Security Logs -->
<script>
function toggleSelectAll() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.log-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function selectAll() {
    const checkboxes = document.querySelectorAll('.log-checkbox');
    const selectAllCheckbox = document.getElementById('select-all');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    selectAllCheckbox.checked = true;
}

function getSelectedLogs() {
    const checkboxes = document.querySelectorAll('.log-checkbox:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

function bulkMarkReviewed() {
    const selectedLogs = getSelectedLogs();
    
    if (selectedLogs.length === 0) {
        alert('Please select at least one log to mark as reviewed.');
        return;
    }
    
    if (confirm(`Mark ${selectedLogs.length} selected logs as reviewed?`)) {
        // Create and submit form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/security-logs/bulk-mark-reviewed';
        form.innerHTML = `
            @csrf
            ${selectedLogs.map(id => `<input type="hidden" name="log_ids[]" value="${id}">`).join('')}
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function bulkDelete() {
    const selectedLogs = getSelectedLogs();
    
    if (selectedLogs.length === 0) {
        alert('Please select at least one log to delete.');
        return;
    }
    
    if (confirm(`Are you sure you want to delete ${selectedLogs.length} selected logs? This action cannot be undone.`)) {
        // Create and submit form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/security-logs/bulk-delete';
        form.innerHTML = `
            @csrf
            ${selectedLogs.map(id => `<input type="hidden" name="log_ids[]" value="${id}">`).join('')}
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function viewLog(logId) {
    window.open(`/admin/security-logs/${logId}`, '_blank', 'width=800,height=600,scrollbars=yes');
}

function markReviewed(logId) {
    if (confirm('Mark this log as reviewed?')) {
        // Create and submit form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/security-logs/${logId}/mark-reviewed`;
        form.innerHTML = `@csrf`;
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteLog(logId) {
    if (confirm('Are you sure you want to delete this security log? This action cannot be undone.')) {
        // Create and submit form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/security-logs/${logId}`;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function exportLogs() {
    // Get current filter parameters
    const params = new URLSearchParams(window.location.search);
    params.set('export', '1');
    
    // Create download link
    const link = document.createElement('a');
    link.href = `/admin/security-logs/export?${params.toString()}`;
    link.download = 'security_logs.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endsection
