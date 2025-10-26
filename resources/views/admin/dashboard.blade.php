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

    <!-- Analytics & Insights -->
    <div class="intel-card">
        <div class="intel-card-header">
            <h2 class="intel-card-title">
                <i class="fas fa-chart-line"></i>
                Analytics & Insights
            </h2>
            <p class="intel-card-subtitle">Usage metrics, top content, and user activity</p>
        </div>
        <div class="intel-card-body" style="display: flex; flex-direction: column; gap: var(--space-xl);">
            <!-- Quick KPIs -->
            <div class="intel-stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
                <div class="intel-stat-card">
                    <div class="intel-stat-header">
                        <div class="intel-stat-content">
                            <h3>Total Users</h3>
                            <p class="value">{{ number_format($analytics['users']['total'] ?? 0) }}</p>
                        </div>
                        <div class="intel-stat-icon green"><i class="fas fa-users"></i></div>
                    </div>
                    <div class="intel-stat-footer">
                        <i class="fas fa-user-check text-green-600"></i>
                        <span>{{ number_format($analytics['users']['verified'] ?? 0) }} verified</span>
                        <span style="margin-left: .5rem; color: var(--intel-gray-500);">/ {{ number_format($analytics['users']['non_verified'] ?? 0) }} non-verified</span>
                    </div>
                </div>

                <div class="intel-stat-card">
                    <div class="intel-stat-header">
                        <div class="intel-stat-content">
                            <h3>Total Views</h3>
                            <p class="value">{{ number_format($analytics['views']['total'] ?? 0) }}</p>
                        </div>
                        <div class="intel-stat-icon yellow"><i class="fas fa-eye"></i></div>
                    </div>
                    <div class="intel-stat-footer">
                        <i class="fas fa-user text-blue-600"></i>
                        <span>{{ number_format($analytics['views']['unique'] ?? 0) }} unique</span>
                    </div>
                </div>
            </div>

            <!-- Time Window Metrics -->
            <div class="intel-card" style="margin: 0;">
                <div class="intel-card-header">
                    <h3 class="intel-card-title" style="font-size: 1rem;">
                        <i class="fas fa-clock"></i>
                        Activity by Time Window
                    </h3>
                </div>
                <div class="intel-card-body" style="overflow-x: auto;">
                    <table class="intel-table" style="width: 100%; min-width: 720px;">
                        <thead>
                            <tr>
                                <th style="text-align: left;">Window</th>
                                <th style="text-align: right;">Logins</th>
                                <th style="text-align: right;">PDF Downloads</th>
                                <th style="text-align: right;">Prophecy Views</th>
                                <th style="text-align: right;">Prophecy Prints</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($win = $analytics['windows'] ?? [])
                            @foreach(['today','24h','48h','72h','7d','15d','30d'] as $k)
                                @if(isset($win[$k]))
                                <tr>
                                    <td>{{ $win[$k]['label'] }}</td>
                                    <td style="text-align: right;">{{ number_format($win[$k]['logins'] ?? 0) }}</td>
                                    <td style="text-align: right;">{{ number_format($win[$k]['downloads'] ?? 0) }}</td>
                                    <td style="text-align: right;">{{ number_format($win[$k]['views'] ?? 0) }}</td>
                                    <td style="text-align: right;">{{ number_format($win[$k]['prints'] ?? 0) }}</td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Per-User Activity & Top Prophecies -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--space-xl);">
                <!-- Left: Per-User Activity -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: var(--space-lg);">
                    <div class="intel-card" style="margin: 0;">
                        <div class="intel-card-header">
                            <h3 class="intel-card-title" style="font-size: 1rem;"><i class="fas fa-sign-in-alt"></i> Logins by User</h3>
                        </div>
                        <div class="intel-card-body" style="max-height: 360px; overflow: auto;">
                            @php($items = $analytics['per_user']['logins'] ?? collect())
                            @if($items && count($items) > 0)
                                <table class="intel-table" style="width: 100%;">
                                    <thead>
                                        <tr><th>User</th><th style="text-align:right;">Logins</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $it)
                                            <tr>
                                                <td>{{ $it['name'] }}<div style="color: var(--intel-gray-500); font-size: .75rem;">{{ $it['email'] }}</div></td>
                                                <td style="text-align:right;">{{ number_format($it['total']) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p style="color: var(--intel-gray-500);">No data</p>
                            @endif
                        </div>
                    </div>

                    <div class="intel-card" style="margin: 0;">
                        <div class="intel-card-header">
                            <h3 class="intel-card-title" style="font-size: 1rem;"><i class="fas fa-download"></i> Downloads by User</h3>
                        </div>
                        <div class="intel-card-body" style="max-height: 360px; overflow: auto;">
                            @php($items = $analytics['per_user']['downloads'] ?? collect())
                            @if($items && count($items) > 0)
                                <table class="intel-table" style="width: 100%;">
                                    <thead>
                                        <tr><th>User</th><th style="text-align:right;">Downloads</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $it)
                                            <tr>
                                                <td>{{ $it['name'] }}<div style="color: var(--intel-gray-500); font-size: .75rem;">{{ $it['email'] }}</div></td>
                                                <td style="text-align:right;">{{ number_format($it['total']) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p style="color: var(--intel-gray-500);">No data</p>
                            @endif
                        </div>
                    </div>

                    <div class="intel-card" style="margin: 0;">
                        <div class="intel-card-header">
                            <h3 class="intel-card-title" style="font-size: 1rem;"><i class="fas fa-eye"></i> Views by User</h3>
                        </div>
                        <div class="intel-card-body" style="max-height: 360px; overflow: auto;">
                            @php($items = $analytics['per_user']['views'] ?? collect())
                            @if($items && count($items) > 0)
                                <table class="intel-table" style="width: 100%;">
                                    <thead>
                                        <tr><th>User</th><th style="text-align:right;">Views</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $it)
                                            <tr>
                                                <td>{{ $it['name'] }}<div style="color: var(--intel-gray-500); font-size: .75rem;">{{ $it['email'] }}</div></td>
                                                <td style="text-align:right;">{{ number_format($it['total']) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p style="color: var(--intel-gray-500);">No data</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right: Top Prophecies -->
                <div style="display: flex; flex-direction: column; gap: var(--space-lg);">
                    <div class="intel-card" style="margin: 0;">
                        <div class="intel-card-header">
                            <h3 class="intel-card-title" style="font-size: 1rem;"><i class="fas fa-arrow-up"></i> Top 5 by Downloads</h3>
                        </div>
                        <div class="intel-card-body">
                            @php($items = $analytics['top_prophecies']['downloads'] ?? collect())
                            @if($items && count($items) > 0)
                                <ol style="margin: 0; padding-left: 1rem; display: flex; flex-direction: column; gap: .5rem;">
                                    @foreach($items as $it)
                                        <li><strong>{{ $it['title'] }}</strong><span style="float:right;">{{ number_format($it['total']) }}</span></li>
                                    @endforeach
                                </ol>
                            @else
                                <p style="color: var(--intel-gray-500);">No data</p>
                            @endif
                        </div>
                    </div>

                    <div class="intel-card" style="margin: 0;">
                        <div class="intel-card-header">
                            <h3 class="intel-card-title" style="font-size: 1rem;"><i class="fas fa-arrow-up"></i> Top 5 by Views</h3>
                        </div>
                        <div class="intel-card-body">
                            @php($items = $analytics['top_prophecies']['views'] ?? collect())
                            @if($items && count($items) > 0)
                                <ol style="margin: 0; padding-left: 1rem; display: flex; flex-direction: column; gap: .5rem;">
                                    @foreach($items as $it)
                                        <li><strong>{{ $it['title'] }}</strong><span style="float:right;">{{ number_format($it['total']) }}</span></li>
                                    @endforeach
                                </ol>
                            @else
                                <p style="color: var(--intel-gray-500);">No data</p>
                            @endif
                        </div>
                    </div>

                    <div class="intel-card" style="margin: 0;">
                        <div class="intel-card-header">
                            <h3 class="intel-card-title" style="font-size: 1rem;"><i class="fas fa-arrow-up"></i> Top 5 by Prints</h3>
                        </div>
                        <div class="intel-card-body">
                            @php($items = $analytics['top_prophecies']['prints'] ?? collect())
                            @if($items && count($items) > 0)
                                <ol style="margin: 0; padding-left: 1rem; display: flex; flex-direction: column; gap: .5rem;">
                                    @foreach($items as $it)
                                        <li><strong>{{ $it['title'] }}</strong><span style="float:right;">{{ number_format($it['total']) }}</span></li>
                                    @endforeach
                                </ol>
                            @else
                                <p style="color: var(--intel-gray-500);">No data</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection