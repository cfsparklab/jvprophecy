@extends('layouts.admin')

@section('page-title', $prophecy->title ?? 'Prophecy Details')

@section('admin-content')
<!-- Page Header -->
<div class="intel-page-header">
    <div class="intel-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="intel-page-title">
                    <i class="fas fa-scroll"></i>
                    {{ $prophecy->title ?? 'Season of Breakthrough' }}
                </h1>
                <p class="intel-page-subtitle">Prophecy details and management options</p>
            </div>
            <div style="display: flex; gap: var(--space-md);">
                <a href="{{ route('admin.prophecies.edit', $prophecy->id ?? 1) }}" class="intel-btn intel-btn-primary">
                    <i class="fas fa-edit"></i>
                    Edit Prophecy
                </a>
                <a href="{{ route('prophecies.show', $prophecy->id ?? 1) }}" class="intel-btn intel-btn-success" target="_blank" rel="noopener noreferrer">
                    <i class="fas fa-external-link-alt"></i>
                    View Public Page
                </a>
                <a href="{{ route('admin.prophecies.translations', $prophecy->id ?? 1) }}" class="intel-btn intel-btn-info">
                    <i class="fas fa-language"></i>
                    Manage Translations
                </a>
                <a href="{{ route('admin.prophecies.index') }}" class="intel-btn intel-btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>
</div>

<div class="intel-container">
    <!-- Main Content Grid -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--space-xl); margin-bottom: var(--space-xl);">
        <!-- Prophecy Content -->
        <div class="intel-card">
            <div class="intel-card-header">
                <h2 class="intel-card-title">
                    <i class="fas fa-file-text"></i>
                    Prophecy Content
                </h2>
                <p class="intel-card-subtitle">Full prophecy text and details</p>
            </div>
            <div class="intel-card-body">
                <!-- Prophecy Text -->
                <div style="background: var(--intel-gray-50); border-radius: var(--radius-lg); padding: var(--space-xl); border-left: 4px solid var(--intel-blue-500);">
                    <div style="font-size: 1.125rem; line-height: 1.8; color: var(--intel-gray-900);">
                        {!! $prophecy->description ?? '<p><strong>The Word of the Lord for the last days Christian families:</strong></p>
                        <p>The Spirit of the Lord says, "I am releasing a season of breakthrough over My people. What has been delayed will now come to pass. What has been hindered will now flow freely."</p>
                        <p style="color: #cc0000;"><strong>Key Points for This Season:</strong></p>
                        <ul>
                            <li>Financial breakthroughs and provision</li>
                            <li>Healing and restoration in families</li>
                            <li>Divine connections and relationships</li>
                            <li>Spiritual awakening and revival</li>
                        </ul>
                        <p><em>"Prepare your hearts, for I am doing a new thing in this season!"</em></p>' !!}
                    </div>
                </div>
                
                <!-- Tags -->
                @if(($prophecy->tags ?? ['breakthrough', 'season', 'family', 'provision', 'healing']) && count($prophecy->tags ?? ['breakthrough', 'season', 'family', 'provision', 'healing']) > 0)
                <div style="margin-top: var(--space-xl); padding-top: var(--space-lg); border-top: 1px solid var(--intel-gray-200);">
                    <h3 style="margin: 0 0 var(--space-md) 0; font-size: 1rem; font-weight: 600; color: var(--intel-gray-900); display: flex; align-items: center; gap: var(--space-sm);">
                        <i class="fas fa-tags text-indigo-600"></i>
                        Tags
                    </h3>
                    <div style="display: flex; flex-wrap: wrap; gap: var(--space-sm);">
                        @foreach($prophecy->tags ?? ['breakthrough', 'season', 'family', 'provision', 'healing'] as $tag)
                        <span class="intel-badge intel-badge-info">{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Prophecy Information -->
        <div style="display: flex; flex-direction: column; gap: var(--space-lg);">
            <!-- Basic Information -->
            <div class="intel-card">
                <div class="intel-card-header">
                    <h2 class="intel-card-title">
                        <i class="fas fa-info-circle"></i>
                        Basic Information
                    </h2>
                </div>
                <div class="intel-card-body">
                    <div style="display: flex; flex-direction: column; gap: var(--space-lg);">
                        <!-- Title -->
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--intel-gray-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--space-xs);">Title</label>
                            <p style="margin: 0; font-size: 1.125rem; font-weight: 600; color: var(--intel-gray-900);">{{ $prophecy->title ?? 'Season of Breakthrough' }}</p>
                        </div>
                        
                        <!-- Date -->
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--intel-gray-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--space-xs);">Jebikalam Vaanga Date</label>
                            <div style="background: var(--intel-blue-50); border: 1px solid var(--intel-blue-200); border-radius: var(--radius-md); padding: var(--space-md); text-align: center; display: inline-block;">
                                @php
                                    $date = $prophecy->jebikalam_vanga_date ?? now()->subDays(2);
                                @endphp
                                <p style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--intel-blue-900);">{{ $date->format('d') }}</p>
                                <p style="margin: 0; font-size: 0.875rem; color: var(--intel-blue-600); font-weight: 600;">{{ $date->format('M Y') }}</p>
                            </div>
                        </div>
                        
                        <!-- Category -->
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--intel-gray-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--space-xs);">Category</label>
                            @php
                                $categoryName = $prophecy->category->name ?? 'FAMILY';
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
                        </div>
                        
                        <!-- Status -->
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--intel-gray-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--space-xs);">Status</label>
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
                        </div>
                        
                        <!-- Visibility -->
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--intel-gray-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--space-xs);">Visibility</label>
                            <span class="intel-badge intel-badge-info">
                                <i class="fas fa-globe"></i>{{ ucfirst($prophecy->visibility ?? 'public') }}
                            </span>
                        </div>
                        
                        <!-- Created By -->
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--intel-gray-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--space-xs);">Created By</label>
                            <div style="display: flex; align-items: center; gap: var(--space-sm);">
                                <div style="width: 32px; height: 32px; background: linear-gradient(135deg, var(--intel-blue-500), var(--intel-blue-600)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.875rem;">
                                    {{ substr($prophecy->creator->name ?? 'S', 0, 1) }}
                                </div>
                                <span style="font-size: 0.875rem; font-weight: 600; color: var(--intel-gray-900);">{{ $prophecy->creator->name ?? 'Super Administrator' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Statistics -->
            <div class="intel-card">
                <div class="intel-card-header">
                    <h2 class="intel-card-title">
                        <i class="fas fa-chart-bar"></i>
                        Statistics
                    </h2>
                </div>
                <div class="intel-card-body">
                    <div style="display: grid; grid-template-columns: 1fr; gap: var(--space-md);">
                        <!-- Views -->
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-md); background: var(--intel-blue-50); border-radius: var(--radius-md);">
                            <div style="display: flex; align-items: center; gap: var(--space-sm);">
                                <i class="fas fa-eye text-blue-600"></i>
                                <span style="font-size: 0.875rem; font-weight: 500; color: var(--intel-gray-700);">Views</span>
                            </div>
                            <span style="font-size: 1.25rem; font-weight: 700; color: var(--intel-blue-900);">{{ $prophecy->view_count ?? 52 }}</span>
                        </div>
                        
                        <!-- Downloads -->
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-md); background: #dcfce7; border-radius: var(--radius-md);">
                            <div style="display: flex; align-items: center; gap: var(--space-sm);">
                                <i class="fas fa-download text-green-600"></i>
                                <span style="font-size: 0.875rem; font-weight: 500; color: var(--intel-gray-700);">Downloads</span>
                            </div>
                            <span style="font-size: 1.25rem; font-weight: 700; color: #166534;">{{ $prophecy->download_count ?? 15 }}</span>
                        </div>
                        
                        <!-- Prints -->
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-md); background: #fef3c7; border-radius: var(--radius-md);">
                            <div style="display: flex; align-items: center; gap: var(--space-sm);">
                                <i class="fas fa-print text-yellow-600"></i>
                                <span style="font-size: 0.875rem; font-weight: 500; color: var(--intel-gray-700);">Prints</span>
                            </div>
                            <span style="font-size: 1.25rem; font-weight: 700; color: #92400e;">{{ $prophecy->print_count ?? 5 }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="intel-card">
                <div class="intel-card-header">
                    <h2 class="intel-card-title">
                        <i class="fas fa-cogs"></i>
                        Actions
                    </h2>
                </div>
                <div class="intel-card-body">
                    <div style="display: flex; flex-direction: column; gap: var(--space-sm);">
                        <a href="{{ route('admin.prophecies.edit', $prophecy->id ?? 1) }}" class="intel-btn intel-btn-primary intel-btn-sm">
                            <i class="fas fa-edit"></i>
                            Edit Prophecy
                        </a>
                        
                        <a href="{{ route('admin.prophecies.translations', $prophecy->id ?? 1) }}" class="intel-btn intel-btn-secondary intel-btn-sm">
                            <i class="fas fa-language"></i>
                            Manage Translations
                        </a>
                        
                        <button type="button" class="intel-btn intel-btn-secondary intel-btn-sm">
                            <i class="fas fa-download"></i>
                            Export PDF
                        </button>
                        
                        <button type="button" class="intel-btn intel-btn-secondary intel-btn-sm">
                            <i class="fas fa-print"></i>
                            Print
                        </button>
                        
                        <hr style="margin: var(--space-md) 0; border: none; border-top: 1px solid var(--intel-gray-200);">
                        
                        @if(($prophecy->status ?? 'published') === 'published')
                        <button type="button" class="intel-btn intel-btn-warning intel-btn-sm">
                            <i class="fas fa-eye-slash"></i>
                            Unpublish
                        </button>
                        @else
                        <button type="button" class="intel-btn intel-btn-success intel-btn-sm">
                            <i class="fas fa-check-circle"></i>
                            Publish
                        </button>
                        @endif
                        
                        <button type="button" class="intel-btn intel-btn-danger intel-btn-sm" onclick="confirmDelete('{{ $prophecy->id ?? 1 }}', '{{ $prophecy->title ?? 'Prophecy' }}')">
                            <i class="fas fa-trash"></i>
                            Delete Prophecy
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Script -->
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