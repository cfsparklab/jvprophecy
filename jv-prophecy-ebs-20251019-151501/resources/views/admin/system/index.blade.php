@extends('layouts.admin')

@section('title', 'System Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">System Management</h1>
                    <p class="mt-1 text-sm text-gray-600">Monitor and manage system performance</p>
                </div>
                <div class="flex space-x-3">
                    <button id="refreshBtn" class="intel-btn-secondary">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </button>
                    <button id="optimizeBtn" class="intel-btn-primary">
                        <i class="fas fa-rocket mr-2"></i>Optimize System
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- System Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Database Status -->
            <div class="intel-card p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 {{ $systemStatus['database_connection']['status'] === 'connected' ? 'bg-green-500' : 'bg-red-500' }} rounded-lg flex items-center justify-center">
                            <i class="fas fa-database text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Database</p>
                        <p class="text-lg font-bold {{ $systemStatus['database_connection']['status'] === 'connected' ? 'text-green-600' : 'text-red-600' }}">
                            {{ ucfirst($systemStatus['database_connection']['status']) }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $systemStatus['database_connection']['message'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Cache Status -->
            <div class="intel-card p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 {{ $systemStatus['cache_status']['status'] === 'working' ? 'bg-green-500' : 'bg-red-500' }} rounded-lg flex items-center justify-center">
                            <i class="fas fa-memory text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Cache</p>
                        <p class="text-lg font-bold {{ $systemStatus['cache_status']['status'] === 'working' ? 'text-green-600' : 'text-red-600' }}">
                            {{ ucfirst($systemStatus['cache_status']['status']) }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $systemStatus['cache_status']['message'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Storage Status -->
            <div class="intel-card p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 {{ $systemStatus['storage_writable']['status'] === 'writable' ? 'bg-green-500' : 'bg-red-500' }} rounded-lg flex items-center justify-center">
                            <i class="fas fa-hdd text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Storage</p>
                        <p class="text-lg font-bold {{ $systemStatus['storage_writable']['status'] === 'writable' ? 'text-green-600' : 'text-red-600' }}">
                            {{ ucfirst($systemStatus['storage_writable']['status']) }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $systemStatus['storage_writable']['message'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Queue Status -->
            <div class="intel-card p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tasks text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Queue</p>
                        <p class="text-lg font-bold text-blue-600">
                            {{ ucfirst($systemStatus['queue_status']['status']) }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $systemStatus['queue_status']['message'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- System Info -->
            <div class="intel-card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">System Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">PHP Version</span>
                        <span class="text-sm text-gray-900">{{ $systemInfo['php_version'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Laravel Version</span>
                        <span class="text-sm text-gray-900">{{ $systemInfo['laravel_version'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Database Version</span>
                        <span class="text-sm text-gray-900">{{ $systemInfo['database_version'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Environment</span>
                        <span class="text-sm {{ $systemInfo['environment'] === 'production' ? 'text-green-600' : 'text-orange-600' }}">
                            {{ ucfirst($systemInfo['environment']) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Debug Mode</span>
                        <span class="text-sm {{ $systemInfo['debug_mode'] ? 'text-red-600' : 'text-green-600' }}">
                            {{ $systemInfo['debug_mode'] ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Timezone</span>
                        <span class="text-sm text-gray-900">{{ $systemInfo['timezone'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="intel-card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance Metrics</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Memory Usage</span>
                        <span class="text-sm text-gray-900">{{ $performanceMetrics['memory_usage'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Memory Peak</span>
                        <span class="text-sm text-gray-900">{{ $performanceMetrics['memory_peak'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Memory Limit</span>
                        <span class="text-sm text-gray-900">{{ $performanceMetrics['memory_limit'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Execution Time</span>
                        <span class="text-sm text-gray-900">{{ $performanceMetrics['execution_time'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Storage Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Disk Usage -->
            <div class="intel-card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Disk Usage</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Total Space</span>
                        <span class="text-sm text-gray-900">{{ $storageInfo['total_space'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Used Space</span>
                        <span class="text-sm text-gray-900">{{ $storageInfo['used_space'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Free Space</span>
                        <span class="text-sm text-gray-900">{{ $storageInfo['free_space'] }}</span>
                    </div>
                    <div class="mt-4">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">Usage</span>
                            <span class="text-gray-900">{{ $storageInfo['storage_usage_percent'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $storageInfo['storage_usage_percent'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Backup Information -->
            <div class="intel-card p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Backup Information</h3>
                    <button id="createBackupBtn" class="intel-btn-secondary text-sm">
                        <i class="fas fa-plus mr-1"></i>Create Backup
                    </button>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Total Backups</span>
                        <span class="text-sm text-gray-900">{{ $backupInfo['count'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Total Size</span>
                        <span class="text-sm text-gray-900">{{ $backupInfo['total_size'] }}</span>
                    </div>
                    @if(count($backupInfo['backups']) > 0)
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-600 mb-2">Recent Backups</p>
                        <div class="space-y-2">
                            @foreach(array_slice($backupInfo['backups'], 0, 3) as $backup)
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-600 truncate">{{ $backup['name'] }}</span>
                                <span class="text-gray-500">{{ $backup['size'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- System Actions -->
        <div class="intel-card p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">System Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Clear Cache -->
                <button id="clearCacheBtn" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-3">
                        <i class="fas fa-broom text-blue-600 text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Clear Cache</span>
                    <span class="text-xs text-gray-500 text-center">Clear application cache</span>
                </button>

                <!-- Optimize System -->
                <button id="optimizeSystemBtn" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-3">
                        <i class="fas fa-rocket text-green-600 text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Optimize</span>
                    <span class="text-xs text-gray-500 text-center">Optimize performance</span>
                </button>

                <!-- Create Backup -->
                <button id="backupSystemBtn" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-3">
                        <i class="fas fa-archive text-purple-600 text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Backup</span>
                    <span class="text-xs text-gray-500 text-center">Create system backup</span>
                </button>

                <!-- View Logs -->
                <a href="{{ route('admin.system.logs') }}" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-3">
                        <i class="fas fa-file-alt text-orange-600 text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">View Logs</span>
                    <span class="text-xs text-gray-500 text-center">System logs</span>
                </a>
            </div>
        </div>

        <!-- Recent System Logs -->
        @if(count($recentLogs) > 0)
        <div class="intel-card p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Recent System Logs</h3>
                <a href="{{ route('admin.system.logs') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
            </div>
            <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto">
                <pre class="text-green-400 text-xs font-mono">{{ implode("\n", array_slice($recentLogs, -10)) }}</pre>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Loading Modal -->
<div id="loadingModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                <i class="fas fa-spinner fa-spin text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Processing...</h3>
            <p id="loadingMessage" class="text-sm text-gray-500 mt-2">Please wait while we process your request.</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadingModal = document.getElementById('loadingModal');
    const loadingMessage = document.getElementById('loadingMessage');

    function showLoading(message = 'Processing...') {
        loadingMessage.textContent = message;
        loadingModal.classList.remove('hidden');
    }

    function hideLoading() {
        loadingModal.classList.add('hidden');
    }

    function showNotification(message, type = 'success') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' : 
            type === 'error' ? 'bg-red-500 text-white' : 
            'bg-blue-500 text-white'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Remove after 5 seconds
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    // Refresh button
    document.getElementById('refreshBtn').addEventListener('click', function() {
        location.reload();
    });

    // Clear Cache
    document.getElementById('clearCacheBtn').addEventListener('click', function() {
        showLoading('Clearing cache...');
        
        fetch('{{ route("admin.system.clear-cache") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                cache_types: ['application', 'config', 'route', 'view']
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showNotification(data.message, 'success');
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            hideLoading();
            showNotification('Failed to clear cache', 'error');
        });
    });

    // Optimize System
    document.getElementById('optimizeSystemBtn').addEventListener('click', function() {
        showLoading('Optimizing system...');
        
        fetch('{{ route("admin.system.optimize") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                optimizations: ['config', 'route', 'view']
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showNotification(data.message, 'success');
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            hideLoading();
            showNotification('Failed to optimize system', 'error');
        });
    });

    // Create Backup
    document.getElementById('backupSystemBtn').addEventListener('click', function() {
        showLoading('Creating backup...');
        
        fetch('{{ route("admin.system.backup") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                type: 'full'
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showNotification(data.message, 'success');
                // Refresh page after 2 seconds to show updated backup info
                setTimeout(() => location.reload(), 2000);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            hideLoading();
            showNotification('Failed to create backup', 'error');
        });
    });

    // Optimize button in header
    document.getElementById('optimizeBtn').addEventListener('click', function() {
        document.getElementById('optimizeSystemBtn').click();
    });
});
</script>
@endpush
@endsection
