@extends('layouts.admin')

@section('page-title', 'System Settings')

@section('admin-content')
<!-- Page Header -->
<div class="intel-page-header">
    <div class="intel-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="intel-page-title">
                    <i class="fas fa-cogs"></i>
                    System Settings
                </h1>
                <p class="intel-page-subtitle">Configure system-wide settings and preferences</p>
            </div>
            <div style="display: flex; gap: var(--space-md);">
                <form method="POST" action="{{ route('admin.settings.backup') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="intel-btn intel-btn-success">
                        <i class="fas fa-download"></i>
                        Create Backup
                    </button>
                </form>
                
                <form method="POST" action="{{ route('admin.settings.clear-cache') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="intel-btn intel-btn-warning">
                        <i class="fas fa-broom"></i>
                        Clear Cache
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="intel-container">
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')
        
        <!-- Application Settings -->
        <div class="intel-form-section">
            <div class="intel-form-section-header">
                <h2 class="intel-form-section-title">
                    <i class="fas fa-desktop"></i>
                    Application Settings
                </h2>
                <p class="intel-form-section-subtitle">Core application configuration and branding</p>
            </div>
            <div class="intel-form-section-body">
                <div class="intel-form-grid">
                    <!-- Application Name -->
                    <div class="intel-form-group">
                        <label for="app_name" class="intel-form-label">
                            Application Name <span style="color: var(--error-color);">*</span>
                        </label>
                        <input type="text" 
                               id="app_name" 
                               name="app_name" 
                               required
                               value="{{ old('app_name', $settings['app_name'] ?? 'Jebikalam Vaanga Prophecy') }}"
                               class="intel-form-input @error('app_name') error @enderror"
                               placeholder="Enter application name">
                        @error('app_name')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Application Version -->
                    <div class="intel-form-group">
                        <label for="app_version" class="intel-form-label">
                            Application Version <span style="color: var(--error-color);">*</span>
                        </label>
                        <input type="text" 
                               id="app_version" 
                               name="app_version" 
                               required
                               value="{{ old('app_version', $settings['app_version'] ?? '3.2.0.0') }}"
                               class="intel-form-input @error('app_version') error @enderror"
                               placeholder="e.g., 3.2.0.0">
                        @error('app_version')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Build Number -->
                    <div class="intel-form-group">
                        <label for="app_build" class="intel-form-label">
                            Build Number <span style="color: var(--error-color);">*</span>
                        </label>
                        <input type="text" 
                               id="app_build" 
                               name="app_build" 
                               required
                               value="{{ old('app_build', $settings['app_build'] ?? 'Build 00010') }}"
                               class="intel-form-input @error('app_build') error @enderror"
                               placeholder="e.g., Build 00010">
                        @error('app_build')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Application Description -->
                    <div class="intel-form-group" style="grid-column: 1 / -1;">
                        <label for="app_description" class="intel-form-label">
                            Application Description
                        </label>
                        <textarea id="app_description" 
                                  name="app_description" 
                                  rows="3"
                                  class="intel-form-textarea @error('app_description') error @enderror"
                                  placeholder="Enter application description">{{ old('app_description', $settings['app_description'] ?? 'Professional prophecy management system with multi-language support') }}</textarea>
                        @error('app_description')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Localization Settings -->
        <div class="intel-form-section">
            <div class="intel-form-section-header">
                <h2 class="intel-form-section-title">
                    <i class="fas fa-globe"></i>
                    Localization Settings
                </h2>
                <p class="intel-form-section-subtitle">Language, timezone, and date/time formatting</p>
            </div>
            <div class="intel-form-section-body">
                <div class="intel-form-grid">
                    <!-- Default Language -->
                    <div class="intel-form-group">
                        <label for="default_language" class="intel-form-label">
                            Default Language <span style="color: var(--error-color);">*</span>
                        </label>
                        <select id="default_language" 
                                name="default_language" 
                                required 
                                class="intel-form-select @error('default_language') error @enderror">
                            <option value="en" {{ old('default_language', $settings['default_language'] ?? 'en') === 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                            <option value="ta" {{ old('default_language', $settings['default_language']) === 'ta' ? 'selected' : '' }}>🇮🇳 தமிழ் (Tamil)</option>
                            <option value="kn" {{ old('default_language', $settings['default_language']) === 'kn' ? 'selected' : '' }}>🇮🇳 ಕನ್ನಡ (Kannada)</option>
                            <option value="te" {{ old('default_language', $settings['default_language']) === 'te' ? 'selected' : '' }}>🇮🇳 తెలుగు (Telugu)</option>
                            <option value="ml" {{ old('default_language', $settings['default_language']) === 'ml' ? 'selected' : '' }}>🇮🇳 മലയാളം (Malayalam)</option>
                            <option value="hi" {{ old('default_language', $settings['default_language']) === 'hi' ? 'selected' : '' }}>🇮🇳 हिंदी (Hindi)</option>
                        </select>
                        @error('default_language')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Timezone -->
                    <div class="intel-form-group">
                        <label for="timezone" class="intel-form-label">
                            Timezone <span style="color: var(--error-color);">*</span>
                        </label>
                        <select id="timezone" 
                                name="timezone" 
                                required 
                                class="intel-form-select @error('timezone') error @enderror">
                            <option value="Asia/Kolkata" {{ old('timezone', $settings['timezone'] ?? 'Asia/Kolkata') === 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata (IST)</option>
                            <option value="UTC" {{ old('timezone', $settings['timezone']) === 'UTC' ? 'selected' : '' }}>UTC</option>
                            <option value="America/New_York" {{ old('timezone', $settings['timezone']) === 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                            <option value="Europe/London" {{ old('timezone', $settings['timezone']) === 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                        </select>
                        @error('timezone')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Date Format -->
                    <div class="intel-form-group">
                        <label for="date_format" class="intel-form-label">
                            Date Format <span style="color: var(--error-color);">*</span>
                        </label>
                        <select id="date_format" 
                                name="date_format" 
                                required 
                                class="intel-form-select @error('date_format') error @enderror">
                            <option value="d/m/Y" {{ old('date_format', $settings['date_format'] ?? 'd/m/Y') === 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY (Indian Standard)</option>
                            <option value="m/d/Y" {{ old('date_format', $settings['date_format']) === 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY (US Format)</option>
                            <option value="Y-m-d" {{ old('date_format', $settings['date_format']) === 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD (ISO Format)</option>
                            <option value="d-m-Y" {{ old('date_format', $settings['date_format']) === 'd-m-Y' ? 'selected' : '' }}>DD-MM-YYYY</option>
                        </select>
                        @error('date_format')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Time Format -->
                    <div class="intel-form-group">
                        <label for="time_format" class="intel-form-label">
                            Time Format <span style="color: var(--error-color);">*</span>
                        </label>
                        <select id="time_format" 
                                name="time_format" 
                                required 
                                class="intel-form-select @error('time_format') error @enderror">
                            <option value="H:i:s" {{ old('time_format', $settings['time_format'] ?? 'H:i:s') === 'H:i:s' ? 'selected' : '' }}>24 Hour (HH:MM:SS)</option>
                            <option value="h:i:s A" {{ old('time_format', $settings['time_format']) === 'h:i:s A' ? 'selected' : '' }}>12 Hour (HH:MM:SS AM/PM)</option>
                        </select>
                        @error('time_format')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- System Performance -->
        <div class="intel-form-section">
            <div class="intel-form-section-header">
                <h2 class="intel-form-section-title">
                    <i class="fas fa-tachometer-alt"></i>
                    System Performance
                </h2>
                <p class="intel-form-section-subtitle">Performance optimization and file management settings</p>
            </div>
            <div class="intel-form-section-body">
                <div class="intel-form-grid">
                    <!-- Items Per Page -->
                    <div class="intel-form-group">
                        <label for="items_per_page" class="intel-form-label">
                            Items Per Page <span style="color: var(--error-color);">*</span>
                        </label>
                        <input type="number" 
                               id="items_per_page" 
                               name="items_per_page" 
                               min="5" 
                               max="100" 
                               required
                               value="{{ old('items_per_page', $settings['items_per_page'] ?? 25) }}"
                               class="intel-form-input @error('items_per_page') error @enderror"
                               placeholder="25">
                        <p class="intel-form-help">Number of items to display per page in lists</p>
                        @error('items_per_page')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Max File Size -->
                    <div class="intel-form-group">
                        <label for="max_file_size" class="intel-form-label">
                            Max File Size (KB) <span style="color: var(--error-color);">*</span>
                        </label>
                        <input type="number" 
                               id="max_file_size" 
                               name="max_file_size" 
                               min="1" 
                               max="10240" 
                               required
                               value="{{ old('max_file_size', $settings['max_file_size'] ?? 2048) }}"
                               class="intel-form-input @error('max_file_size') error @enderror"
                               placeholder="2048">
                        <p class="intel-form-help">Maximum file upload size in kilobytes</p>
                        @error('max_file_size')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Allowed File Types -->
                    <div class="intel-form-group" style="grid-column: 1 / -1;">
                        <label for="allowed_file_types" class="intel-form-label">
                            Allowed File Types <span style="color: var(--error-color);">*</span>
                        </label>
                        <input type="text" 
                               id="allowed_file_types" 
                               name="allowed_file_types" 
                               required
                               value="{{ old('allowed_file_types', $settings['allowed_file_types'] ?? 'jpg,jpeg,png,gif,pdf,doc,docx') }}"
                               class="intel-form-input @error('allowed_file_types') error @enderror"
                               placeholder="jpg,jpeg,png,gif,pdf,doc,docx">
                        <p class="intel-form-help">Comma-separated list of allowed file extensions</p>
                        @error('allowed_file_types')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Security & Features -->
        <div class="intel-form-section">
            <div class="intel-form-section-header">
                <h2 class="intel-form-section-title">
                    <i class="fas fa-shield-alt"></i>
                    Security & Features
                </h2>
                <p class="intel-form-section-subtitle">Security settings and feature toggles</p>
            </div>
            <div class="intel-form-section-body">
                <!-- Feature Toggles -->
                <div style="display: grid; grid-template-columns: 1fr; gap: var(--space-lg);">
                    <!-- Registration -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-lg); background: var(--intel-blue-50); border: 1px solid var(--intel-blue-200); border-radius: var(--radius-lg);">
                        <div>
                            <h4 style="margin: 0; font-size: 1rem; font-weight: 600; color: var(--intel-blue-900);">
                                <i class="fas fa-user-plus mr-2"></i>
                                Enable User Registration
                            </h4>
                            <p style="margin: var(--space-xs) 0 0 0; font-size: 0.875rem; color: var(--intel-blue-700);">Allow new users to register accounts</p>
                        </div>
                        <label class="intel-toggle">
                            <input type="checkbox" 
                                   name="enable_registration" 
                                   value="1"
                                   {{ old('enable_registration', $settings['enable_registration'] ?? false) ? 'checked' : '' }}>
                            <span class="intel-toggle-slider"></span>
                        </label>
                    </div>
                    
                    <!-- Email Verification -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-lg); background: #dcfce7; border: 1px solid #86efac; border-radius: var(--radius-lg);">
                        <div>
                            <h4 style="margin: 0; font-size: 1rem; font-weight: 600; color: #166534;">
                                <i class="fas fa-envelope-check mr-2"></i>
                                Email Verification Required
                            </h4>
                            <p style="margin: var(--space-xs) 0 0 0; font-size: 0.875rem; color: var(--success-color);">Require email verification for new accounts</p>
                        </div>
                        <label class="intel-toggle">
                            <input type="checkbox" 
                                   name="enable_email_verification" 
                                   value="1"
                                   {{ old('enable_email_verification', $settings['enable_email_verification'] ?? true) ? 'checked' : '' }}>
                            <span class="intel-toggle-slider"></span>
                        </label>
                    </div>
                    
                    <!-- Security Logging -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-lg); background: #fef3c7; border: 1px solid #fbbf24; border-radius: var(--radius-lg);">
                        <div>
                            <h4 style="margin: 0; font-size: 1rem; font-weight: 600; color: #92400e;">
                                <i class="fas fa-shield-alt mr-2"></i>
                                Security Event Logging
                            </h4>
                            <p style="margin: var(--space-xs) 0 0 0; font-size: 0.875rem; color: #d97706;">Log security events and user activities</p>
                        </div>
                        <label class="intel-toggle">
                            <input type="checkbox" 
                                   name="enable_security_logging" 
                                   value="1"
                                   {{ old('enable_security_logging', $settings['enable_security_logging'] ?? true) ? 'checked' : '' }}>
                            <span class="intel-toggle-slider"></span>
                        </label>
                    </div>
                    
                    <!-- Maintenance Mode -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-lg); background: #fee2e2; border: 1px solid #fca5a5; border-radius: var(--radius-lg);">
                        <div>
                            <h4 style="margin: 0; font-size: 1rem; font-weight: 600; color: #991b1b;">
                                <i class="fas fa-tools mr-2"></i>
                                Maintenance Mode
                            </h4>
                            <p style="margin: var(--space-xs) 0 0 0; font-size: 0.875rem; color: #dc2626;">Put the application in maintenance mode</p>
                        </div>
                        <label class="intel-toggle">
                            <input type="checkbox" 
                                   name="enable_maintenance_mode" 
                                   value="1"
                                   {{ old('enable_maintenance_mode', $settings['enable_maintenance_mode'] ?? false) ? 'checked' : '' }}>
                            <span class="intel-toggle-slider"></span>
                        </label>
                    </div>
                </div>
                
                <!-- Maintenance Message -->
                <div class="intel-form-group" style="margin-top: var(--space-lg);">
                    <label for="maintenance_message" class="intel-form-label">
                        Maintenance Message
                    </label>
                    <textarea id="maintenance_message" 
                              name="maintenance_message" 
                              rows="3"
                              class="intel-form-textarea @error('maintenance_message') error @enderror"
                              placeholder="Enter maintenance message to display to users">{{ old('maintenance_message', $settings['maintenance_message'] ?? 'System is currently under maintenance. Please check back later.') }}</textarea>
                    <p class="intel-form-help">Message displayed when maintenance mode is enabled</p>
                    @error('maintenance_message')
                    <p class="intel-form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div style="display: flex; justify-content: space-between; align-items: center; background: white; padding: var(--space-lg); border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid var(--intel-gray-200);">
            <div style="display: flex; gap: var(--space-md);">
                <button type="submit" class="intel-btn intel-btn-primary">
                    <i class="fas fa-save"></i>
                    Save Settings
                </button>
                
                <button type="button" class="intel-btn intel-btn-secondary" onclick="resetForm()">
                    <i class="fas fa-undo"></i>
                    Reset Changes
                </button>
            </div>
            
            <div style="display: flex; gap: var(--space-md);">
                <form method="POST" action="{{ route('admin.settings.backup') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="intel-btn intel-btn-success">
                        <i class="fas fa-download"></i>
                        Backup Settings
                    </button>
                </form>
                
                <form method="POST" action="{{ route('admin.settings.clear-cache') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="intel-btn intel-btn-warning" onclick="return confirm('Are you sure you want to clear all caches?')">
                        <i class="fas fa-broom"></i>
                        Clear All Caches
                    </button>
                </form>
            </div>
        </div>
    </form>
</div>

<!-- Add Toggle Styles -->
<style>
.intel-toggle {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}

.intel-toggle input {
    opacity: 0;
    width: 0;
    height: 0;
}

.intel-toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: var(--intel-gray-300);
    transition: .4s;
    border-radius: 34px;
}

.intel-toggle-slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

.intel-toggle input:checked + .intel-toggle-slider {
    background-color: var(--intel-blue-500);
}

.intel-toggle input:focus + .intel-toggle-slider {
    box-shadow: 0 0 1px var(--intel-blue-500);
}

.intel-toggle input:checked + .intel-toggle-slider:before {
    transform: translateX(26px);
}
</style>

<!-- JavaScript for Form Actions -->
<script>
function resetForm() {
    if (confirm('Are you sure you want to reset all changes? This will restore the original values.')) {
        document.querySelector('form').reset();
    }
}

// Auto-save functionality (optional)
let saveTimeout;
function autoSave() {
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(() => {
        // Show auto-save indicator
        console.log('Auto-saving settings...');
    }, 2000);
}

// Add auto-save listeners to form inputs
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('change', autoSave);
    });
});
</script>
@endsection