@extends('layouts.app')

@section('content')
<div style="display: flex; height: 100vh;">
    <!-- Sidebar -->
    <div class="intel-sidebar">
        <!-- Logo -->
        <div style="display: flex; align-items: center; justify-content: center; padding: var(--space-xl); border-bottom: 1px solid var(--intel-gray-700);">
            <div style="display: flex; align-items: center; gap: var(--space-md);">
                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--intel-blue-500), var(--intel-blue-600)); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-scroll" style="color: white; font-size: 1.125rem;"></i>
                </div>
                <h1 style="font-size: 1.25rem; font-weight: 700; color: white; margin: 0;">Jebikalam Vaanga Prophecy</h1>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav style="margin-top: var(--space-lg);">
            <!-- Main Section -->
            <div style="padding: 0 var(--space-lg); margin-bottom: var(--space-md);">
                <p style="font-size: 0.75rem; font-weight: 600; color: var(--intel-gray-400); text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Main</p>
            </div>
            
            <a href="{{ route('admin.dashboard') }}" 
               class="intel-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            
            <a href="{{ route('admin.prophecies.index') }}" 
               class="intel-nav-link {{ request()->routeIs('admin.prophecies.*') ? 'active' : '' }}">
                <i class="fas fa-scroll"></i>
                Prophecies
            </a>
            
                    <a href="{{ route('admin.categories.index') }}" 
                       class="intel-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i>
                        Categories
                    </a>
                    
                    <a href="{{ route('admin.security-logs.index') }}" 
                       class="intel-nav-link {{ request()->routeIs('admin.security-logs.*') ? 'active' : '' }}">
                        <i class="fas fa-shield-alt"></i>
                        Security Logs
                    </a>
            
            <!-- Management Section -->
            <div style="padding: 0 var(--space-lg); margin-bottom: var(--space-md); margin-top: var(--space-xl);">
                <p style="font-size: 0.75rem; font-weight: 600; color: var(--intel-gray-400); text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Management</p>
            </div>
            
            <a href="{{ route('admin.users.index') }}" 
               class="intel-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                Users
            </a>
            
            <a href="#" 
               class="intel-nav-link">
                <i class="fas fa-shield-alt"></i>
                Security Logs
            </a>
            
            <!-- System Section -->
            <div style="padding: 0 var(--space-lg); margin-bottom: var(--space-md); margin-top: var(--space-xl);">
                <p style="font-size: 0.75rem; font-weight: 600; color: var(--intel-gray-400); text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">System</p>
            </div>
            
            <a href="{{ route('admin.settings.index') }}" 
               class="intel-nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                Settings
            </a>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div style="flex: 1; display: flex; flex-direction: column; overflow: hidden;">
        <!-- Header -->
        <header style="background: white; border-bottom: 1px solid var(--intel-gray-200); padding: var(--space-lg) var(--space-xl);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--intel-gray-900); margin: 0;">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div style="display: flex; align-items: center; gap: var(--space-lg);">
                    <!-- Language Selector -->
                    <select class="intel-form-select" style="width: auto; min-width: 120px;">
                        <option>🌐 EN</option>
                        <option>🇮🇳 தமிழ்</option>
                        <option>🇮🇳 ಕನ್ನಡ</option>
                        <option>🇮🇳 తెలుగు</option>
                        <option>🇮🇳 മലയാളം</option>
                        <option>🇮🇳 हिंदी</option>
                    </select>
                    
                    <!-- User Menu -->
                    <div style="display: flex; align-items: center; gap: var(--space-md);">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--intel-blue-500), var(--intel-blue-600)); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <span style="color: white; font-size: 0.875rem; font-weight: 600;">
                                {{ substr(auth()->user()->name ?? 'S', 0, 1) }}
                            </span>
                        </div>
                        <div style="font-size: 0.875rem;">
                            <p style="font-weight: 600; color: var(--intel-gray-900); margin: 0;">{{ auth()->user()->name }}</p>
                            <p style="color: var(--intel-gray-500); margin: 0;">{{ auth()->user()->primary_role }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Page Content -->
        <main class="intel-content" style="flex: 1; overflow-y: auto;">
            <!-- Flash Messages -->
            @if(session('success'))
            <div style="background: #dcfce7; border: 1px solid #86efac; border-left: 4px solid var(--success-color); border-radius: var(--radius-md); padding: var(--space-lg); margin-bottom: var(--space-lg);">
                <div style="display: flex; align-items: center; gap: var(--space-md);">
                    <i class="fas fa-check-circle" style="color: var(--success-color);"></i>
                    <span style="color: #166534; font-weight: 500;">{{ session('success') }}</span>
                </div>
            </div>
            @endif
            
            @if(session('error'))
            <div style="background: #fee2e2; border: 1px solid #f87171; border-left: 4px solid var(--error-color); border-radius: var(--radius-md); padding: var(--space-lg); margin-bottom: var(--space-lg);">
                <div style="display: flex; align-items: center; gap: var(--space-md);">
                    <i class="fas fa-exclamation-circle" style="color: var(--error-color);"></i>
                    <span style="color: #991b1b; font-weight: 500;">{{ session('error') }}</span>
                </div>
            </div>
            @endif
            
            @if($errors->any())
            <div style="background: #fee2e2; border: 1px solid #f87171; border-left: 4px solid var(--error-color); border-radius: var(--radius-md); padding: var(--space-lg); margin-bottom: var(--space-lg);">
                <div style="display: flex; align-items: start; gap: var(--space-md);">
                    <i class="fas fa-exclamation-triangle" style="color: var(--error-color); margin-top: var(--space-xs);"></i>
                    <div>
                        <p style="color: #991b1b; font-weight: 600; margin: 0 0 var(--space-sm) 0;">Please fix the following errors:</p>
                        <ul style="color: #dc2626; font-size: 0.875rem; margin: 0; padding-left: var(--space-md);">
                            @foreach($errors->all() as $error)
                            <li style="margin-bottom: var(--space-xs);">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif
            
            @yield('admin-content')
        </main>
    </div>
</div>
@endsection