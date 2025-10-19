@extends('layouts.admin')

@section('page-title', 'Create User')

@section('admin-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.users.index') }}" 
               class="text-gray-600 hover:text-gray-900 transition-colors">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Create New User</h1>
                <p class="text-gray-600 mt-1">Add a new user to the system</p>
            </div>
        </div>
    </div>
    
    <!-- Form -->
    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
            @csrf
            
            <!-- Personal Information -->
            <div class="intel-card rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Personal Information</h3>
                
                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" required
                           value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Enter full name">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email and Mobile -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" required
                               value="{{ old('email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                               placeholder="user@example.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="mobile" class="block text-sm font-medium text-gray-700 mb-2">
                            Mobile Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="mobile" name="mobile" required
                               value="{{ old('mobile') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                               placeholder="9876543210">
                        @error('mobile')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="password" name="password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter password">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Confirm password">
                    </div>
                </div>
            </div>
            
            <!-- Preferences -->
            <div class="intel-card rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">User Preferences</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Preferred Language -->
                    <div>
                        <label for="preferred_language" class="block text-sm font-medium text-gray-700 mb-2">
                            Preferred Language <span class="text-red-500">*</span>
                        </label>
                        <select id="preferred_language" name="preferred_language" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="en" {{ old('preferred_language', 'en') === 'en' ? 'selected' : '' }}>English</option>
                            <option value="ta" {{ old('preferred_language') === 'ta' ? 'selected' : '' }}>Tamil (தமிழ்)</option>
                            <option value="kn" {{ old('preferred_language') === 'kn' ? 'selected' : '' }}>Kannada (ಕನ್ನಡ)</option>
                            <option value="te" {{ old('preferred_language') === 'te' ? 'selected' : '' }}>Telugu (తెలుగు)</option>
                            <option value="ml" {{ old('preferred_language') === 'ml' ? 'selected' : '' }}>Malayalam (മലയാളം)</option>
                            <option value="hi" {{ old('preferred_language') === 'hi' ? 'selected' : '' }}>Hindi (हिंदी)</option>
                        </select>
                        @error('preferred_language')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Account Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Role Assignment -->
            <div class="intel-card rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Role Assignment</h3>
                
                <div class="space-y-3">
                    @foreach($roles as $role)
                    <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <input type="checkbox" id="role_{{ $role->id }}" name="roles[]" value="{{ $role->id }}"
                               {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="role_{{ $role->id }}" class="ml-3 flex-1 cursor-pointer">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $role->display_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $role->description }}</div>
                                </div>
                                <div class="text-xs text-gray-400">Level {{ $role->level }}</div>
                            </div>
                        </label>
                    </div>
                    @endforeach
                </div>
                
                @error('roles')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('roles.*')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Preview -->
            <div class="intel-card rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">User Preview</h3>
                <div id="user-preview" class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                    <div id="preview-avatar" class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-medium text-lg">
                        U
                    </div>
                    <div class="flex-1">
                        <div id="preview-name" class="text-lg font-semibold text-gray-900">User Name</div>
                        <div id="preview-email" class="text-sm text-gray-600">user@example.com</div>
                        <div id="preview-mobile" class="text-sm text-gray-500">Mobile number</div>
                    </div>
                    <div class="text-right">
                        <div id="preview-status" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </div>
                        <div id="preview-language" class="text-xs text-gray-500 mt-1">English</div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="intel-btn-primary px-6 py-2 rounded-lg font-medium">
                    <i class="fas fa-user-plus mr-2"></i>Create User
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const mobileInput = document.getElementById('mobile');
    const statusSelect = document.getElementById('status');
    const languageSelect = document.getElementById('preferred_language');
    
    const previewAvatar = document.getElementById('preview-avatar');
    const previewName = document.getElementById('preview-name');
    const previewEmail = document.getElementById('preview-email');
    const previewMobile = document.getElementById('preview-mobile');
    const previewStatus = document.getElementById('preview-status');
    const previewLanguage = document.getElementById('preview-language');
    
    const languageNames = {
        'en': 'English',
        'ta': 'Tamil',
        'kn': 'Kannada',
        'te': 'Telugu',
        'ml': 'Malayalam',
        'hi': 'Hindi'
    };
    
    function updatePreview() {
        // Update avatar
        const name = nameInput.value || 'User';
        previewAvatar.textContent = name.charAt(0).toUpperCase();
        
        // Update name
        previewName.textContent = name + ' Name';
        
        // Update email
        previewEmail.textContent = emailInput.value || 'user@example.com';
        
        // Update mobile
        previewMobile.textContent = mobileInput.value || 'Mobile number';
        
        // Update status
        const status = statusSelect.value;
        previewStatus.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        previewStatus.className = `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
            status === 'active' ? 'bg-green-100 text-green-800' :
            status === 'inactive' ? 'bg-yellow-100 text-yellow-800' :
            'bg-red-100 text-red-800'
        }`;
        
        // Update language
        previewLanguage.textContent = languageNames[languageSelect.value] || 'English';
    }
    
    nameInput.addEventListener('input', updatePreview);
    emailInput.addEventListener('input', updatePreview);
    mobileInput.addEventListener('input', updatePreview);
    statusSelect.addEventListener('change', updatePreview);
    languageSelect.addEventListener('change', updatePreview);
});
</script>
@endpush
@endsection
