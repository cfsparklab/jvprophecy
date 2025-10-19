@extends('layouts.admin')

@section('page-title', 'User Details')

@section('admin-content')
<!-- Page Header -->
<div class="intel-page-header">
    <div class="intel-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="intel-page-title">
                    <i class="fas fa-user"></i>
                    {{ $user->name ?? 'User Details' }}
                </h1>
                <p class="intel-page-subtitle">User profile and activity information</p>
            </div>
            <div style="display: flex; gap: var(--space-md);">
                <a href="{{ route('admin.users.edit', $user->id ?? 1) }}" class="intel-btn intel-btn-primary">
                    <i class="fas fa-edit"></i>
                    Edit User
                </a>
                <a href="{{ route('admin.users.index') }}" class="intel-btn intel-btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Users
                </a>
            </div>
        </div>
    </div>
</div>

<div class="intel-container">
    <!-- User Overview -->
    <div style="display: grid; grid-template-columns: 1fr 300px; gap: var(--space-xl); margin-bottom: var(--space-xl);">
        <!-- Main Content -->
        <div>
            <!-- User Information -->
            <div class="intel-card">
                <div class="intel-card-header">
                    <h2 class="intel-card-title">
                        <i class="fas fa-info-circle"></i>
                        User Information
                    </h2>
                    <p class="intel-card-subtitle">Personal details and account information</p>
                </div>
                <div class="intel-card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-lg);">
                        <!-- Personal Details -->
                        <div>
                            <h3 style="margin: 0 0 var(--space-md) 0; font-size: 1rem; font-weight: 600; color: var(--intel-gray-900);">Personal Details</h3>
                            
                            <div style="display: flex; flex-direction: column; gap: var(--space-md);">
                                <!-- Name -->
                                <div>
                                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--intel-gray-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--space-xs);">Full Name</label>
                                    <p style="margin: 0; font-size: 1rem; font-weight: 500; color: var(--intel-gray-900);">{{ $user->name ?? 'John Doe' }}</p>
                                </div>
                                
                                <!-- Email -->
                                <div>
                                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--intel-gray-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--space-xs);">Email Address</label>
                                    <p style="margin: 0; font-size: 1rem; font-weight: 500; color: var(--intel-gray-900);">{{ $user->email ?? 'john.doe@example.com' }}</p>
                                </div>
                                
                                <!-- Mobile -->
                                <div>
                                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--intel-gray-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--space-xs);">Mobile Number</label>
                                    <p style="margin: 0; font-size: 1rem; font-weight: 500; color: var(--intel-gray-900);">{{ $user->mobile ?? '9876543210' }}</p>
                                </div>
                                
                                <!-- Language -->
                                <div>
                                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--intel-gray-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--space-xs);">Preferred Language</label>
                                    <p style="margin: 0; font-size: 1rem; font-weight: 500; color: var(--intel-gray-900);">{{ ucfirst($user->preferred_language ?? 'English') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Account Details -->
                        <div>
                            <h3 style="margin: 0 0 var(--space-md) 0; font-size: 1rem; font-weight: 600; color: var(--intel-gray-900);">Account Details</h3>
                            
                            <div style="display: flex; flex-direction: column; gap: var(--space-md);">
                                <!-- Status -->
                                <div>
                                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--intel-gray-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--space-xs);">Account Status</label>
                                    @php
                                        $status = $user->status ?? 'active';
                                    @endphp
                                    @if($status === 'active')
                                    <span class="intel-badge intel-badge-success">
                                        <i class="fas fa-check-circle"></i>Active
                                    </span>
                                    @elseif($status === 'inactive')
                                    <span class="intel-badge intel-badge-warning">
                                        <i class="fas fa-pause-circle"></i>Inactive
                                    </span>
                                    @else
                                    <span class="intel-badge intel-badge-error">
                                        <i class="fas fa-ban"></i>Suspended
                                    </span>
                                    @endif
                                </div>
                                
                                <!-- Roles -->
                                <div>
                                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--intel-gray-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--space-xs);">User Roles</label>
                                    <div style="display: flex; flex-wrap: wrap; gap: var(--space-sm);">
                                        @if(isset($user->roles) && $user->roles->count() > 0)
                                            @foreach($user->roles as $role)
                                            <span class="intel-badge intel-badge-info">
                                                <i class="fas fa-shield-alt"></i>{{ $role->name }}
                                            </span>
                                            @endforeach
                                        @else
                                        <span class="intel-badge intel-badge-info">
                                            <i class="fas fa-user"></i>User
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Joined Date -->
                                <div>
                                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--intel-gray-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--space-xs);">Member Since</label>
                                    <p style="margin: 0; font-size: 1rem; font-weight: 500; color: var(--intel-gray-900);">{{ $user->created_at ? $user->created_at->format('d/m/Y') : '02/09/2025' }}</p>
                                </div>
                                
                                <!-- Last Login -->
                                <div>
                                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--intel-gray-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--space-xs);">Last Login</label>
                                    <p style="margin: 0; font-size: 1rem; font-weight: 500; color: var(--intel-gray-900);">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Never' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- User Activity -->
            <div class="intel-card" style="margin-top: var(--space-lg);">
                <div class="intel-card-header">
                    <h2 class="intel-card-title">
                        <i class="fas fa-chart-line"></i>
                        User Activity
                    </h2>
                    <p class="intel-card-subtitle">Recent user activities and contributions</p>
                </div>
                <div class="intel-card-body">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-lg);">
                        <!-- Prophecies Created -->
                        <div style="text-align: center; padding: var(--space-md);">
                            <div style="width: 60px; height: 60px; background: var(--intel-blue-100); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-sm) auto;">
                                <i class="fas fa-scroll text-blue-600"></i>
                            </div>
                            <h4 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--intel-gray-900);">{{ $user->prophecies_count ?? 0 }}</h4>
                            <p style="margin: var(--space-xs) 0 0 0; font-size: 0.875rem; color: var(--intel-gray-600);">Prophecies Created</p>
                        </div>
                        
                        <!-- Login Sessions -->
                        <div style="text-align: center; padding: var(--space-md);">
                            <div style="width: 60px; height: 60px; background: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-sm) auto;">
                                <i class="fas fa-sign-in-alt text-green-600"></i>
                            </div>
                            <h4 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--intel-gray-900);">{{ $user->login_count ?? 25 }}</h4>
                            <p style="margin: var(--space-xs) 0 0 0; font-size: 0.875rem; color: var(--intel-gray-600);">Login Sessions</p>
                        </div>
                        
                        <!-- Profile Updates -->
                        <div style="text-align: center; padding: var(--space-md);">
                            <div style="width: 60px; height: 60px; background: #fef3c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-sm) auto;">
                                <i class="fas fa-edit text-yellow-600"></i>
                            </div>
                            <h4 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--intel-gray-900);">{{ $user->profile_updates ?? 3 }}</h4>
                            <p style="margin: var(--space-xs) 0 0 0; font-size: 0.875rem; color: var(--intel-gray-600);">Profile Updates</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div>
            <!-- User Avatar -->
            <div class="intel-card">
                <div class="intel-card-body" style="text-align: center;">
                    <div style="width: 120px; height: 120px; background: linear-gradient(135deg, var(--intel-blue-500), var(--intel-blue-600)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg) auto; color: white; font-size: 3rem; font-weight: 700;">
                        {{ substr($user->name ?? 'J', 0, 1) }}
                    </div>
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: 600; color: var(--intel-gray-900);">{{ $user->name ?? 'John Doe' }}</h3>
                    <p style="margin: var(--space-xs) 0 0 0; font-size: 0.875rem; color: var(--intel-gray-600);">{{ $user->email ?? 'john.doe@example.com' }}</p>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="intel-card" style="margin-top: var(--space-lg);">
                <div class="intel-card-header">
                    <h2 class="intel-card-title">
                        <i class="fas fa-bolt"></i>
                        Quick Actions
                    </h2>
                </div>
                <div class="intel-card-body">
                    <div style="display: flex; flex-direction: column; gap: var(--space-sm);">
                        <a href="{{ route('admin.users.edit', $user->id ?? 1) }}" class="intel-btn intel-btn-primary intel-btn-sm">
                            <i class="fas fa-edit"></i>
                            Edit User
                        </a>
                        
                        @if(($user->status ?? 'active') === 'active')
                        <button type="button" class="intel-btn intel-btn-warning intel-btn-sm" onclick="toggleUserStatus('{{ $user->id ?? 1 }}', 'inactive')">
                            <i class="fas fa-pause"></i>
                            Deactivate User
                        </button>
                        @else
                        <button type="button" class="intel-btn intel-btn-success intel-btn-sm" onclick="toggleUserStatus('{{ $user->id ?? 1 }}', 'active')">
                            <i class="fas fa-play"></i>
                            Activate User
                        </button>
                        @endif
                        
                        <button type="button" class="intel-btn intel-btn-secondary intel-btn-sm">
                            <i class="fas fa-key"></i>
                            Reset Password
                        </button>
                        
                        <hr style="margin: var(--space-md) 0; border: none; border-top: 1px solid var(--intel-gray-200);">
                        
                        <button type="button" class="intel-btn intel-btn-danger intel-btn-sm" onclick="confirmDelete('{{ $user->id ?? 1 }}', '{{ $user->name ?? 'User' }}')">
                            <i class="fas fa-trash"></i>
                            Delete User
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Actions -->
<script>
function toggleUserStatus(userId, newStatus) {
    const action = newStatus === 'active' ? 'activate' : 'deactivate';
    if (confirm(`Are you sure you want to ${action} this user?`)) {
        // Create and submit form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/users/${userId}/toggle-status`;
        form.innerHTML = `
            @csrf
            <input type="hidden" name="status" value="${newStatus}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function confirmDelete(userId, userName) {
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