@extends('layouts.admin')

@section('page-title', 'Analytics')

@section('admin-content')
<div class="intel-page-header">
    <div class="intel-container">
        <h1 class="intel-page-title">
            <i class="fas fa-chart-line"></i>
            Analytics
        </h1>
        <p class="intel-page-subtitle">Detailed usage metrics and insights</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script>
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
        function exportTableToCsv(tableId, filename) {
            const table = document.getElementById(tableId);
            if (!table) return;
            let csv = '';
            const rows = table.querySelectorAll('tr');
            rows.forEach(row => {
                const cols = row.querySelectorAll('th,td');
                const line = Array.from(cols).map(td => '"' + (td.innerText || '').replaceAll('"', '""') + '"').join(',');
                csv += line + '\n';
            });
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = filename;
            link.click();
            URL.revokeObjectURL(link.href);
        }
    </script>
</div>

<div class="intel-container" style="display: flex; flex-direction: column; gap: var(--space-xl);">
    <!-- KPIs -->
    <div class="intel-stats-grid" id="kpi-widget" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
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
        <div style="display:flex; align-items:center; gap:.5rem;">
            <button class="intel-btn intel-btn-secondary intel-btn-sm" onclick="exportWidgetToPng('kpi-widget','analytics-kpis.png')"><i class="fas fa-image"></i> Export PNG</button>
        </div>
    </div>

    <!-- Time Windows -->
    <div class="intel-card" id="windows-widget">
        <div class="intel-card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h2 class="intel-card-title"><i class="fas fa-clock"></i> Activity by Time Window</h2>
                <p class="intel-card-subtitle">Today, 24h, 48h, 72h, 7d, 15d, 30d</p>
            </div>
            <div style="display:flex; gap:.5rem;">
                <button class="intel-btn intel-btn-secondary intel-btn-sm" onclick="exportWidgetToPng('windows-widget','analytics-windows.png')"><i class="fas fa-image"></i> PNG</button>
                <a class="intel-btn intel-btn-secondary intel-btn-sm" href="{{ route('admin.analytics.export', ['type' => 'windows']) }}"><i class="fas fa-file-excel"></i> Excel</a>
            </div>
        </div>
        <div class="intel-card-body" style="overflow-x:auto;">
            <table id="windows-table" class="intel-table" style="width:100%; min-width: 720px;">
                <thead>
                    <tr>
                        <th>Window</th>
                        <th style="text-align:right;">Logins</th>
                        <th style="text-align:right;">Downloads</th>
                        <th style="text-align:right;">Views</th>
                        <th style="text-align:right;">Prints</th>
                    </tr>
                </thead>
                <tbody>
                    @php($win = $analytics['windows'] ?? [])
                    @foreach(['today','24h','48h','72h','7d','15d','30d'] as $k)
                        @if(isset($win[$k]))
                        <tr>
                            <td>{{ $win[$k]['label'] }}</td>
                            <td style="text-align:right;">{{ number_format($win[$k]['logins'] ?? 0) }}</td>
                            <td style="text-align:right;">{{ number_format($win[$k]['downloads'] ?? 0) }}</td>
                            <td style="text-align:right;">{{ number_format($win[$k]['views'] ?? 0) }}</td>
                            <td style="text-align:right;">{{ number_format($win[$k]['prints'] ?? 0) }}</td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:.5rem;">
                <button class="intel-btn intel-btn-secondary intel-btn-sm" onclick="exportTableToCsv('windows-table','analytics-windows.csv')"><i class="fas fa-file-csv"></i> CSV</button>
            </div>
        </div>
    </div>

    <!-- Per-User Activity -->
    <div class="intel-card" id="peruser-widget">
        <div class="intel-card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <h2 class="intel-card-title"><i class="fas fa-user-clock"></i> Activity by User (Top 50)</h2>
            <div style="display:flex; gap:.5rem;">
                <button class="intel-btn intel-btn-secondary intel-btn-sm" onclick="exportWidgetToPng('peruser-widget','analytics-peruser.png')"><i class="fas fa-image"></i> PNG</button>
            </div>
        </div>
        <div class="intel-card-body" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: var(--space-lg);">
            @php($sets = [
                ['key' => 'logins', 'title' => 'Logins by User', 'export' => route('admin.analytics.export', ['type' => 'logins_by_user'])],
                ['key' => 'downloads', 'title' => 'Downloads by User', 'export' => route('admin.analytics.export', ['type' => 'downloads_by_user'])],
                ['key' => 'views', 'title' => 'Views by User', 'export' => route('admin.analytics.export', ['type' => 'views_by_user'])],
            ])
            @foreach($sets as $set)
            <div>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:.5rem;">
                    <h3 style="margin:0; font-size:1rem;">{{ $set['title'] }}</h3>
                    <a class="intel-btn intel-btn-secondary intel-btn-sm" href="{{ $set['export'] }}"><i class="fas fa-file-excel"></i> Excel</a>
                </div>
                @php($items = $analytics['per_user'][$set['key']] ?? collect())
                @if($items && count($items) > 0)
                    <table class="intel-table" style="width:100%;">
                        <thead>
                            <tr><th>User</th><th style="text-align:right;">Total</th></tr>
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
            @endforeach
        </div>
    </div>

    <!-- Top Prophecies -->
    <div class="intel-card" id="topprop-widget">
        <div class="intel-card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <h2 class="intel-card-title"><i class="fas fa-trophy"></i> Top 5 Prophecies</h2>
            <div style="display:flex; gap:.5rem;">
                <button class="intel-btn intel-btn-secondary intel-btn-sm" onclick="exportWidgetToPng('topprop-widget','analytics-top-prophecies.png')"><i class="fas fa-image"></i> PNG</button>
            </div>
        </div>
        <div class="intel-card-body" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: var(--space-lg);">
            @php($tops = [
                ['key' => 'downloads', 'title' => 'By Downloads', 'export' => route('admin.analytics.export', ['type' => 'top_downloads'])],
                ['key' => 'views', 'title' => 'By Views', 'export' => route('admin.analytics.export', ['type' => 'top_views'])],
                ['key' => 'prints', 'title' => 'By Prints', 'export' => route('admin.analytics.export', ['type' => 'top_prints'])],
            ])
            @foreach($tops as $t)
            <div>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:.5rem;">
                    <h3 style="margin:0; font-size:1rem;">{{ $t['title'] }}</h3>
                    <a class="intel-btn intel-btn-secondary intel-btn-sm" href="{{ $t['export'] }}"><i class="fas fa-file-excel"></i> Excel</a>
                </div>
                @php($items = $analytics['top_prophecies'][$t['key']] ?? collect())
                @if($items && count($items) > 0)
                    <ol style="margin:0; padding-left:1rem; display:flex; flex-direction:column; gap:.5rem;">
                        @foreach($items as $it)
                        <li><strong>{{ $it['title'] }}</strong><span style="float:right;">{{ number_format($it['total']) }}</span></li>
                        @endforeach
                    </ol>
                @else
                    <p style="color: var(--intel-gray-500);">No data</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection


