@extends('layouts.admin')

@section('page-title', 'Users Management')

@section('admin-content')
<!-- Page Header -->
<div class="intel-page-header">
    <div class="intel-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="intel-page-title">
                    <i class="fas fa-users"></i>
                    Users Management
                </h1>
                <p class="intel-page-subtitle">Manage system users and their roles</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="intel-btn intel-btn-primary">
                <i class="fas fa-user-plus"></i>
                Create User
            </a>
        </div>
    </div>
</div>

<div class="intel-container">
    <!-- Statistics Overview -->
    <div class="intel-stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: var(--space-lg);">
        <!-- Total Users -->
        <div class="intel-stat-card">
            <div class="intel-stat-header">
                <div class="intel-stat-content">
                    <h3>Total Users</h3>
                    <p class="value">{{ $totalUsers ?? 13 }}</p>
                </div>
                <div class="intel-stat-icon blue">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="intel-stat-footer">
                <i class="fas fa-user-check text-green-600"></i>
                <span>{{ $activeUsers ?? 11 }} active</span>
            </div>
        </div>
        
        <!-- Administrators -->
        <div class="intel-stat-card">
            <div class="intel-stat-header">
                <div class="intel-stat-content">
                    <h3>Administrators</h3>
                    <p class="value">{{ $adminUsers ?? 2 }}</p>
                </div>
                <div class="intel-stat-icon red">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
            <div class="intel-stat-footer">
                <i class="fas fa-crown text-yellow-600"></i>
                <span>Super Admin access</span>
            </div>
        </div>
        
        <!-- Regular Users -->
        <div class="intel-stat-card">
            <div class="intel-stat-header">
                <div class="intel-stat-content">
                    <h3>Regular Users</h3>
                    <p class="value">{{ $regularUsers ?? 11 }}</p>
                </div>
                <div class="intel-stat-icon green">
                    <i class="fas fa-user"></i>
                </div>
            </div>
            <div class="intel-stat-footer">
                <i class="fas fa-eye text-blue-600"></i>
                <span>View access</span>
            </div>
        </div>
        
        <!-- Recent Registrations -->
        <div class="intel-stat-card">
            <div class="intel-stat-header">
                <div class="intel-stat-content">
                    <h3>This Month</h3>
                    <p class="value">{{ $recentUsers ?? 3 }}</p>
                </div>
                <div class="intel-stat-icon yellow">
                    <i class="fas fa-user-plus"></i>
                </div>
            </div>
            <div class="intel-stat-footer">
                <i class="fas fa-calendar text-purple-600"></i>
                <span>New registrations</span>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="intel-card mb-4">
        <div class="intel-card-body">
            <form method="GET" action="{{ route('admin.users.index') }}">
                <div class="intel-form-grid">
                    <div class="intel-form-group">
                        <label class="intel-form-label">Search</label>
                        <input type="text" name="search" class="intel-form-input" placeholder="Search users..." value="{{ request('search') }}">
                    </div>
                    <div class="intel-form-group">
                        <label class="intel-form-label">Status</label>
                        <select name="status" class="intel-form-select">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>
                    <div class="intel-form-group">
                        <label class="intel-form-label">Role</label>
                        <select name="role" class="intel-form-select">
                            <option value="">All Roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="intel-form-group">
                        <label class="intel-form-label">Per Page</label>
                        <select name="per_page" class="intel-form-select" onchange="this.form.submit()">
                            <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                            <option value="30" {{ $perPage == 30 ? 'selected' : '' }}>30</option>
                            <option value="40" {{ $perPage == 40 ? 'selected' : '' }}>40</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                </div>
                <div style="margin-top: var(--space-md);">
                    <button type="submit" class="intel-btn intel-btn-primary">
                        <i class="fas fa-search"></i>
                        Search
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="intel-btn intel-btn-secondary">
                        <i class="fas fa-times"></i>
                        Clear
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Users Table -->
    <div class="intel-table-container">
        <div class="intel-table-header">
            <h2 class="intel-card-title">
                <i class="fas fa-list"></i>
                Users List
            </h2>
            <p class="intel-card-subtitle">All system users with their details and roles</p>
        </div>
        
        <table class="intel-table">
            <thead>
                <tr>
                    <th>
                        <i class="fas fa-user mr-2"></i>
                        User
                    </th>
                    <th>
                        <i class="fas fa-envelope mr-2"></i>
                        Contact
                    </th>
                    <th>
                        <i class="fas fa-shield-alt mr-2"></i>
                        Roles
                    </th>
                    <th>
                        <i class="fas fa-toggle-on mr-2"></i>
                        Status
                    </th>
                    <th>
                        <i class="fas fa-calendar mr-2"></i>
                        Joined
                    </th>
                    <th>
                        <i class="fas fa-cogs mr-2"></i>
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: var(--space-md);">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--intel-blue-500), var(--intel-blue-600)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 1.125rem; flex-shrink: 0;">
                                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}{{ strtoupper(substr(explode(' ', $user->name ?? 'User')[1] ?? substr($user->name ?? 'User', 1, 1), 0, 1)) }}
                            </div>
                            <div style="min-width: 0; flex: 1;">
                                <h3 style="margin: 0; font-size: 1rem; font-weight: 600; color: var(--intel-gray-900);">{{ $user->name ?? 'Unknown User' }}</h3>
                                <p style="margin: var(--space-xs) 0 0 0; font-size: 0.875rem; color: var(--intel-gray-600);">
                                    @switch($user->preferred_language)
                                        @case('en') English @break
                                        @case('ta') தமிழ் @break
                                        @case('hi') हिंदी @break
                                        @case('kn') ಕನ್ನಡ @break
                                        @case('te') తెలుగు @break
                                        @case('ml') മലയാളം @break
                                        @default English
                                    @endswitch
                                </p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <p style="margin: 0; font-size: 0.875rem; font-weight: 500; color: var(--intel-gray-900);">{{ $user->email }}</p>
                            <p style="margin: var(--space-xs) 0 0 0; font-size: 0.875rem; color: var(--intel-gray-600);">{{ $user->mobile ?? 'N/A' }}</p>
                        </div>
                    </td>
                    <td>
                        @if($user->roles->isNotEmpty())
                            @foreach($user->roles as $role)
                                <span class="intel-badge intel-badge-info">
                                    <i class="fas fa-{{ $role->name === 'admin' ? 'user-shield' : 'user' }}"></i>
                                    {{ ucfirst($role->name) }}
                                </span>
                                @if(!$loop->last)<br>@endif
                            @endforeach
                        @else
                            <span class="intel-badge intel-badge-secondary">
                                <i class="fas fa-user"></i>No Role
                            </span>
                        @endif
                    </td>
                    <td>
                        @switch($user->status)
                            @case('active')
                                <span class="intel-badge intel-badge-success">
                                    <i class="fas fa-check-circle"></i>Active
                                </span>
                                @break
                            @case('inactive')
                                <span class="intel-badge intel-badge-warning">
                                    <i class="fas fa-pause-circle"></i>Inactive
                                </span>
                                @break
                            @case('suspended')
                                <span class="intel-badge intel-badge-danger">
                                    <i class="fas fa-ban"></i>Suspended
                                </span>
                                @break
                            @default
                                <span class="intel-badge intel-badge-secondary">
                                    <i class="fas fa-question-circle"></i>Unknown
                                </span>
                        @endswitch
                    </td>
                    <td>
                        <div style="text-align: center;">
                            <div style="background: var(--intel-blue-50); border: 1px solid var(--intel-blue-200); border-radius: var(--radius-md); padding: var(--space-sm); display: inline-block;">
                                <p style="margin: 0; font-size: 1rem; font-weight: 700; color: var(--intel-blue-900);">{{ $user->created_at->format('d') }}</p>
                                <p style="margin: 0; font-size: 0.75rem; color: var(--intel-blue-600); font-weight: 600;">{{ $user->created_at->format('M Y') }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="intel-actions">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="intel-action-btn view" title="View User">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="intel-action-btn edit" title="Edit User">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="intel-action-btn delete" title="Delete User" onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: var(--space-xl); color: var(--intel-gray-600);">
                        <i class="fas fa-users" style="font-size: 3rem; margin-bottom: var(--space-md); opacity: 0.3;"></i>
                        <p style="margin: 0; font-size: 1.125rem; font-weight: 500;">No users found</p>
                        <p style="margin: var(--space-xs) 0 0 0; font-size: 0.875rem;">Try adjusting your search criteria or create a new user.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($users->hasPages())
    <div style="margin-top: var(--space-lg);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-md);">
            <div style="color: var(--intel-gray-600); font-size: 0.875rem;">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
            </div>
            <div style="color: var(--intel-gray-600); font-size: 0.875rem;">
                Displaying {{ $perPage }} users per page
            </div>
        </div>
        
        <div style="display: flex; justify-content: center;">
            <div class="intel-pagination">
                {{-- Previous Page Link --}}
                @if ($users->onFirstPage())
                    <span class="intel-pagination-item disabled">
                        <i class="fas fa-chevron-left"></i>
                        Previous
                    </span>
                @else
                    <a href="{{ $users->previousPageUrl() }}" class="intel-pagination-item">
                        <i class="fas fa-chevron-left"></i>
                        Previous
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                    @if ($page == $users->currentPage())
                        <span class="intel-pagination-item active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="intel-pagination-item">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="intel-pagination-item">
                        Next
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <span class="intel-pagination-item disabled">
                        Next
                        <i class="fas fa-chevron-right"></i>
                    </span>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
<!-- JavaScript for User Actions -->
<script>
function deleteUser(userId, userName) {
    if (confirm(`Are you sure you want to delete "${userName}"? This action cannot be undone.`)) {
        // Create and submit delete form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/users/${userId}`;
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