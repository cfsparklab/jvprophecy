@extends('layouts.admin')

@section('page-title', 'Prophecies Management')

@section('admin-content')
<!-- Page Header -->
<div class="intel-page-header">
    <div class="intel-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="intel-page-title">
                    <i class="fas fa-scroll"></i>
                    Prophecies Management
                </h1>
                <p class="intel-page-subtitle">Manage and organize all prophecies in the system</p>
            </div>
            <a href="{{ route('admin.prophecies.create') }}" class="intel-btn intel-btn-primary">
                <i class="fas fa-plus"></i>
                Create New Prophecy
            </a>
        </div>
    </div>
</div>

<div class="intel-container">
    <!-- Filters and Search -->
    <div class="intel-card mb-4">
        <div class="intel-card-body">
            <div class="intel-form-grid">
                <div class="intel-form-group">
                    <label class="intel-form-label">Search</label>
                    <input type="text" class="intel-form-input" placeholder="Search prophecies...">
                </div>
                <div class="intel-form-group">
                    <label class="intel-form-label">Status</label>
                    <select class="intel-form-select">
                        <option value="">All Statuses</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
                <div class="intel-form-group">
                    <label class="intel-form-label">Category</label>
                    <select class="intel-form-select">
                        <option value="">All Categories</option>
                        <option value="family">FAMILY</option>
                        <option value="general">General Prophecies</option>
                        <option value="end-times">End Times</option>
                        <option value="healing">Healing & Miracles</option>
                        <option value="church">Church & Ministry</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Prophecies Table -->
    <div class="intel-table-container">
        <div class="intel-table-header">
            <h2 class="intel-card-title">
                <i class="fas fa-list"></i>
                Prophecies List
            </h2>
            <p class="intel-card-subtitle">All prophecies with their details and actions</p>
        </div>
        
        <table class="intel-table">
            <thead>
                <tr>
                    <th>
                        <i class="fas fa-scroll mr-2"></i>
                        Prophecy
                    </th>
                    <th>
                        <i class="fas fa-calendar mr-2"></i>
                        Date
                    </th>
                    <th>
                        <i class="fas fa-tags mr-2"></i>
                        Category
                    </th>
                    <th>
                        <i class="fas fa-toggle-on mr-2"></i>
                        Status
                    </th>
                    <th>
                        <i class="fas fa-chart-bar mr-2"></i>
                        Stats
                    </th>
                    <th>
                        <i class="fas fa-cogs mr-2"></i>
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($prophecies ?? [] as $prophecy)
                <tr>
                    <td>
                        <div style="display: flex; align-items: start; gap: var(--space-md);">
                            @php
                                $categoryColors = [
                                    'FAMILY' => 'intel-stat-icon blue',
                                    'General Prophecies' => 'intel-stat-icon green',
                                    'End Times' => 'intel-stat-icon red',
                                    'Healing & Miracles' => 'intel-stat-icon green',
                                    'Church & Ministry' => 'intel-stat-icon yellow',
                                ];
                                $categoryName = $prophecy->category->name ?? 'General Prophecies';
                                $iconClass = $categoryColors[$categoryName] ?? 'intel-stat-icon blue';
                            @endphp
                            <div class="{{ $iconClass }}" style="width: 40px; height: 40px; font-size: 1rem; flex-shrink: 0;">
                                <i class="fas fa-scroll"></i>
                            </div>
                            <div style="min-width: 0; flex: 1;">
                                <h3 style="margin: 0; font-size: 1rem; font-weight: 600; color: var(--intel-gray-900);">{{ $prophecy->title ?? 'FAMILY / INDIVIDUAL – 1' }}</h3>
                                <p style="margin: var(--space-xs) 0; font-size: 0.875rem; color: var(--intel-gray-600); line-height: 1.4;">{{ Str::limit(strip_tags($prophecy->description ?? 'The Word of the Lord for the last days Christian families...'), 120) }}</p>
                                <div style="display: flex; align-items: center; gap: var(--space-md); margin-top: var(--space-sm);">
                                    <span style="font-size: 0.75rem; color: var(--intel-gray-500);">
                                        <i class="fas fa-user mr-1"></i>{{ $prophecy->creator->name ?? 'Super Administrator' }}
                                    </span>
                                    @if(($prophecy->status ?? 'published') === 'published')
                                    <span class="intel-badge intel-badge-success">
                                        <i class="fas fa-check-circle"></i>Live
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="text-align: center;">
                            @php
                                $date = $prophecy->jebikalam_vanga_date ?? $prophecy->created_at ?? now();
                            @endphp
                            <div style="background: var(--intel-blue-50); border: 1px solid var(--intel-blue-200); border-radius: var(--radius-md); padding: var(--space-sm); display: inline-block;">
                                <p style="margin: 0; font-size: 1.125rem; font-weight: 700; color: var(--intel-blue-900);">{{ $date->format('d') }}</p>
                                <p style="margin: 0; font-size: 0.75rem; color: var(--intel-blue-600); font-weight: 600;">{{ $date->format('M Y') }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                            $categoryClass = match(strtolower($categoryName)) {
                                'family' => 'family',
                                'general prophecies' => 'general',
                                'end times' => 'end-times',
                                'healing & miracles' => 'healing',
                                'church & ministry' => 'church',
                                default => 'general'
                            };
                        @endphp
                        <span class="intel-category-tag {{ $categoryClass }}">
                            <i class="fas fa-tag"></i>{{ $categoryName }}
                        </span>
                    </td>
                    <td>
                        @php
                            $status = $prophecy->status ?? 'published';
                        @endphp
                        @if($status === 'published')
                        <span class="intel-badge intel-badge-success">
                            <i class="fas fa-check-circle"></i>Published
                        </span>
                        @elseif($status === 'draft')
                        <span class="intel-badge intel-badge-warning">
                            <i class="fas fa-edit"></i>Draft
                        </span>
                        @else
                        <span class="intel-badge intel-badge-gray">
                            <i class="fas fa-archive"></i>Archived
                        </span>
                        @endif
                    </td>
                    <td>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: var(--space-sm); text-align: center;">
                            <div style="background: var(--intel-blue-50); border-radius: var(--radius-sm); padding: var(--space-sm);">
                                <div style="color: var(--intel-blue-600); margin-bottom: var(--space-xs);">
                                    <i class="fas fa-eye text-sm"></i>
                                </div>
                                <span style="font-size: 0.875rem; font-weight: 600; color: var(--intel-blue-900);">{{ $prophecy->view_count ?? 52 }}</span>
                            </div>
                            <div style="background: #dcfce7; border-radius: var(--radius-sm); padding: var(--space-sm);">
                                <div style="color: var(--success-color); margin-bottom: var(--space-xs);">
                                    <i class="fas fa-download text-sm"></i>
                                </div>
                                <span style="font-size: 0.875rem; font-weight: 600; color: #166534;">{{ $prophecy->download_count ?? 15 }}</span>
                            </div>
                            <div style="background: #fef3c7; border-radius: var(--radius-sm); padding: var(--space-sm);">
                                <div style="color: var(--warning-color); margin-bottom: var(--space-xs);">
                                    <i class="fas fa-print text-sm"></i>
                                </div>
                                <span style="font-size: 0.875rem; font-weight: 600; color: #92400e;">{{ $prophecy->print_count ?? 5 }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="intel-actions">
                            <a href="{{ route('admin.prophecies.show', $prophecy->id ?? 1) }}" 
                               class="intel-action-btn view" 
                               title="View Prophecy">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.prophecies.edit', $prophecy->id ?? 1) }}" 
                               class="intel-action-btn edit" 
                               title="Edit Prophecy">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.prophecies.translations', $prophecy->id ?? 1) }}" 
                               class="intel-action-btn view" 
                               title="Manage Translations">
                                <i class="fas fa-language"></i>
                            </a>
                            <button type="button" 
                                    class="intel-action-btn delete" 
                                    title="Delete Prophecy"
                                    onclick="confirmDelete('{{ $prophecy->id ?? 1 }}', '{{ $prophecy->title ?? 'Prophecy' }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: var(--space-2xl); color: var(--intel-gray-500);">
                        <i class="fas fa-scroll" style="font-size: 3rem; margin-bottom: var(--space-md); opacity: 0.3;"></i>
                        <p style="margin: 0; font-size: 1.125rem; font-weight: 600;">No prophecies found</p>
                        <p style="margin: var(--space-sm) 0 0 0; font-size: 0.875rem;">Create your first prophecy to get started</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if(isset($prophecies) && $prophecies->hasPages())
    <div style="margin-top: var(--space-lg); display: flex; justify-content: center;">
        {{ $prophecies->links() }}
    </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<script>
function confirmDelete(id, title) {
    if (confirm(`Are you sure you want to delete "${title}"? This action cannot be undone.`)) {
        // Create and submit delete form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/prophecies/${id}`;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection