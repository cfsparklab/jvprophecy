@extends('layouts.admin')

@section('page-title', 'Categories Management')

@section('admin-content')
<!-- Page Header -->
<div class="intel-page-header">
    <div class="intel-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="intel-page-title">
                    <i class="fas fa-tags"></i>
                    Categories Management
                </h1>
                <p class="intel-page-subtitle">Organize prophecies with hierarchical categories</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="intel-btn intel-btn-primary">
                <i class="fas fa-plus"></i>
                Create Category
            </a>
        </div>
    </div>
</div>

<div class="intel-container">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="alert alert-success" style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: var(--space-md); border-radius: var(--radius-md); margin-bottom: var(--space-lg); display: flex; align-items: center; gap: var(--space-sm);">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger" style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: var(--space-md); border-radius: var(--radius-md); margin-bottom: var(--space-lg); display: flex; align-items: center; gap: var(--space-sm);">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif
    
    <!-- Statistics Overview -->
    @php
        $totalCategories = $categories->count();
        $activeCategories = $categories->where('status', 'active')->count();
        $rootCategories = $categories->whereNull('parent_id')->count();
        $mostUsed = $categories->sortByDesc('prophecies_count')->first();
    @endphp
    <div class="intel-stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: var(--space-lg);">
        <!-- Total Categories -->
        <div class="intel-stat-card">
            <div class="intel-stat-header">
                <div class="intel-stat-content">
                    <h3>Total Categories</h3>
                    <p class="value">{{ $totalCategories }}</p>
                </div>
                <div class="intel-stat-icon blue">
                    <i class="fas fa-tags"></i>
                </div>
            </div>
            <div class="intel-stat-footer">
                <i class="fas fa-check-circle text-green-600"></i>
                <span>All categories</span>
            </div>
        </div>
        
        <!-- Active Categories -->
        <div class="intel-stat-card">
            <div class="intel-stat-header">
                <div class="intel-stat-content">
                    <h3>Active Categories</h3>
                    <p class="value">{{ $activeCategories }}</p>
                </div>
                <div class="intel-stat-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="intel-stat-footer">
                <i class="fas fa-eye text-blue-600"></i>
                <span>Visible to users</span>
            </div>
        </div>
        
        <!-- Root Categories -->
        <div class="intel-stat-card">
            <div class="intel-stat-header">
                <div class="intel-stat-content">
                    <h3>Root Categories</h3>
                    <p class="value">{{ $rootCategories }}</p>
                </div>
                <div class="intel-stat-icon yellow">
                    <i class="fas fa-sitemap"></i>
                </div>
            </div>
            <div class="intel-stat-footer">
                <i class="fas fa-layer-group text-purple-600"></i>
                <span>Top level</span>
            </div>
        </div>
        
        <!-- Most Used -->
        <div class="intel-stat-card">
            <div class="intel-stat-header">
                <div class="intel-stat-content">
                    <h3>Most Used</h3>
                    <p class="value" style="font-size: 1.5rem;">{{ $mostUsed ? strtoupper($mostUsed->name) : 'N/A' }}</p>
                </div>
                <div class="intel-stat-icon blue">
                    <i class="fas fa-star"></i>
                </div>
            </div>
            <div class="intel-stat-footer">
                <i class="fas fa-scroll text-blue-600"></i>
                <span>{{ $mostUsed ? $mostUsed->prophecies_count : 0 }} prophecies</span>
            </div>
        </div>
    </div>
    
    <!-- Categories Table -->
    <div class="intel-table-container">
        <div class="intel-table-header">
            <h2 class="intel-card-title">
                <i class="fas fa-list"></i>
                Categories List
            </h2>
            <p class="intel-card-subtitle">All categories with their hierarchy and prophecy counts</p>
        </div>
        
        <table class="intel-table">
            <thead>
                <tr>
                    <th>
                        <i class="fas fa-tag mr-2"></i>
                        Category
                    </th>
                    <th>
                        <i class="fas fa-sitemap mr-2"></i>
                        Parent
                    </th>
                    <th>
                        <i class="fas fa-scroll mr-2"></i>
                        Prophecies
                    </th>
                    <th>
                        <i class="fas fa-toggle-on mr-2"></i>
                        Status
                    </th>
                    <th>
                        <i class="fas fa-cogs mr-2"></i>
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $iconColors = ['blue', 'green', 'red', 'yellow', 'purple', 'orange'];
                    $iconMap = [
                        'family' => 'fa-home',
                        'general' => 'fa-star',
                        'end times' => 'fa-hourglass-end',
                        'healing' => 'fa-heart',
                        'personal' => 'fa-user',
                        'church' => 'fa-church',
                        'ministry' => 'fa-hands-praying',
                        'prophecy' => 'fa-scroll',
                        'spiritual' => 'fa-dove',
                        'prayer' => 'fa-praying-hands',
                        'faith' => 'fa-cross',
                        'guidance' => 'fa-compass',
                    ];
                    
                    function getIconForCategory($name, $iconMap) {
                        $nameLower = strtolower($name);
                        foreach ($iconMap as $key => $icon) {
                            if (strpos($nameLower, $key) !== false) {
                                return $icon;
                            }
                        }
                        return 'fa-folder';
                    }
                    
                    function getColorForCount($count) {
                        if ($count == 0) {
                            return ['bg' => 'var(--intel-gray-100)', 'border' => 'var(--intel-gray-300)', 'text' => 'var(--intel-gray-700)', 'subtext' => 'var(--intel-gray-500)'];
                        } elseif ($count <= 2) {
                            return ['bg' => 'var(--intel-blue-50)', 'border' => 'var(--intel-blue-200)', 'text' => 'var(--intel-blue-900)', 'subtext' => 'var(--intel-blue-600)'];
                        } elseif ($count <= 4) {
                            return ['bg' => '#fef3c7', 'border' => '#fbbf24', 'text' => '#92400e', 'subtext' => 'var(--warning-color)'];
                        } else {
                            return ['bg' => '#dcfce7', 'border' => '#86efac', 'text' => '#166534', 'subtext' => 'var(--success-color)'];
                        }
                    }
                @endphp
                
                @forelse($categories as $index => $category)
                @php
                    $iconColor = $iconColors[$index % count($iconColors)];
                    $icon = $category->icon ?: getIconForCategory($category->name, $iconMap);
                    $colors = getColorForCount($category->prophecies_count);
                @endphp
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: var(--space-md);">
                            <div class="intel-stat-icon {{ $iconColor }}" style="width: 40px; height: 40px; font-size: 1rem; flex-shrink: 0;">
                                <i class="{{ $icon }}"></i>
                            </div>
                            <div style="min-width: 0; flex: 1;">
                                <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: var(--intel-gray-900);">{{ $category->name }}</h3>
                                <p style="margin: var(--space-xs) 0 0 0; font-size: 0.875rem; color: var(--intel-gray-600);">{{ $category->description ?: 'No description provided' }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($category->parent)
                            <span class="intel-badge intel-badge-blue">
                                <i class="fas fa-level-up-alt"></i>{{ $category->parent->name }}
                            </span>
                        @else
                            <span class="intel-badge intel-badge-gray">
                                <i class="fas fa-layer-group"></i>Root Category
                            </span>
                        @endif
                    </td>
                    <td>
                        <div style="text-align: center;">
                            <div style="background: {{ $colors['bg'] }}; border: 1px solid {{ $colors['border'] }}; border-radius: var(--radius-md); padding: var(--space-sm); display: inline-block; min-width: 60px;">
                                <p style="margin: 0; font-size: 1.25rem; font-weight: 700; color: {{ $colors['text'] }};">{{ $category->prophecies_count }}</p>
                                <p style="margin: 0; font-size: 0.75rem; color: {{ $colors['subtext'] }}; font-weight: 600;">prophecies</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($category->status === 'active')
                            <span class="intel-badge intel-badge-success">
                                <i class="fas fa-check-circle"></i>Active
                            </span>
                        @else
                            <span class="intel-badge intel-badge-gray">
                                <i class="fas fa-times-circle"></i>Inactive
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="intel-actions">
                            <a href="{{ route('admin.categories.show', $category->id) }}" class="intel-action-btn view" title="View Category">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="intel-action-btn edit" title="Edit Category">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="intel-action-btn delete" title="Delete Category" onclick="deleteCategory({{ $category->id }}, '{{ addslashes($category->name) }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: var(--space-xl);">
                        <div style="color: var(--intel-gray-500);">
                            <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: var(--space-md); opacity: 0.5;"></i>
                            <p style="font-size: 1.125rem; margin: 0;">No categories found</p>
                            <p style="font-size: 0.875rem; margin-top: var(--space-xs);">Create your first category to get started</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<!-- JavaScript for Category Actions -->
<script>
function deleteCategory(categoryId, categoryName) {
    if (confirm(`Are you sure you want to delete "${categoryName}"? This action cannot be undone.`)) {
        // Create and submit delete form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/categories/${categoryId}`;
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        // Add DELETE method
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection