@extends('layouts.app')

@section('title', 'Jebikalam Vaanga Prophecy - Home')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Simplified Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo and Title -->
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center shadow-lg">

                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 sm:text-2xl">Jebikalam Vaanga Prophecy</h1>
                        <p class="text-xs text-gray-600 sm:text-sm">Simple. Secure. Spiritual.</p>
                    </div>
                </div>
                
                <!-- User Menu -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    @auth
                        <!-- User Avatar -->
                        <div class="tooltip">
                            <div class="flex items-center space-x-2 bg-blue-50 rounded-lg px-3 py-2">
                                @if(auth()->user()->avatar)
                                    <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="w-8 h-8 rounded-full">
                                @else
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center text-white font-medium text-sm">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="text-sm text-gray-700 hidden sm:block">{{ auth()->user()->name }}</span>
                            </div>
                            <span class="tooltiptext">Welcome, {{ auth()->user()->name }}!</span>
                        </div>
                        
                        <!-- Admin Access -->
                        @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'editor']))
                        <div class="tooltip">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="btn-simple btn-secondary-simple">
                                <i class="fas fa-cog mr-1 sm:mr-2"></i>
                                <span class="hidden sm:inline">Admin</span>
                            </a>
                            <span class="tooltiptext">Access admin dashboard to manage prophecies</span>
                        </div>
                        @endif
                        
                        <!-- Logout -->
                        <div class="tooltip">
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="btn-simple btn-secondary-simple">
                                    <i class="fas fa-sign-out-alt mr-1 sm:mr-2"></i>
                                    <span class="hidden sm:inline">Logout</span>
                                </button>
                            </form>
                            <span class="tooltiptext">Sign out of your account</span>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-simple btn-secondary-simple">
                            <i class="fas fa-sign-in-alt mr-1 sm:mr-2"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="btn-simple btn-primary-simple">
                            <i class="fas fa-user-plus mr-1 sm:mr-2"></i>Sign Up
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        @auth
        <!-- Welcome Section for Authenticated Users -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full mb-6 shadow-lg">
                <i class="fas fa-heart text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                Welcome back, {{ auth()->user()->name }}! 🙏
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Ready to explore God's revelations? Choose a date below to discover the prophecies waiting for you.
            </p>
        </div>

        <!-- Quick Actions for Authenticated Users -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <!-- Browse by Date -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-alt text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Browse by Date</h3>
                    <p class="text-gray-600 text-sm mb-4">Select a specific date to view prophecies</p>
                    <div class="help-text">
                        <i class="fas fa-info-circle"></i>
                        <span>Click on any available date below</span>
                    </div>
                </div>
            </div>

            <!-- Search Prophecies -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Search Prophecies</h3>
                    <p class="text-gray-600 text-sm mb-4">Find specific prophecies by keywords</p>
                    <div class="help-text">
                        <i class="fas fa-lightbulb"></i>
                        <span>Coming soon - Advanced search</span>
                    </div>
                </div>
            </div>

            <!-- Your Activity -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Your Activity</h3>
                    <p class="text-gray-600 text-sm mb-4">Track your spiritual journey</p>
                    <div class="help-text">
                        <i class="fas fa-heart"></i>
                        <span>View your reading history</span>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Welcome Section for Guests -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full mb-6 shadow-lg">
                <i class="fas fa-dove text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                Welcome to Jebikalam Vaanga Prophecy ✨
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-8">
                Discover divine revelations and spiritual guidance. Join our community to access a comprehensive collection of Christian prophecies.
            </p>
            
            <!-- Call to Action for Guests -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('register') }}" class="btn-simple btn-primary-simple text-lg px-8 py-4">
                    <i class="fas fa-user-plus mr-2"></i>
                    Get Started Free
                </a>
                <a href="{{ route('login') }}" class="btn-simple btn-secondary-simple text-lg px-8 py-4">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Already a Member?
                </a>
            </div>
            
            <div class="mt-6 text-sm text-gray-500">
                <i class="fas fa-shield-alt mr-1"></i>
                Secure • Free • No spam ever
            </div>
        </div>
        @endauth

        <!-- Available Prophecy Dates Section -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-calendar-check text-blue-600 mr-3"></i>
                    Available Prophecy Dates
                </h3>
                <p class="text-gray-600 mb-6">
                    @auth
                        Choose any date below to read the prophecies revealed on that day
                    @else
                        Sign up to access prophecies from these dates
                    @endauth
                </p>
                
                <!-- Statistics Overview -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="text-2xl font-bold text-blue-600">{{ count($availableDates) }}</div>
                        <div class="text-sm text-gray-600">Available Dates</div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="text-2xl font-bold text-green-600">{{ collect($availableDates)->sum('prophecy_count') }}</div>
                        <div class="text-sm text-gray-600">Total Prophecies</div>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4">
                        <div class="text-2xl font-bold text-purple-600">6</div>
                        <div class="text-sm text-gray-600">Languages Supported</div>
                    </div>
                </div>
            </div>

            @if(count($availableDates) > 0)
                <!-- Dates Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($availableDates as $dateInfo)
                        @auth
                            <a href="{{ route('prophecies.by-date', ['date' => $dateInfo['jebikalam_vanga_date']]) }}" 
                               class="block bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 card-hover border-2 border-transparent hover:border-blue-300 transition-all duration-300">
                        @else
                            <div class="block bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 opacity-75 cursor-not-allowed">
                        @endauth
                            <!-- Date Display -->
                            <div class="text-center mb-4">
                                <div class="text-2xl font-bold text-gray-900 mb-1">
                                    {{ \Carbon\Carbon::parse($dateInfo['jebikalam_vanga_date'])->format('d') }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($dateInfo['jebikalam_vanga_date'])->format('M Y') }}
                                </div>
                            </div>

                            <!-- Prophecy Count -->
                            <div class="text-center mb-4">
                                <div class="inline-flex items-center bg-blue-600 text-white px-3 py-1 rounded-full text-sm">
                                    <i class="fas fa-scroll mr-1"></i>
                                    {{ $dateInfo['prophecy_count'] }} {{ $dateInfo['prophecy_count'] == 1 ? 'Prophecy' : 'Prophecies' }}
                                </div>
                            </div>

                            <!-- Language Flags -->
                            <div class="flex justify-center space-x-1 mb-4">
                                @foreach($dateInfo['available_languages'] as $lang)
                                    <div class="tooltip">
                                        <span class="inline-flex items-center justify-center w-6 h-6 bg-white rounded-full text-xs font-medium shadow-sm
                                            {{ $lang === 'en' ? 'text-blue-600' : '' }}
                                            {{ $lang === 'ta' ? 'text-red-600' : '' }}
                                            {{ $lang === 'hi' ? 'text-orange-600' : '' }}
                                            {{ $lang === 'kn' ? 'text-yellow-600' : '' }}
                                            {{ $lang === 'te' ? 'text-green-600' : '' }}
                                            {{ $lang === 'ml' ? 'text-purple-600' : '' }}">
                                            {{ strtoupper($lang) }}
                                        </span>
                                        <span class="tooltiptext">
                                            Available in 
                                            @switch($lang)
                                                @case('en') English @break
                                                @case('ta') Tamil @break
                                                @case('hi') Hindi @break
                                                @case('kn') Kannada @break
                                                @case('te') Telugu @break
                                                @case('ml') Malayalam @break
                                            @endswitch
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            @guest
                                <div class="text-center">
                                    <div class="text-xs text-gray-500">
                                        <i class="fas fa-lock mr-1"></i>
                                        Login to access
                                    </div>
                                </div>
                            @endguest
                        @auth
                            </a>
                        @else
                            </div>
                        @endauth
                    @endforeach
                </div>
            @else
                <!-- No Dates Available -->
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">No Prophecies Available</h4>
                    <p class="text-gray-600">Check back later for new revelations.</p>
                </div>
            @endif
        </div>

        @auth
        <!-- User Activity Section -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">
                <i class="fas fa-user-chart text-blue-600 mr-3"></i>
                Your Spiritual Journey
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Prophecies Viewed -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 text-center">
                    <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-eye text-white"></i>
                    </div>
                    <div class="text-2xl font-bold text-blue-600 mb-1">0</div>
                    <div class="text-sm text-gray-600">Prophecies Viewed</div>
                    <div class="help-text mt-2">
                        <i class="fas fa-info-circle"></i>
                        <span>Start reading to track your progress</span>
                    </div>
                </div>

                <!-- Downloads -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 text-center">
                    <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-download text-white"></i>
                    </div>
                    <div class="text-2xl font-bold text-green-600 mb-1">0</div>
                    <div class="text-sm text-gray-600">Downloads</div>
                    <div class="help-text mt-2">
                        <i class="fas fa-file-pdf"></i>
                        <span>Save prophecies as PDF</span>
                    </div>
                </div>

                <!-- Preferred Language -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6 text-center">
                    <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-language text-white"></i>
                    </div>
                    <div class="text-lg font-bold text-purple-600 mb-1">
                        @switch(auth()->user()->preferred_language)
                            @case('en') English @break
                            @case('ta') Tamil @break
                            @case('hi') Hindi @break
                            @case('kn') Kannada @break
                            @case('te') Telugu @break
                            @case('ml') Malayalam @break
                            @default English
                        @endswitch
                    </div>
                    <div class="text-sm text-gray-600">Preferred Language</div>
                    <div class="help-text mt-2">
                        <i class="fas fa-cog"></i>
                        <span>Change in settings</span>
                    </div>
                </div>
            </div>
        </div>
        @endauth
    </main>

    <!-- Help Section -->
    <div class="bg-blue-600 text-white py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h3 class="text-2xl font-bold mb-4">Need Help Getting Started? 🤔</h3>
            <p class="text-blue-100 mb-6">
                New to prophecy reading? Here's how to make the most of your spiritual journey:
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
                <div class="bg-blue-700 rounded-lg p-6">
                    <div class="text-2xl mb-3">1️⃣</div>
                    <h4 class="font-semibold mb-2">Choose a Date</h4>
                    <p class="text-blue-100 text-sm">Select any available date from the calendar above to see prophecies from that day.</p>
                </div>
                
                <div class="bg-blue-700 rounded-lg p-6">
                    <div class="text-2xl mb-3">2️⃣</div>
                    <h4 class="font-semibold mb-2">Read & Reflect</h4>
                    <p class="text-blue-100 text-sm">Take your time to read and meditate on the divine messages revealed.</p>
                </div>
                
                <div class="bg-blue-700 rounded-lg p-6">
                    <div class="text-2xl mb-3">3️⃣</div>
                    <h4 class="font-semibold mb-2">Save & Share</h4>
                    <p class="text-blue-100 text-sm">Download prophecies as PDF or print them for offline reading and sharing.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex items-center justify-center mb-4">
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center mr-3">

                </div>
                <span class="text-lg font-semibold">Jebikalam Vaanga Prophecy</span>
            </div>
            <p class="text-gray-400 text-sm">
                Connecting hearts to divine revelations • Secure • Multi-language • Always free
            </p>
            <div class="mt-4 text-xs text-gray-500">
                Built with ❤️ for the Christian community
            </div>
        </div>
    </footer>
</div>
@endsection

@push('scripts')
<script>
    // Add smooth scrolling for better UX
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Add loading states for better feedback
    document.querySelectorAll('.card-hover').forEach(card => {
        card.addEventListener('click', function() {
            if (this.tagName === 'A') {
                this.classList.add('loading');
            }
        });
    });

    // Show success messages with animation
    @if(session('success'))
        setTimeout(() => {
            const alert = document.createElement('div');
            alert.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg fade-in z-50';
            alert.innerHTML = '<i class="fas fa-check-circle mr-2"></i>{{ session('success') }}';
            document.body.appendChild(alert);
            
            setTimeout(() => {
                alert.remove();
            }, 5000);
        }, 500);
    @endif
</script>
@endpush
