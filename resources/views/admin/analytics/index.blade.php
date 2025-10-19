@extends('layouts.admin')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Analytics Dashboard</h1>
                    <p class="mt-1 text-sm text-gray-600">Comprehensive system analytics and insights</p>
                </div>
                <div class="flex space-x-3">
                    <div class="relative">
                        <select id="dateRange" class="intel-select">
                            <option value="7">Last 7 days</option>
                            <option value="30" selected>Last 30 days</option>
                            <option value="90">Last 90 days</option>
                            <option value="365">Last year</option>
                        </select>
                    </div>
                    <div class="relative">
                        <button id="exportBtn" class="intel-btn-secondary">
                            <i class="fas fa-download mr-2"></i>Export Data
                        </button>
                        <div id="exportMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                            <div class="py-1">
                                <a href="{{ route('admin.analytics.export', ['type' => 'summary', 'format' => 'csv']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-file-csv mr-2"></i>Summary (CSV)
                                </a>
                                <a href="{{ route('admin.analytics.export', ['type' => 'users', 'format' => 'csv']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-users mr-2"></i>Users (CSV)
                                </a>
                                <a href="{{ route('admin.analytics.export', ['type' => 'prophecies', 'format' => 'csv']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-book mr-2"></i>Prophecies (CSV)
                                </a>
                                <a href="{{ route('admin.analytics.export', ['type' => 'activities', 'format' => 'json']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-chart-line mr-2"></i>Activities (JSON)
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Users Card -->
            <div class="intel-card p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($userStats['total_users']) }}</p>
                        <p class="text-sm text-green-600">
                            <i class="fas fa-arrow-up mr-1"></i>{{ $userStats['new_users_this_month'] }} this month
                        </p>
                    </div>
                </div>
            </div>

            <!-- Prophecies Card -->
            <div class="intel-card p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Prophecies</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($prophecyStats['total_prophecies']) }}</p>
                        <p class="text-sm text-blue-600">
                            <i class="fas fa-check-circle mr-1"></i>{{ $prophecyStats['published_prophecies'] }} published
                        </p>
                    </div>
                </div>
            </div>

            <!-- Translations Card -->
            <div class="intel-card p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-language text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Translations</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($translationStats['total_translations']) }}</p>
                        <p class="text-sm text-purple-600">
                            <i class="fas fa-percentage mr-1"></i>{{ $translationStats['completion_rate'] }}% complete
                        </p>
                    </div>
                </div>
            </div>

            <!-- Activities Card -->
            <div class="intel-card p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Views</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($activityStats['total_views']) }}</p>
                        <p class="text-sm text-orange-600">
                            <i class="fas fa-download mr-1"></i>{{ number_format($activityStats['total_downloads']) }} downloads
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- User Roles Distribution -->
            <div class="intel-card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">User Roles Distribution</h3>
                <div class="space-y-4">
                    @foreach($userStats['users_by_role'] as $role)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">{{ ucfirst($role->role) }}</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $role->count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Prophecies by Category -->
            <div class="intel-card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Prophecies by Category</h3>
                <div class="space-y-4">
                    @foreach($prophecyStats['prophecies_by_category'] as $category)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">{{ $category->category }}</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $category->count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Most Viewed Prophecies -->
            <div class="intel-card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Most Viewed Prophecies</h3>
                <div class="space-y-3">
                    @forelse($performanceStats['most_viewed_prophecies'] as $prophecy)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $prophecy->title }}</p>
                        </div>
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $prophecy->views }} views
                        </span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-4">No view data available</p>
                    @endforelse
                </div>
            </div>

            <!-- Most Downloaded Prophecies -->
            <div class="intel-card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Most Downloaded Prophecies</h3>
                <div class="space-y-3">
                    @forelse($performanceStats['most_downloaded_prophecies'] as $prophecy)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $prophecy->title }}</p>
                        </div>
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $prophecy->downloads }} downloads
                        </span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-4">No download data available</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Language Usage -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Translation Languages -->
            <div class="intel-card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Translation Languages</h3>
                <div class="space-y-4">
                    @foreach($translationStats['translations_by_language'] as $translation)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">
                                @switch($translation->language)
                                    @case('en') English @break
                                    @case('ta') தமிழ் (Tamil) @break
                                    @case('kn') ಕನ್ನಡ (Kannada) @break
                                    @case('te') తెలుగు (Telugu) @break
                                    @case('ml') മലയാളം (Malayalam) @break
                                    @case('hi') हिंदी (Hindi) @break
                                    @default {{ $translation->language }}
                                @endswitch
                            </span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $translation->count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Popular Languages -->
            <div class="intel-card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Popular Languages (Usage)</h3>
                <div class="space-y-4">
                    @forelse($performanceStats['popular_languages'] as $language)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-orange-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">
                                @switch($language->language)
                                    @case('en') English @break
                                    @case('ta') தமிழ் (Tamil) @break
                                    @case('kn') ಕನ್ನಡ (Kannada) @break
                                    @case('te') తెలుగు (Telugu) @break
                                    @case('ml') മലയാളം (Malayalam) @break
                                    @case('hi') हिंदी (Hindi) @break
                                    @default {{ $language->language }}
                                @endswitch
                            </span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $language->usage }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-4">No usage data available</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="intel-card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($activityStats['recent_activities'] as $activity)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($activity->event_type === 'prophecy_view') bg-blue-100 text-blue-800
                                    @elseif($activity->event_type === 'prophecy_download') bg-green-100 text-green-800
                                    @elseif($activity->event_type === 'user_login') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ str_replace('_', ' ', $activity->event_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $activity->user?->name ?? 'Guest' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $activity->description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $activity->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                No recent activities found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Export menu toggle
    const exportBtn = document.getElementById('exportBtn');
    const exportMenu = document.getElementById('exportMenu');
    
    exportBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        exportMenu.classList.toggle('hidden');
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', function() {
        exportMenu.classList.add('hidden');
    });
    
    // Date range change handler
    const dateRange = document.getElementById('dateRange');
    dateRange.addEventListener('change', function() {
        // Reload page with new date range
        const url = new URL(window.location);
        url.searchParams.set('days', this.value);
        window.location.href = url.toString();
    });
});
</script>
@endpush
@endsection
