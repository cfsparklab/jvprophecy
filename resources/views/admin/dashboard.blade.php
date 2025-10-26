@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('admin-content')
<!-- Page Header -->
<div class="intel-page-header">
    <div class="intel-container">
        <h1 class="intel-page-title">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
        </h1>
        <p class="intel-page-subtitle">Welcome back, {{ auth()->user()->name }}! Here's your prophecy management overview.</p>
    </div>
</div>

<div class="intel-container">
    <!-- Statistics Overview -->
    <div class="intel-stats-grid">
        <!-- Total Prophecies -->
        <div class="intel-stat-card">
            <div class="intel-stat-header">
                <div class="intel-stat-content">
                    <h3>Total Prophecies</h3>
                    <p class="value">{{ $stats['total_prophecies'] }}</p>
                </div>
                <div class="intel-stat-icon blue">
                    <i class="fas fa-scroll"></i>
                </div>
            </div>
            <div class="intel-stat-footer">
                <i class="fas fa-check-circle text-green-600"></i>
                <span>{{ $stats['published_prophecies'] }} published</span>
            </div>
        </div>
        
        <!-- Total Users -->
        <div class="intel-stat-card">
            <div class="intel-stat-header">
                <div class="intel-stat-content">
                    <h3>Total Users</h3>
                    <p class="value">{{ $stats['total_users'] }}</p>
                </div>
                <div class="intel-stat-icon green">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="intel-stat-footer">
                <i class="fas fa-user-check text-green-600"></i>
                <span>{{ $stats['active_users'] }} active</span>
            </div>
        </div>
        
        <!-- Total Views -->
        <div class="intel-stat-card">
            <div class="intel-stat-header">
                <div class="intel-stat-content">
                    <h3>Total Views</h3>
                    <p class="value">{{ number_format($stats['total_views'] ?? 0) }}</p>
                </div>
                <div class="intel-stat-icon yellow">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
            <div class="intel-stat-footer">
                <i class="fas fa-download text-blue-600"></i>
                <span>{{ number_format($stats['total_downloads'] ?? 0) }} downloads</span>
            </div>
        </div>
        
        <!-- Security Events -->
        <div class="intel-stat-card">
            <div class="intel-stat-header">
                <div class="intel-stat-content">
                    <h3>Security Events</h3>
                    <p class="value">{{ number_format($stats['total_security_events'] ?? 0) }}</p>
                </div>
                <div class="intel-stat-icon red">
                    <i class="fas fa-shield-alt"></i>
                </div>
            </div>
            <div class="intel-stat-footer">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
                <span>{{ $stats['high_severity_events'] ?? 0 }} high priority</span>
            </div>
        </div>
    </div>
    
    <!-- Main Content Grid -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--space-xl); margin-bottom: var(--space-xl);">
        <!-- Recent Activities -->
        <div class="intel-card">
            <div class="intel-card-header">
                <h2 class="intel-card-title">
                    <i class="fas fa-clock"></i>
                    Recent Activities
                </h2>
                <p class="intel-card-subtitle">Latest system activities and updates</p>
            </div>
            <div class="intel-card-body">
                @if($recentActivities && $recentActivities->count() > 0)
                <div style="display: flex; flex-direction: column; gap: var(--space-md);">
                    @foreach($recentActivities as $activity)
                    <!-- Activity Item -->
                    <div style="display: flex; align-items: start; gap: var(--space-md); padding: var(--space-md); background: var(--intel-gray-50); border-radius: var(--radius-md);">
                        <div class="intel-stat-icon {{ 
                            $activity['event_type'] == 'login_success' ? 'green' : 
                            ($activity['event_type'] == 'prophecy_view' ? 'blue' : 
                            ($activity['event_type'] == 'prophecy_download' ? 'yellow' : 
                            ($activity['event_type'] == 'registration_success' ? 'green' : 'blue'))) 
                        }}" style="width: 40px; height: 40px; font-size: 1rem;">
                            <i class="fas {{ 
                                $activity['event_type'] == 'login_success' ? 'fa-sign-in-alt' : 
                                ($activity['event_type'] == 'prophecy_view' ? 'fa-eye' : 
                                ($activity['event_type'] == 'prophecy_download' ? 'fa-download' : 
                                ($activity['event_type'] == 'prophecy_print' ? 'fa-print' : 
                                ($activity['event_type'] == 'registration_success' ? 'fa-user-plus' : 'fa-info-circle')))) 
                            }}"></i>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="margin: 0; font-size: 0.875rem; font-weight: 600; color: var(--intel-gray-900);">
                                {{ ucwords(str_replace('_', ' ', $activity['event_type'])) }}
                            </h4>
                            <p style="margin: var(--space-xs) 0 0 0; font-size: 0.75rem; color: var(--intel-gray-600);">
                                {{ $activity['user_name'] }} 
                                @if(isset($activity['metadata']['prophecy_title']))
                                    - {{ $activity['metadata']['prophecy_title'] }}
                                @endif
                            </p>
                            <p style="margin: var(--space-xs) 0 0 0; font-size: 0.75rem; color: var(--intel-gray-500);">
                                {{ \Carbon\Carbon::parse($activity['event_time'])->diffForHumans() }}
                                <span style="margin-left: 0.5rem; color: var(--intel-gray-400);">• {{ $activity['ip_address'] }}</span>
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div style="text-align: center; padding: var(--space-xl); color: var(--intel-gray-500);">
                    <i class="fas fa-info-circle" style="font-size: 2rem; margin-bottom: var(--space-md); opacity: 0.5;"></i>
                    <p style="margin: 0;">No recent activities to display</p>
                </div>
                @endif
            </div>
            <div class="intel-card-footer">
                <a href="#" class="intel-btn intel-btn-secondary intel-btn-sm">
                    <i class="fas fa-history"></i>
                    View All Activities
                </a>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="intel-card">
            <div class="intel-card-header">
                <h2 class="intel-card-title">
                    <i class="fas fa-bolt"></i>
                    Quick Actions
                </h2>
                <p class="intel-card-subtitle">Frequently used actions</p>
            </div>
            <div class="intel-card-body">
                <div style="display: flex; flex-direction: column; gap: var(--space-md);">
                    <a href="{{ route('admin.prophecies.create') }}" class="intel-btn intel-btn-primary">
                        <i class="fas fa-plus"></i>
                        Create New Prophecy
                    </a>
                    
                    <a href="{{ route('admin.prophecies.index') }}" class="intel-btn intel-btn-secondary">
                        <i class="fas fa-list"></i>
                        Manage Prophecies
                    </a>
                    
                    <a href="{{ route('admin.users.index') }}" class="intel-btn intel-btn-secondary">
                        <i class="fas fa-users"></i>
                        Manage Users
                    </a>
                    
                    <a href="{{ route('admin.categories.index') }}" class="intel-btn intel-btn-secondary">
                        <i class="fas fa-tags"></i>
                        Manage Categories
                    </a>
                    
                    <a href="{{ route('admin.settings.index') }}" class="intel-btn intel-btn-secondary">
                        <i class="fas fa-cog"></i>
                        System Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- System Status -->
    <div class="intel-card">
        <div class="intel-card-header">
            <h2 class="intel-card-title">
                <i class="fas fa-server"></i>
                System Status
            </h2>
            <p class="intel-card-subtitle">Current system health and performance metrics</p>
        </div>
        <div class="intel-card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-lg);">
                <!-- Database Status -->
                <div style="text-align: center; padding: var(--space-md);">
                    <div class="intel-stat-icon {{ $systemStatus['database']['status'] == 'operational' ? 'green' : 'red' }}" style="margin: 0 auto var(--space-sm) auto;">
                        <i class="fas fa-database"></i>
                    </div>
                    <h4 style="margin: 0; font-size: 0.875rem; font-weight: 600; color: var(--intel-gray-900);">Database</h4>
                    <p style="margin: var(--space-xs) 0 0 0; font-size: 0.75rem; color: {{ $systemStatus['database']['status'] == 'operational' ? 'var(--success-color)' : 'var(--error-color)' }}; font-weight: 600;">
                        {{ $systemStatus['database']['label'] }}
                    </p>
                </div>
                
                <!-- Cache Status -->
                <div style="text-align: center; padding: var(--space-md);">
                    <div class="intel-stat-icon {{ $systemStatus['cache']['status'] == 'optimal' ? 'green' : 'red' }}" style="margin: 0 auto var(--space-sm) auto;">
                        <i class="fas fa-memory"></i>
                    </div>
                    <h4 style="margin: 0; font-size: 0.875rem; font-weight: 600; color: var(--intel-gray-900);">Cache</h4>
                    <p style="margin: var(--space-xs) 0 0 0; font-size: 0.75rem; color: {{ $systemStatus['cache']['status'] == 'optimal' ? 'var(--success-color)' : 'var(--error-color)' }}; font-weight: 600;">
                        {{ $systemStatus['cache']['label'] }}
                    </p>
                </div>
                
                <!-- Storage Status -->
                <div style="text-align: center; padding: var(--space-md);">
                    <div class="intel-stat-icon {{ 
                        $systemStatus['storage']['status'] == 'optimal' ? 'green' : 
                        ($systemStatus['storage']['status'] == 'warning' ? 'yellow' : 'red') 
                    }}" style="margin: 0 auto var(--space-sm) auto;">
                        <i class="fas fa-hdd"></i>
                    </div>
                    <h4 style="margin: 0; font-size: 0.875rem; font-weight: 600; color: var(--intel-gray-900);">Storage</h4>
                    <p style="margin: var(--space-xs) 0 0 0; font-size: 0.75rem; color: {{ 
                        $systemStatus['storage']['status'] == 'optimal' ? 'var(--success-color)' : 
                        ($systemStatus['storage']['status'] == 'warning' ? 'var(--warning-color)' : 'var(--error-color)') 
                    }}; font-weight: 600;">
                        {{ $systemStatus['storage']['label'] }}
                    </p>
                </div>
                
                <!-- App Status -->
                <div style="text-align: center; padding: var(--space-md);">
                    <div class="intel-stat-icon {{ $systemStatus['app']['status'] == 'production' ? 'green' : 'yellow' }}" style="margin: 0 auto var(--space-sm) auto;">
                        <i class="fas fa-{{ $systemStatus['app']['status'] == 'production' ? 'check-circle' : 'exclamation-triangle' }}"></i>
                    </div>
                    <h4 style="margin: 0; font-size: 0.875rem; font-weight: 600; color: var(--intel-gray-900);">Application</h4>
                    <p style="margin: var(--space-xs) 0 0 0; font-size: 0.75rem; color: {{ $systemStatus['app']['status'] == 'production' ? 'var(--success-color)' : 'var(--warning-color)' }}; font-weight: 600;">
                        {{ $systemStatus['app']['label'] }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Link -->
    <div class="intel-card">
        <div class="intel-card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h2 class="intel-card-title">
                    <i class="fas fa-chart-line"></i>
                    Analytics
                </h2>
                <p class="intel-card-subtitle">View detailed stats and export reports</p>
            </div>
            <a href="{{ route('admin.analytics.index') }}" class="intel-btn intel-btn-primary">
                <i class="fas fa-external-link-alt"></i>
                Open Analytics
            </a>
        </div>
    </div>
</div>
@endsection