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
        <p class="intel-page-subtitle">Welcome back, {{ auth()->user()->name ?? 'Super Administrator' }}! Here's your prophecy management overview.</p>
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
                    <p class="value">{{ $stats['total_prophecies'] ?? 9 }}</p>
                </div>
                <div class="intel-stat-icon blue">
                    <i class="fas fa-scroll"></i>
                </div>
            </div>
            <div class="intel-stat-footer">
                <i class="fas fa-check-circle text-green-600"></i>
                <span>{{ $stats['published_prophecies'] ?? 9 }} published</span>
            </div>
        </div>
        
        <!-- Total Users -->
        <div class="intel-stat-card">
            <div class="intel-stat-header">
                <div class="intel-stat-content">
                    <h3>Total Users</h3>
                    <p class="value">{{ $stats['total_users'] ?? 13 }}</p>
                </div>
                <div class="intel-stat-icon green">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="intel-stat-footer">
                <i class="fas fa-user-check text-green-600"></i>
                <span>{{ $stats['active_users'] ?? 11 }} active</span>
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
                <div style="display: flex; flex-direction: column; gap: var(--space-md);">
                    <!-- Activity Item -->
                    <div style="display: flex; align-items: start; gap: var(--space-md); padding: var(--space-md); background: var(--intel-gray-50); border-radius: var(--radius-md);">
                        <div class="intel-stat-icon blue" style="width: 40px; height: 40px; font-size: 1rem;">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="margin: 0; font-size: 0.875rem; font-weight: 600; color: var(--intel-gray-900);">New Prophecy Created</h4>
                            <p style="margin: var(--space-xs) 0 0 0; font-size: 0.75rem; color: var(--intel-gray-600);">"Season of Breakthrough" was published</p>
                            <p style="margin: var(--space-xs) 0 0 0; font-size: 0.75rem; color: var(--intel-gray-500);">2 hours ago</p>
                        </div>
                    </div>
                    
                    <!-- Activity Item -->
                    <div style="display: flex; align-items: start; gap: var(--space-md); padding: var(--space-md); background: var(--intel-gray-50); border-radius: var(--radius-md);">
                        <div class="intel-stat-icon green" style="width: 40px; height: 40px; font-size: 1rem;">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="margin: 0; font-size: 0.875rem; font-weight: 600; color: var(--intel-gray-900);">New User Registered</h4>
                            <p style="margin: var(--space-xs) 0 0 0; font-size: 0.75rem; color: var(--intel-gray-600);">John Doe joined the platform</p>
                            <p style="margin: var(--space-xs) 0 0 0; font-size: 0.75rem; color: var(--intel-gray-500);">4 hours ago</p>
                        </div>
                    </div>
                    
                    <!-- Activity Item -->
                    <div style="display: flex; align-items: start; gap: var(--space-md); padding: var(--space-md); background: var(--intel-gray-50); border-radius: var(--radius-md);">
                        <div class="intel-stat-icon yellow" style="width: 40px; height: 40px; font-size: 1rem;">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="margin: 0; font-size: 0.875rem; font-weight: 600; color: var(--intel-gray-900);">Prophecy Updated</h4>
                            <p style="margin: var(--space-xs) 0 0 0; font-size: 0.75rem; color: var(--intel-gray-600);">"The Coming Revival - Part 2" was modified</p>
                            <p style="margin: var(--space-xs) 0 0 0; font-size: 0.75rem; color: var(--intel-gray-500);">6 hours ago</p>
                        </div>
                    </div>
                    
                    <!-- Activity Item -->
                    <div style="display: flex; align-items: start; gap: var(--space-md); padding: var(--space-md); background: var(--intel-gray-50); border-radius: var(--radius-md);">
                        <div class="intel-stat-icon red" style="width: 40px; height: 40px; font-size: 1rem;">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="margin: 0; font-size: 0.875rem; font-weight: 600; color: var(--intel-gray-900);">Security Event</h4>
                            <p style="margin: var(--space-xs) 0 0 0; font-size: 0.75rem; color: var(--intel-gray-600);">Failed login attempt detected</p>
                            <p style="margin: var(--space-xs) 0 0 0; font-size: 0.75rem; color: var(--intel-gray-500);">8 hours ago</p>
                        </div>
                    </div>
                </div>
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
                    <div class="intel-stat-icon green" style="margin: 0 auto var(--space-sm) auto;">
                        <i class="fas fa-database"></i>
                    </div>
                    <h4 style="margin: 0; font-size: 0.875rem; font-weight: 600; color: var(--intel-gray-900);">Database</h4>
                    <p style="margin: var(--space-xs) 0 0 0; font-size: 0.75rem; color: var(--success-color); font-weight: 600;">Operational</p>
                </div>
                
                <!-- Cache Status -->
                <div style="text-align: center; padding: var(--space-md);">
                    <div class="intel-stat-icon green" style="margin: 0 auto var(--space-sm) auto;">
                        <i class="fas fa-memory"></i>
                    </div>
                    <h4 style="margin: 0; font-size: 0.875rem; font-weight: 600; color: var(--intel-gray-900);">Cache</h4>
                    <p style="margin: var(--space-xs) 0 0 0; font-size: 0.75rem; color: var(--success-color); font-weight: 600;">Optimal</p>
                </div>
                
                <!-- Storage Status -->
                <div style="text-align: center; padding: var(--space-md);">
                    <div class="intel-stat-icon yellow" style="margin: 0 auto var(--space-sm) auto;">
                        <i class="fas fa-hdd"></i>
                    </div>
                    <h4 style="margin: 0; font-size: 0.875rem; font-weight: 600; color: var(--intel-gray-900);">Storage</h4>
                    <p style="margin: var(--space-xs) 0 0 0; font-size: 0.75rem; color: var(--warning-color); font-weight: 600;">75% Used</p>
                </div>
                
                <!-- API Status -->
                <div style="text-align: center; padding: var(--space-md);">
                    <div class="intel-stat-icon green" style="margin: 0 auto var(--space-sm) auto;">
                        <i class="fas fa-plug"></i>
                    </div>
                    <h4 style="margin: 0; font-size: 0.875rem; font-weight: 600; color: var(--intel-gray-900);">API</h4>
                    <p style="margin: var(--space-xs) 0 0 0; font-size: 0.75rem; color: var(--success-color); font-weight: 600;">Healthy</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection