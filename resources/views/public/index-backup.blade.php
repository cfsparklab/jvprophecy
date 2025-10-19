@extends('layouts.app')

@section('title', 'Jebikalam Vaanga Prophecy - Select Date')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">

                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Jebikalam Vaanga Prophecy</h1>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-medium text-sm">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span class="text-sm text-gray-700">{{ auth()->user()->name }}</span>
                        </div>
                        
                        @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'editor']))
                        <a href="{{ route('admin.dashboard') }}" 
                           class="intel-btn-primary px-4 py-2 rounded-lg text-sm font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i>Admin Dashboard
                        </a>
                        @endif
                        
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-900 text-sm">
                                <i class="fas fa-sign-out-alt mr-1"></i>Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                            <i class="fas fa-sign-in-alt mr-1"></i>Login
                        </a>
                        <a href="{{ route('register') }}" 
                           class="intel-btn-primary px-4 py-2 rounded-lg text-sm font-medium">
                            <i class="fas fa-user-plus mr-2"></i>Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Welcome Section -->
        <div class="text-center mb-12">
            @auth
            <div class="mb-6">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <span class="text-white text-2xl font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">
                    Welcome back, {{ auth()->user()->name }}!
                </h2>
                <p class="text-lg text-gray-600">
                    Ready to explore divine revelations? Choose a date below to discover prophecies.
                </p>
            </div>
            @else
            <h2 class="text-4xl font-bold text-gray-900 mb-4">
                Discover Divine Revelations
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Explore Christian prophecies by selecting a specific Jebikalam Vaanga date. 
                Access spiritual content in your preferred language.
            </p>
            @endauth
        </div>
        
        <!-- Available Dates Section -->
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl mb-6 shadow-lg">
                    <i class="fas fa-calendar-alt text-white text-3xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-4">Available Prophecy Dates</h3>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Discover divine revelations by selecting a specific Jebikalam Vaanga date. Each date contains prophecies available in multiple languages with secure access.
                </p>
            </div>
            
            @if(!empty($availableDates) && count($availableDates) > 0)
                <!-- Statistics Overview -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="intel-card rounded-xl p-6 text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-calendar-day text-blue-600 text-xl"></i>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">{{ count($availableDates) }}</div>
                        <div class="text-sm text-gray-600">Available Dates</div>
                    </div>
                    
                    <div class="intel-card rounded-xl p-6 text-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-scroll text-green-600 text-xl"></i>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">{{ collect($availableDates)->sum('prophecy_count') }}</div>
                        <div class="text-sm text-gray-600">Total Prophecies</div>
                    </div>
                    
                    <div class="intel-card rounded-xl p-6 text-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-language text-purple-600 text-xl"></i>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">6</div>
                        <div class="text-sm text-gray-600">Languages Supported</div>
                    </div>
                </div>
                
                <!-- Date Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($availableDates as $dateInfo)
                    <div class="group intel-card rounded-xl p-6 hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:-translate-y-1 border-l-4 border-blue-500"
                         onclick="viewPropheciesForDate('{{ $dateInfo['date']->format('Y-m-d') }}')">
                        
                        <!-- Date Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-md">
                                    {{ $dateInfo['date']->format('d') }}
                                </div>
                                <div>
                                    <div class="text-lg font-bold text-gray-900">
                                        {{ $dateInfo['date']->format('M Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $dateInfo['date']->format('l') }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-blue-600">{{ $dateInfo['prophecy_count'] }}</div>
                                <div class="text-xs text-gray-500 uppercase tracking-wide">
                                    {{ $dateInfo['prophecy_count'] == 1 ? 'Prophecy' : 'Prophecies' }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Full Date Display -->
                        <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                            <div class="text-center">
                                <div class="text-sm text-gray-600 mb-1">Jebikalam Vaanga Date</div>
                                <div class="text-lg font-semibold text-gray-900">
                                    {{ $dateInfo['formatted_date'] }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Language Availability -->
                        <div class="mb-4">
                            <div class="text-xs font-medium text-gray-700 mb-2 uppercase tracking-wide">Available Languages</div>
                            <div class="flex flex-wrap gap-1">
                                @php
                                    $languageInfo = [
                                        'en' => ['flag' => '🇺🇸', 'name' => 'English', 'code' => 'EN'],
                                        'ta' => ['flag' => '🇮🇳', 'name' => 'Tamil', 'code' => 'TA'],
                                        'kn' => ['flag' => '🇮🇳', 'name' => 'Kannada', 'code' => 'KN'],
                                        'te' => ['flag' => '🇮🇳', 'name' => 'Telugu', 'code' => 'TE'],
                                        'ml' => ['flag' => '🇮🇳', 'name' => 'Malayalam', 'code' => 'ML'],
                                        'hi' => ['flag' => '🇮🇳', 'name' => 'Hindi', 'code' => 'HI']
                                    ];
                                @endphp
                                
                                @foreach($languageInfo as $langCode => $langData)
                                    @if(in_array($langCode, $dateInfo['available_languages']))
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800 border border-green-200" 
                                              title="{{ $langData['name'] }} Available">
                                            <span class="mr-1">{{ $langData['flag'] }}</span>
                                            {{ $langData['code'] }}
                                            <i class="fas fa-check ml-1 text-green-600"></i>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-500 border border-gray-200" 
                                              title="{{ $langData['name'] }} Not Available">
                                            {{ $langData['code'] }}
                                            <i class="fas fa-times ml-1 text-gray-400"></i>
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Action Button -->
                        <div class="pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Click to explore</span>
                                <div class="flex items-center text-blue-600 group-hover:text-blue-700">
                                    <span class="font-medium mr-1">View Prophecies</span>
                                    <i class="fas fa-arrow-right transform group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hover Effect Indicator -->
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white">
                                <i class="fas fa-external-link-alt text-xs"></i>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Quick Access Section -->
                <div class="mt-12 intel-card rounded-xl p-8">
                    <div class="text-center mb-6">
                        <h4 class="text-xl font-bold text-gray-900 mb-2">Quick Access</h4>
                        <p class="text-gray-600">Jump to the most recent or popular prophecy dates</p>
                    </div>
                    
                    <div class="flex flex-wrap justify-center gap-4">
                        @foreach($availableDates->take(3) as $recentDate)
                        <button onclick="viewPropheciesForDate('{{ $recentDate['date']->format('Y-m-d') }}')"
                                class="flex items-center space-x-2 px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition-colors">
                            <i class="fas fa-calendar-day"></i>
                            <span class="font-medium">{{ $recentDate['formatted_date'] }}</span>
                            <span class="text-xs bg-blue-200 px-2 py-1 rounded-full">{{ $recentDate['prophecy_count'] }}</span>
                        </button>
                        @endforeach
                    </div>
                </div>
                
            @else
                <!-- Empty State -->
                <div class="intel-card rounded-xl p-12 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-calendar-times text-gray-400 text-4xl"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900 mb-4">No Prophecies Available Yet</h4>
                    <p class="text-lg text-gray-600 mb-6 max-w-md mx-auto">
                        We're preparing divine revelations for you. Check back soon for new prophecy dates and content.
                    </p>
                    <div class="flex items-center justify-center space-x-4">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-clock mr-2"></i>
                            <span>Updated {{ now()->format('d/m/Y H:i') }} IST</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Categories Section -->
        @if(!empty($categories) && count($categories) > 0)
        <div class="mt-12">
            <h3 class="text-2xl font-bold text-gray-900 text-center mb-8">Browse by Category</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categories as $category)
                <div class="intel-card rounded-lg p-6 hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center" 
                             style="background-color: {{ $category->color }}20; color: {{ $category->color }}">
                            <i class="{{ $category->icon }} text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h4>
                            <p class="text-sm text-gray-600">{{ $category->description }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Search Section -->
        <div class="mt-12 max-w-2xl mx-auto">
            <div class="intel-card rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">
                    <i class="fas fa-search mr-2"></i>Search Prophecies
                </h3>
                
                <form action="{{ route('search') }}" method="GET" class="flex space-x-4">
                    <input type="text" name="q" placeholder="Search prophecies..." 
                           value="{{ request('q') }}"
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" 
                            class="intel-btn-primary px-6 py-2 rounded-lg font-medium">
                        Search
                    </button>
                </form>
            </div>
        </div>
        
        @auth
        <!-- User Activity Section -->
        <div class="mt-16">
            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Your Spiritual Journey</h3>
                <p class="text-gray-600">Track your engagement with divine revelations</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="intel-card rounded-xl p-6 text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-eye text-white text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Prophecies Viewed</h4>
                    <p class="text-3xl font-bold text-blue-600 mb-2">{{ auth()->user()->prophecies()->sum('view_count') ?? 0 }}</p>
                    <p class="text-sm text-gray-600">Total views across all prophecies</p>
                </div>
                
                <div class="intel-card rounded-xl p-6 text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-download text-white text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Downloads</h4>
                    <p class="text-3xl font-bold text-green-600 mb-2">{{ auth()->user()->prophecies()->sum('download_count') ?? 0 }}</p>
                    <p class="text-sm text-gray-600">PDF downloads for offline reading</p>
                </div>
                
                <div class="intel-card rounded-xl p-6 text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-language text-white text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Your Language</h4>
                    <p class="text-2xl font-bold text-purple-600 mb-2">
                        @switch(auth()->user()->preferred_language)
                            @case('ta') தமிழ் @break
                            @case('kn') ಕನ್ನಡ @break
                            @case('te') తెలుగు @break
                            @case('ml') മലയാളം @break
                            @case('hi') हिंदी @break
                            @default English
                        @endswitch
                    </p>
                    <p class="text-sm text-gray-600">Preferred reading language</p>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="mt-12">
            <div class="intel-card rounded-xl p-8">
                <div class="text-center mb-6">
                    <h4 class="text-xl font-bold text-gray-900 mb-2">Continue Your Journey</h4>
                    <p class="text-gray-600">Pick up where you left off or explore new revelations</p>
                </div>
                
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('home') }}" 
                       class="flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-lg">
                        <i class="fas fa-calendar-alt"></i>
                        <span class="font-medium">Browse by Date</span>
                    </a>
                    
                    <a href="{{ route('search') }}" 
                       class="flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all duration-200 shadow-lg">
                        <i class="fas fa-search"></i>
                        <span class="font-medium">Search Prophecies</span>
                    </a>
                    
                    @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'editor']))
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-lg">
                        <i class="fas fa-cog"></i>
                        <span class="font-medium">Admin Dashboard</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @else
        <!-- Guest User Section -->
        <div class="mt-16">
            <div class="intel-card rounded-xl p-12 text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fas fa-user-plus text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Join Our Spiritual Community</h3>
                <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                    Create your account to access the complete collection of prophecies, download PDFs, 
                    and track your spiritual journey with personalized features.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" 
                       class="px-8 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-lg font-medium">
                        <i class="fas fa-user-plus mr-2"></i>Create Account
                    </a>
                    <a href="{{ route('login') }}" 
                       class="px-8 py-3 border-2 border-blue-500 text-blue-600 rounded-lg hover:bg-blue-50 transition-all duration-200 font-medium">
                        <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                    </a>
                </div>
            </div>
        </div>
        @endauth
    </main>
    
    <!-- Footer -->
    <footer class="bg-white border-t mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-gray-600">
                <p>&copy; {{ date('Y') }} Jebikalam Vaanga Prophecy. All rights reserved.</p>
                <p class="text-sm mt-2">
                    Version {{ config('app.version', '1.0.0.0') }} Build {{ config('app.build', '00001') }} • 
                    Secured System • {{ now()->format('d/m/Y H:i:s') }} IST
                </p>
            </div>
        </div>
    </footer>
</div>

@push('scripts')
<script>
    function viewPropheciesForDate(date) {
        const userLanguage = '{{ auth()->user()->preferred_language ?? "en" }}';
        window.location.href = `{{ route('prophecies.by-date') }}?date=${date}&language=${userLanguage}`;
    }
</script>
@endpush
@endsection
