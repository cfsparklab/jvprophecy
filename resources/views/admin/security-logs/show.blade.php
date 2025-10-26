@extends('layouts.admin')

@section('page-title', 'Security Log Details')

@section('admin-content')
<style>
    .detail-row {
        display: flex;
        padding: 1rem;
        border-bottom: 1px solid var(--intel-gray-200);
    }
    .detail-row:hover {
        background: var(--intel-gray-50);
    }
    .detail-label {
        flex: 0 0 200px;
        font-weight: 600;
        color: var(--intel-gray-700);
    }
    .detail-value {
        flex: 1;
        color: var(--intel-gray-900);
    }
    .metadata-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }
    .metadata-item {
        padding: 1rem;
        background: var(--intel-gray-50);
        border-radius: var(--radius-md);
        border: 1px solid var(--intel-gray-200);
    }
    .metadata-key {
        font-weight: 600;
        color: var(--intel-gray-700);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }
    .metadata-value {
        color: var(--intel-gray-900);
        word-break: break-word;
    }
</style>

<!-- Page Header -->
<div class="intel-page-header">
    <div class="intel-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="intel-page-title">
                    <i class="fas fa-shield-alt"></i>
                    Security Log Details
                </h1>
                <p class="intel-page-subtitle">Event ID: #{{ $securityLog->id }}</p>
            </div>
            <div style="display: flex; gap: var(--space-md);">
                <a href="{{ route('admin.security-logs.index') }}" class="intel-btn intel-btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Logs
                </a>
                @if(!$securityLog->is_reviewed)
                <form method="POST" action="{{ route('admin.security-logs.mark-reviewed', $securityLog) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="intel-btn intel-btn-success">
                        <i class="fas fa-check"></i> Mark as Reviewed
                    </button>
                </form>
                @endif
                <form method="POST" action="{{ route('admin.security-logs.destroy', $securityLog) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this log?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="intel-btn intel-btn-danger">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="intel-container">
    <!-- Event Overview -->
    <div class="intel-card" style="margin-bottom: var(--space-xl);">
        <div class="intel-card-header" style="background: linear-gradient(135deg, var(--intel-blue-50), var(--intel-blue-100)); border-bottom: 2px solid var(--intel-blue-200);">
            <h2 class="intel-card-title">
                <i class="{{ $securityLog->event_icon }}"></i>
                Event Overview
            </h2>
        </div>
        <div class="intel-card-body" style="padding: 0;">
            <div class="detail-row">
                <div class="detail-label">Event Type</div>
                <div class="detail-value">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <i class="{{ $securityLog->event_icon }} {{ 
                            $securityLog->severity === 'critical' || $securityLog->severity === 'high' ? 'text-red-600' : 
                            ($securityLog->severity === 'medium' ? 'text-yellow-600' : 'text-blue-600') 
                        }}" style="font-size: 1.25rem;"></i>
                        <span style="font-weight: 600; font-size: 1.125rem;">{{ ucwords(str_replace('_', ' ', $securityLog->event_type)) }}</span>
                    </div>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Severity Level</div>
                <div class="detail-value">
                    <span class="intel-badge intel-badge-{{ 
                        $securityLog->severity === 'critical' ? 'error' : 
                        ($securityLog->severity === 'high' ? 'error' : 
                        ($securityLog->severity === 'medium' ? 'warning' : 'success')) 
                    }}" style="font-size: 1rem; padding: 0.5rem 1rem;">
                        <i class="fas fa-{{ 
                            $securityLog->severity === 'critical' ? 'exclamation-circle' : 
                            ($securityLog->severity === 'high' ? 'exclamation-triangle' : 
                            ($securityLog->severity === 'medium' ? 'exclamation' : 'check')) 
                        }}"></i>
                        {{ strtoupper($securityLog->severity) }}
                    </span>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Date & Time</div>
                <div class="detail-value">
                    <div style="font-size: 1.125rem;">{{ $securityLog->event_time->format('l, F j, Y') }}</div>
                    <div style="color: var(--intel-gray-600); margin-top: 0.25rem;">{{ $securityLog->event_time->format('h:i:s A') }} ({{ $securityLog->event_time->diffForHumans() }})</div>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Resource</div>
                <div class="detail-value">
                    @if($securityLog->resource_type && $securityLog->resource_id)
                        <div><strong>Type:</strong> {{ ucfirst($securityLog->resource_type) }}</div>
                        <div><strong>ID:</strong> #{{ $securityLog->resource_id }}</div>
                    @else
                        <span style="color: var(--intel-gray-500);">No resource associated</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-xl); margin-bottom: var(--space-xl);">
        <!-- User Information -->
        <div class="intel-card">
            <div class="intel-card-header" style="background: linear-gradient(135deg, var(--intel-green-50), var(--intel-green-100)); border-bottom: 2px solid var(--intel-green-200);">
                <h2 class="intel-card-title">
                    <i class="fas fa-user"></i>
                    User Information
                </h2>
            </div>
            <div class="intel-card-body" style="padding: 0;">
                @if($securityLog->user)
                    <div class="detail-row">
                        <div class="detail-label">User ID</div>
                        <div class="detail-value">#{{ $securityLog->user->id }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Name</div>
                        <div class="detail-value" style="font-weight: 600;">{{ $securityLog->user->name }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Email</div>
                        <div class="detail-value">
                            <a href="mailto:{{ $securityLog->user->email }}" class="intel-link">{{ $securityLog->user->email }}</a>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Roles</div>
                        <div class="detail-value">
                            @foreach($securityLog->user->roles as $role)
                                <span class="intel-badge intel-badge-info" style="margin-right: 0.5rem;">{{ $role->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div style="padding: var(--space-xl); text-align: center; color: var(--intel-gray-500);">
                        <i class="fas fa-user-slash" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                        <p style="margin: 0;">Guest / Unauthenticated User</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Network Information -->
        <div class="intel-card">
            <div class="intel-card-header" style="background: linear-gradient(135deg, var(--intel-yellow-50), #fef3c7); border-bottom: 2px solid #fcd34d;">
                <h2 class="intel-card-title">
                    <i class="fas fa-network-wired"></i>
                    Network Information
                </h2>
            </div>
            <div class="intel-card-body" style="padding: 0;">
                <div class="detail-row">
                    <div class="detail-label">IP Address</div>
                    <div class="detail-value">
                        <code style="background: var(--intel-gray-100); padding: 0.5rem 0.75rem; border-radius: var(--radius-sm); font-size: 1rem; font-weight: 600;">{{ $securityLog->ip_address }}</code>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">User Agent</div>
                    <div class="detail-value" style="font-family: monospace; font-size: 0.875rem; word-break: break-all;">
                        {{ $securityLog->user_agent ?: 'Not available' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Status -->
    <div class="intel-card" style="margin-bottom: var(--space-xl);">
        <div class="intel-card-header" style="background: linear-gradient(135deg, {{ $securityLog->is_reviewed ? 'var(--intel-green-50), var(--intel-green-100)' : '#fef3c7, #fde68a' }}); border-bottom: 2px solid {{ $securityLog->is_reviewed ? 'var(--intel-green-200)' : '#fcd34d' }};">
            <h2 class="intel-card-title">
                <i class="fas fa-{{ $securityLog->is_reviewed ? 'check-circle' : 'clock' }}"></i>
                Review Status
            </h2>
        </div>
        <div class="intel-card-body" style="padding: 0;">
            <div class="detail-row">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    @if($securityLog->is_reviewed)
                        <span class="intel-badge intel-badge-success" style="font-size: 1rem; padding: 0.5rem 1rem;">
                            <i class="fas fa-check-circle"></i> Reviewed
                        </span>
                    @else
                        <span class="intel-badge intel-badge-warning" style="font-size: 1rem; padding: 0.5rem 1rem;">
                            <i class="fas fa-clock"></i> Pending Review
                        </span>
                    @endif
                </div>
            </div>
            @if($securityLog->is_reviewed)
                <div class="detail-row">
                    <div class="detail-label">Reviewed By</div>
                    <div class="detail-value">
                        @if($securityLog->reviewedBy)
                            <div style="font-weight: 600;">{{ $securityLog->reviewedBy->name }}</div>
                            <div style="color: var(--intel-gray-600); font-size: 0.875rem;">{{ $securityLog->reviewedBy->email }}</div>
                        @else
                            <span style="color: var(--intel-gray-500);">Unknown</span>
                        @endif
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Reviewed At</div>
                    <div class="detail-value">
                        {{ $securityLog->reviewed_at ? $securityLog->reviewed_at->format('F j, Y h:i A') : 'N/A' }}
                        @if($securityLog->reviewed_at)
                            <span style="color: var(--intel-gray-600);">({{ $securityLog->reviewed_at->diffForHumans() }})</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Metadata / Additional Data -->
    @if($securityLog->metadata && count($securityLog->metadata) > 0)
    <div class="intel-card">
        <div class="intel-card-header" style="background: linear-gradient(135deg, #e0e7ff, #c7d2fe); border-bottom: 2px solid #a5b4fc;">
            <h2 class="intel-card-title">
                <i class="fas fa-database"></i>
                Additional Metadata
            </h2>
            <p class="intel-card-subtitle" style="margin-top: 0.25rem;">Event-specific data and context</p>
        </div>
        <div class="intel-card-body">
            <div class="metadata-grid">
                @foreach($securityLog->metadata as $key => $value)
                <div class="metadata-item">
                    <div class="metadata-key">{{ ucwords(str_replace('_', ' ', $key)) }}</div>
                    <div class="metadata-value">
                        @if(is_array($value))
                            <pre style="background: white; padding: 0.75rem; border-radius: var(--radius-sm); margin: 0; overflow-x: auto; font-size: 0.875rem;">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                        @else
                            {{ $value }}
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

