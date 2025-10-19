@extends('layouts.admin')

@section('page-title', 'Edit User')

@section('admin-content')
<!-- Page Header -->
<div class="intel-page-header">
    <div class="intel-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="intel-page-title">
                    <i class="fas fa-user-edit"></i>
                    Edit User
                </h1>
                <p class="intel-page-subtitle">Update user information and settings</p>
            </div>
            <div style="display: flex; gap: var(--space-md);">
                <a href="{{ route('admin.users.show', $user->id ?? 1) }}" class="intel-btn intel-btn-secondary">
                    <i class="fas fa-eye"></i>
                    View Details
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
    <form method="POST" action="{{ route('admin.users.update', $user->id ?? 1) }}">
        @csrf
        @method('PUT')
        
        <!-- Personal Information -->
        <div class="intel-form-section">
            <div class="intel-form-section-header">
                <h2 class="intel-form-section-title">
                    <i class="fas fa-user"></i>
                    Personal Information
                </h2>
                <p class="intel-form-section-subtitle">Basic user details and contact information</p>
            </div>
            <div class="intel-form-section-body">
                <div class="intel-form-grid">
                    <!-- Name -->
                    <div class="intel-form-group">
                        <label for="name" class="intel-form-label">
                            Full Name <span style="color: var(--error-color);">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               required
                               value="{{ old('name', $user->name ?? 'John Doe') }}"
                               class="intel-form-input @error('name') error @enderror"
                               placeholder="Enter full name">
                        @error('name')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Email -->
                    <div class="intel-form-group">
                        <label for="email" class="intel-form-label">
                            Email Address <span style="color: var(--error-color);">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               required
                               value="{{ old('email', $user->email ?? 'john.doe@example.com') }}"
                               class="intel-form-input @error('email') error @enderror"
                               placeholder="Enter email address">
                        @error('email')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Mobile -->
                    <div class="intel-form-group">
                        <label for="mobile" class="intel-form-label">
                            Mobile Number <span style="color: var(--error-color);">*</span>
                        </label>
                        <input type="tel" 
                               id="mobile" 
                               name="mobile" 
                               required
                               value="{{ old('mobile', $user->mobile ?? '9876543210') }}"
                               class="intel-form-input @error('mobile') error @enderror"
                               placeholder="Enter mobile number">
                        @error('mobile')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Preferred Language -->
                    <div class="intel-form-group">
                        <label for="preferred_language" class="intel-form-label">
                            Preferred Language <span style="color: var(--error-color);">*</span>
                        </label>
                        <select id="preferred_language" 
                                name="preferred_language" 
                                required 
                                class="intel-form-select @error('preferred_language') error @enderror">
                            <option value="en" {{ old('preferred_language', $user->preferred_language ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                            <option value="ta" {{ old('preferred_language', $user->preferred_language) === 'ta' ? 'selected' : '' }}>தமிழ் (Tamil)</option>
                            <option value="kn" {{ old('preferred_language', $user->preferred_language) === 'kn' ? 'selected' : '' }}>ಕನ್ನಡ (Kannada)</option>
                            <option value="te" {{ old('preferred_language', $user->preferred_language) === 'te' ? 'selected' : '' }}>తెలుగు (Telugu)</option>
                            <option value="ml" {{ old('preferred_language', $user->preferred_language) === 'ml' ? 'selected' : '' }}>മലയാളം (Malayalam)</option>
                            <option value="hi" {{ old('preferred_language', $user->preferred_language) === 'hi' ? 'selected' : '' }}>हिंदी (Hindi)</option>
                        </select>
                        @error('preferred_language')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Account Settings -->
        <div class="intel-form-section">
            <div class="intel-form-section-header">
                <h2 class="intel-form-section-title">
                    <i class="fas fa-cog"></i>
                    Account Settings
                </h2>
                <p class="intel-form-section-subtitle">User account status and role assignments</p>
            </div>
            <div class="intel-form-section-body">
                <div class="intel-form-grid">
                    <!-- Status -->
                    <div class="intel-form-group">
                        <label for="status" class="intel-form-label">
                            Account Status <span style="color: var(--error-color);">*</span>
                        </label>
                        <select id="status" 
                                name="status" 
                                required 
                                class="intel-form-select @error('status') error @enderror">
                            <option value="active" {{ old('status', $user->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        @error('status')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Roles -->
                    <div class="intel-form-group">
                        <label for="roles" class="intel-form-label">
                            User Roles <span style="color: var(--error-color);">*</span>
                        </label>
                        <select id="roles" 
                                name="roles[]" 
                                multiple 
                                required 
                                class="intel-form-select @error('roles') error @enderror"
                                style="min-height: 120px;">
                            @if(isset($roles) && $roles->count() > 0)
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}" 
                                        {{ (collect(old('roles', $user->roles->pluck('id')->toArray() ?? [1]))->contains($role->id)) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                                @endforeach
                            @else
                                <!-- Sample roles for demo -->
                                <option value="1" {{ (collect(old('roles', [1]))->contains(1)) ? 'selected' : '' }}>User</option>
                                <option value="2" {{ (collect(old('roles', []))->contains(2)) ? 'selected' : '' }}>Moderator</option>
                                <option value="3" {{ (collect(old('roles', []))->contains(3)) ? 'selected' : '' }}>Administrator</option>
                            @endif
                        </select>
                        <p class="intel-form-help">Hold Ctrl/Cmd to select multiple roles</p>
                        @error('roles')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Security Settings -->
        <div class="intel-form-section">
            <div class="intel-form-section-header">
                <h2 class="intel-form-section-title">
                    <i class="fas fa-shield-alt"></i>
                    Security Settings
                </h2>
                <p class="intel-form-section-subtitle">Password and security configuration</p>
            </div>
            <div class="intel-form-section-body">
                <div class="intel-form-grid">
                    <!-- Password -->
                    <div class="intel-form-group">
                        <label for="password" class="intel-form-label">
                            New Password
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="intel-form-input @error('password') error @enderror"
                               placeholder="Enter new password (leave blank to keep current)">
                        <p class="intel-form-help">Leave blank to keep current password</p>
                        @error('password')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="intel-form-group">
                        <label for="password_confirmation" class="intel-form-label">
                            Confirm Password
                        </label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="intel-form-input"
                               placeholder="Confirm new password">
                        <p class="intel-form-help">Must match the new password</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div style="display: flex; justify-content: space-between; align-items: center; background: white; padding: var(--space-lg); border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid var(--intel-gray-200);">
            <div style="display: flex; gap: var(--space-md);">
                <button type="submit" class="intel-btn intel-btn-primary">
                    <i class="fas fa-save"></i>
                    Update User
                </button>
                
                <button type="button" class="intel-btn intel-btn-secondary" onclick="resetForm()">
                    <i class="fas fa-undo"></i>
                    Reset Changes
                </button>
            </div>
            
            <div style="display: flex; gap: var(--space-md);">
                <a href="{{ route('admin.users.show', $user->id ?? 1) }}" class="intel-btn intel-btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
                
                <button type="button" class="intel-btn intel-btn-danger" onclick="confirmDelete('{{ $user->id ?? 1 }}', '{{ $user->name ?? 'User' }}')">
                    <i class="fas fa-trash"></i>
                    Delete User
                </button>
            </div>
        </div>
    </form>
</div>

<!-- JavaScript for Form Actions -->
<script>
function resetForm() {
    if (confirm('Are you sure you want to reset all changes? This will restore the original values.')) {
        document.querySelector('form').reset();
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