@extends('layouts.admin')

@section('page-title', 'Analytics & Insights')

@section('admin-content')
<style>
    .analytics-tab {
        padding: 0.75rem 1.5rem;
        border: none;
        background: transparent;
        color: var(--intel-gray-600);
        font-weight: 600;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: all 0.2s;
    }
    .analytics-tab:hover {
        color: var(--intel-blue-600);
        background: var(--intel-gray-50);
    }
    .analytics-tab.active {
        color: var(--intel-blue-600);
        border-bottom-color: var(--intel-blue-600);
    }
    .analytics-filter-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: var(--radius-lg);
        padding: var(--space-xl);
        color: white;
        margin-bottom: var(--space-xl);
    }
    .chart-container {
        position: relative;
        height: 300px;
        margin-top: var(--space-md);
    }
    .metric-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        font-weight: 600;
    }
    .metric-badge.success { background: #d1fae5; color: #065f46; }
    .metric-badge.warning { background: #fef3c7; color: #92400e; }
    .metric-badge.info { background: #dbeafe; color: #1e40af; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

<!-- Page Header with Filters -->
<div class="analytics-filter-card">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: var(--space-lg);">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 700; margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-chart-line"></i>
                Analytics & Insights
            </h1>
            <p style="margin: 0; opacity: 0.9;">Comprehensive metrics and reporting for data-driven decisions</p>
        </div>
        <div style="display: flex; gap: var(--space-md); flex-wrap: wrap;">
            <select id="date-range" class="intel-form-select" style="background: white; min-width: 180px;" onchange="filterByDateRange()">
                <option value="today">Today</option>
                <option value="24h">Last 24 Hours</option>
                <option value="7d" selected>Last 7 Days</option>
                <option value="30d">Last 30 Days</option>
                <option value="90d">Last 90 Days</option>
                <option value="custom">Custom Range</option>
            </select>
            <button class="intel-btn intel-btn-secondary" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <button class="intel-btn intel-btn-secondary" onclick="exportFullReport()">
                <i class="fas fa-file-export"></i> Export All
            </button>
        </div>
    </div>
</div>

<div class="intel-container">
    <!-- Tabs -->
    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); margin-bottom: var(--space-xl);">
        <div style="display: flex; border-bottom: 1px solid var(--intel-gray-200); overflow-x: auto;">
            <button class="analytics-tab active" onclick="switchTab(event, 'overview')">
                <i class="fas fa-dashboard"></i> Overview
            </button>
            <button class="analytics-tab" onclick="switchTab(event, 'users')">
                <i class="fas fa-users"></i> Users
            </button>
            <button class="analytics-tab" onclick="switchTab(event, 'content')">
                <i class="fas fa-scroll"></i> Content
            </button>
            <button class="analytics-tab" onclick="switchTab(event, 'activity')">
                <i class="fas fa-chart-line"></i> Activity
            </button>
        </div>
        
        <!-- Tab Content: Overview -->
        <div id="tab-overview" class="tab-content active" style="padding: var(--space-xl);">
            <!-- KPI Cards -->
            <div class="intel-stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); margin-bottom: var(--space-xl);">
                <div class="intel-stat-card">
                    <div class="intel-stat-header">
                        <div class="intel-stat-content">
                            <h3>Total Users</h3>
                            <p class="value">{{ number_format($analytics['users']['total'] ?? 0) }}</p>
                        </div>
                        <div class="intel-stat-icon green"><i class="fas fa-users"></i></div>
                    </div>
                    <div class="intel-stat-footer">
                        <span class="metric-badge success">
                            <i class="fas fa-user-check"></i>
                            {{ number_format($analytics['users']['verified'] ?? 0) }} verified
                        </span>
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
                        <span class="metric-badge info">
                            <i class="fas fa-user"></i>
                            {{ number_format($analytics['views']['unique'] ?? 0) }} unique
                        </span>
                    </div>
                </div>
                <div class="intel-stat-card">
                    <div class="intel-stat-header">
                        <div class="intel-stat-content">
                            <h3>Downloads (7d)</h3>
                            <p class="value">{{ number_format($analytics['windows']['7d']['downloads'] ?? 0) }}</p>
                        </div>
                        <div class="intel-stat-icon blue"><i class="fas fa-download"></i></div>
                    </div>
                    <div class="intel-stat-footer">
                        <span style="color: var(--intel-gray-600); font-size: 0.875rem;">
                            <i class="fas fa-calendar-day"></i> Last 7 days
                        </span>
                    </div>
                </div>
                <div class="intel-stat-card">
                    <div class="intel-stat-header">
                        <div class="intel-stat-content">
                            <h3>Active Sessions</h3>
                            <p class="value">{{ number_format($analytics['windows']['today']['logins'] ?? 0) }}</p>
                        </div>
                        <div class="intel-stat-icon green"><i class="fas fa-sign-in-alt"></i></div>
                    </div>
                    <div class="intel-stat-footer">
                        <span style="color: var(--intel-gray-600); font-size: 0.875rem;">
                            <i class="fas fa-clock"></i> Today
                        </span>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: var(--space-xl); margin-bottom: var(--space-xl);">
                <div class="intel-card" id="activity-trend-widget">
                    <div class="intel-card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600;"><i class="fas fa-chart-area"></i> Activity Trend</h3>
                        <button class="intel-btn intel-btn-secondary intel-btn-sm" onclick="exportWidgetToPng('activity-trend-widget', 'activity-trend.png')">
                            <i class="fas fa-image"></i>
                        </button>
                    </div>
                    <div class="intel-card-body">
                        <div class="chart-container">
                            <canvas id="activityChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="intel-card" id="user-growth-widget">
                    <div class="intel-card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600;"><i class="fas fa-user-plus"></i> User Growth</h3>
                        <button class="intel-btn intel-btn-secondary intel-btn-sm" onclick="exportWidgetToPng('user-growth-widget', 'user-growth.png')">
                            <i class="fas fa-image"></i>
                        </button>
                    </div>
                    <div class="intel-card-body">
                        <div class="chart-container">
                            <canvas id="userChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time Windows Table -->
            <div class="intel-card" id="windows-widget">
                <div class="intel-card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h2 class="intel-card-title"><i class="fas fa-clock"></i> Activity by Time Window</h2>
                        <p class="intel-card-subtitle" style="margin-top: 0.25rem;">Granular breakdown of user activity across different time periods</p>
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <button class="intel-btn intel-btn-secondary intel-btn-sm" onclick="exportWidgetToPng('windows-widget', 'activity-windows.png')">
                            <i class="fas fa-image"></i>
                        </button>
                        <a class="intel-btn intel-btn-secondary intel-btn-sm" href="{{ route('admin.analytics.export', ['type' => 'windows']) }}">
                            <i class="fas fa-file-excel"></i>
                        </a>
                    </div>
                </div>
                <div class="intel-card-body" style="overflow-x: auto;">
                    <table class="intel-table" style="width: 100%; min-width: 720px;">
                        <thead>
                            <tr>
                                <th>Time Window</th>
                                <th style="text-align: right;">Logins</th>
                                <th style="text-align: right;">PDF Downloads</th>
                                <th style="text-align: right;">Prophecy Views</th>
                                <th style="text-align: right;">Prints</th>
                                <th style="text-align: right;">Total Activity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($win = $analytics['windows'] ?? [])
                            @foreach(['today','24h','48h','72h','7d','15d','30d'] as $k)
                                @if(isset($win[$k]))
                                @php($total = ($win[$k]['logins'] + $win[$k]['downloads'] + $win[$k]['views'] + $win[$k]['prints']))
                                <tr>
                                    <td><strong>{{ $win[$k]['label'] }}</strong></td>
                                    <td style="text-align: right;">{{ number_format($win[$k]['logins'] ?? 0) }}</td>
                                    <td style="text-align: right;">{{ number_format($win[$k]['downloads'] ?? 0) }}</td>
                                    <td style="text-align: right;">{{ number_format($win[$k]['views'] ?? 0) }}</td>
                                    <td style="text-align: right;">{{ number_format($win[$k]['prints'] ?? 0) }}</td>
                                    <td style="text-align: right;"><strong>{{ number_format($total) }}</strong></td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab Content: Users -->
        <div id="tab-users" class="tab-content" style="padding: var(--space-xl); display: none;">
            <div style="margin-bottom: var(--space-xl);">
                <h2 style="margin: 0 0 0.5rem 0; font-size: 1.5rem; font-weight: 700;">User Activity Breakdown</h2>
                <p style="margin: 0; color: var(--intel-gray-600);">Detailed per-user activity metrics showing engagement patterns</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(380px, 1fr)); gap: var(--space-xl);">
                @php($sets = [
                    ['key' => 'logins', 'title' => 'Logins by User', 'icon' => 'sign-in-alt', 'color' => 'blue', 'export' => route('admin.analytics.export', ['type' => 'logins_by_user'])],
                    ['key' => 'downloads', 'title' => 'Downloads by User', 'icon' => 'download', 'color' => 'green', 'export' => route('admin.analytics.export', ['type' => 'downloads_by_user'])],
                    ['key' => 'views', 'title' => 'Views by User', 'icon' => 'eye', 'color' => 'yellow', 'export' => route('admin.analytics.export', ['type' => 'views_by_user'])],
                ])
                @foreach($sets as $set)
                <div class="intel-card">
                    <div class="intel-card-header" style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-lg); background: linear-gradient(135deg, var(--intel-{{ $set['color'] }}-50), var(--intel-{{ $set['color'] }}-100)); border-bottom: 2px solid var(--intel-{{ $set['color'] }}-200);">
                        <div>
                            <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: var(--intel-gray-900);">
                                <i class="fas fa-{{ $set['icon'] }}" style="color: var(--intel-{{ $set['color'] }}-600);"></i> {{ $set['title'] }}
                            </h3>
                            <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; color: var(--intel-gray-600);">Top 50 users by activity</p>
                        </div>
                        <a class="intel-btn intel-btn-secondary intel-btn-sm" href="{{ $set['export'] }}" title="Export to Excel">
                            <i class="fas fa-file-excel"></i> Export
                        </a>
                    </div>
                    <div class="intel-card-body" style="padding: 0; max-height: 560px; overflow-y: auto;">
                        @php($items = $analytics['per_user'][$set['key']] ?? collect())
                        @if($items && count($items) > 0)
                            <table class="intel-table" style="width: 100%; margin: 0;">
                                <thead style="position: sticky; top: 0; background: white; z-index: 1; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                    <tr>
                                        <th style="text-align: left; padding: 1rem;">User Details</th>
                                        <th style="text-align: right; padding: 1rem;">Activity Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $idx => $it)
                                    <tr style="border-bottom: 1px solid var(--intel-gray-100);">
                                        <td style="padding: 1rem;">
                                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                                <div style="flex-shrink: 0; width: 36px; height: 36px; background: linear-gradient(135deg, var(--intel-{{ $set['color'] }}-500), var(--intel-{{ $set['color'] }}-600)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.875rem;">
                                                    {{ substr($it['name'], 0, 1) }}
                                                </div>
                                                <div>
                                                    <div style="font-weight: 600; color: var(--intel-gray-900);">{{ $it['name'] }}</div>
                                                    <div style="color: var(--intel-gray-500); font-size: 0.8125rem;">{{ $it['email'] }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="text-align: right; padding: 1rem;">
                                            <span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: var(--intel-{{ $set['color'] }}-50); color: var(--intel-{{ $set['color'] }}-700); border-radius: var(--radius-md); font-weight: 600;">
                                                <i class="fas fa-{{ $set['icon'] }}"></i>
                                                {{ number_format($it['total']) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div style="text-align: center; padding: var(--space-xl); color: var(--intel-gray-500);">
                                <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: var(--space-md); opacity: 0.3;"></i>
                                <p style="margin: 0; font-weight: 500;">No activity data available</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Tab Content: Content -->
        <div id="tab-content" class="tab-content" style="padding: var(--space-xl); display: none;">
            <div style="margin-bottom: var(--space-xl);">
                <h2 style="margin: 0 0 0.5rem 0; font-size: 1.5rem; font-weight: 700;">Top Performing Content</h2>
                <p style="margin: 0; color: var(--intel-gray-600);">Most popular prophecies ranked by user engagement metrics</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: var(--space-xl);">
                @php($tops = [
                    ['key' => 'downloads', 'title' => 'Top 5 by Downloads', 'icon' => 'download', 'color' => 'blue', 'export' => route('admin.analytics.export', ['type' => 'top_downloads'])],
                    ['key' => 'views', 'title' => 'Top 5 by Views', 'icon' => 'eye', 'color' => 'yellow', 'export' => route('admin.analytics.export', ['type' => 'top_views'])],
                    ['key' => 'prints', 'title' => 'Top 5 by Prints', 'icon' => 'print', 'color' => 'green', 'export' => route('admin.analytics.export', ['type' => 'top_prints'])],
                ])
                @foreach($tops as $t)
                <div class="intel-card">
                    <div class="intel-card-header" style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-lg); background: linear-gradient(135deg, var(--intel-{{ $t['color'] }}-50), var(--intel-{{ $t['color'] }}-100)); border-bottom: 2px solid var(--intel-{{ $t['color'] }}-200);">
                        <div>
                            <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: var(--intel-gray-900);">
                                <i class="fas fa-{{ $t['icon'] }}" style="color: var(--intel-{{ $t['color'] }}-600);"></i> {{ $t['title'] }}
                            </h3>
                            <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; color: var(--intel-gray-600);">Most {{ strtolower($t['key']) }} content</p>
                        </div>
                        <a class="intel-btn intel-btn-secondary intel-btn-sm" href="{{ $t['export'] }}" title="Export to Excel">
                            <i class="fas fa-file-excel"></i> Export
                        </a>
                    </div>
                    <div class="intel-card-body" style="padding: var(--space-lg);">
                        @php($items = $analytics['top_prophecies'][$t['key']] ?? collect())
                        @if($items && count($items) > 0)
                            <div style="display: flex; flex-direction: column; gap: var(--space-lg);">
                                @foreach($items as $idx => $it)
                                <div style="display: flex; align-items: center; gap: var(--space-lg); padding: var(--space-lg); background: linear-gradient(135deg, var(--intel-gray-50), white); border-radius: var(--radius-lg); border: 1px solid var(--intel-gray-200); transition: all 0.2s;">
                                    <div style="flex-shrink: 0; width: 56px; height: 56px; background: linear-gradient(135deg, var(--intel-{{ $t['color'] }}-500), var(--intel-{{ $t['color'] }}-600)); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                                        {{ $idx + 1 }}
                                    </div>
                                    <div style="flex: 1; min-width: 0;">
                                        <div style="font-weight: 600; font-size: 1rem; color: var(--intel-gray-900); margin-bottom: 0.25rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ $it['title'] }}
                                        </div>
                                        <div style="display: flex; gap: 1rem; font-size: 0.8125rem; color: var(--intel-gray-600);">
                                            <span><i class="fas fa-hashtag"></i> ID: {{ $it['prophecy_id'] }}</span>
                                            <span><i class="fas fa-eye"></i> {{ number_format($it['view_count']) }}</span>
                                            <span><i class="fas fa-download"></i> {{ number_format($it['download_count']) }}</span>
                                        </div>
                                    </div>
                                    <div style="text-align: right;">
                                        <div style="padding: 0.5rem 1rem; background: white; border: 2px solid var(--intel-{{ $t['color'] }}-200); border-radius: var(--radius-md); box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                            <div style="font-weight: 700; font-size: 1.5rem; color: var(--intel-{{ $t['color'] }}-600); line-height: 1;">{{ number_format($it['total']) }}</div>
                                            <div style="font-size: 0.75rem; color: var(--intel-gray-600); margin-top: 0.125rem; text-transform: uppercase; letter-spacing: 0.025em;">{{ ucfirst($t['key']) }}</div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div style="text-align: center; padding: var(--space-xl); color: var(--intel-gray-500);">
                                <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: var(--space-md); opacity: 0.3;"></i>
                                <p style="margin: 0; font-weight: 500;">No content data available</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Tab Content: Activity -->
        <div id="tab-activity" class="tab-content" style="padding: var(--space-xl); display: none;">
            <h2 style="margin: 0 0 var(--space-lg) 0; font-size: 1.25rem;">Detailed Activity Logs</h2>
            <div class="intel-card">
                <div class="intel-card-header">
                    <p style="margin: 0; color: var(--intel-gray-600);">Real-time activity monitoring and event tracking</p>
                </div>
                <div class="intel-card-body">
                    <p style="text-align: center; color: var(--intel-gray-500); padding: var(--space-xl);">
                        <i class="fas fa-info-circle" style="font-size: 2rem; margin-bottom: var(--space-md); opacity: 0.5;"></i><br>
                        Visit <a href="{{ route('admin.security-logs.index') }}" class="intel-link">Security Logs</a> for detailed activity tracking
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Tab Switching
function switchTab(event, tabName) {
    document.querySelectorAll('.analytics-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
    event.target.classList.add('active');
    document.getElementById('tab-' + tabName).style.display = 'block';
}

// Export Functions
function exportWidgetToPng(id, filename) {
    const el = document.getElementById(id);
    if (!el) return;
    html2canvas(el, {backgroundColor: '#ffffff', scale: 2}).then(canvas => {
        const link = document.createElement('a');
        link.download = filename || (id + '.png');
        link.href = canvas.toDataURL('image/png');
        link.click();
    });
}

function exportFullReport() {
    alert('Full report export will download all analytics data. This feature is coming soon!');
}

function filterByDateRange() {
    const val = document.getElementById('date-range').value;
    if (val === 'custom') {
        alert('Custom date range picker coming soon!');
    } else {
        // In production, this would reload with query params
        console.log('Filtering by:', val);
    }
}

// Charts
const activityData = @json(array_values($analytics['windows']));
const labels = activityData.map(w => w.label);

// Activity Trend Chart
const activityCtx = document.getElementById('activityChart').getContext('2d');
new Chart(activityCtx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Views',
            data: activityData.map(w => w.views),
            borderColor: 'rgb(234, 179, 8)',
            backgroundColor: 'rgba(234, 179, 8, 0.1)',
            tension: 0.4
        }, {
            label: 'Downloads',
            data: activityData.map(w => w.downloads),
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4
        }, {
            label: 'Prints',
            data: activityData.map(w => w.prints),
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'top' },
            title: { display: false }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// User Growth Chart (Logins as proxy)
const userCtx = document.getElementById('userChart').getContext('2d');
new Chart(userCtx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Logins',
            data: activityData.map(w => w.logins),
            backgroundColor: 'rgba(102, 126, 234, 0.8)',
            borderColor: 'rgb(102, 126, 234)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            title: { display: false }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
@endsection
