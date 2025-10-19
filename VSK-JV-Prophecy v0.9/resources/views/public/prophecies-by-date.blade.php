@extends('layouts.app')

@section('title', 'Prophecies for ' . \Carbon\Carbon::parse($date)->format('d/m/Y'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Home
                    </a>
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
                    @endauth
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Page Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Prophecies for {{ \Carbon\Carbon::parse($date)->format('l, d/m/Y') }}
            </h1>
            <p class="text-gray-600">
                Viewing in: 
                @switch($language)
                    @case('ta') தமிழ் (Tamil) @break
                    @case('kn') ಕನ್ನಡ (Kannada) @break
                    @case('te') తెలుగు (Telugu) @break
                    @case('ml') മലയാളം (Malayalam) @break
                    @case('hi') हिंदी (Hindi) @break
                    @default English
                @endswitch
            </p>
        </div>
        
        <!-- Language Switcher -->
        <div class="flex justify-center mb-8">
            <div class="intel-card rounded-lg p-4">
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-gray-700">Switch Language:</span>
                    <div class="flex space-x-2">
                        @php
                            $languages = [
                                'en' => 'EN',
                                'ta' => 'TA',
                                'kn' => 'KN',
                                'te' => 'TE',
                                'ml' => 'ML',
                                'hi' => 'HI'
                            ];
                        @endphp
                        
                        @foreach($languages as $langCode => $langName)
                        <a href="{{ route('prophecies.by-date', ['date' => $date, 'language' => $langCode]) }}"
                           class="px-3 py-1 rounded-md text-sm font-medium {{ $language === $langCode ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ $langName }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Prophecies List -->
        @if($prophecies->count() > 0)
            <div class="space-y-6">
                @foreach($prophecies as $prophecy)
                <div class="intel-card rounded-lg p-6">
                    <div class="flex items-start space-x-4">
                        @if($prophecy->featured_image)
                        <img src="{{ Storage::url($prophecy->featured_image) }}" 
                             alt="{{ $prophecy->title }}"
                             class="w-20 h-20 rounded-lg object-cover flex-shrink-0">
                        @else
                        <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-scroll text-gray-400 text-2xl"></i>
                        </div>
                        @endif
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                        @if($prophecy->translations->isNotEmpty())
                                            {{ $prophecy->translations->first()->title }}
                                        @else
                                            {{ $prophecy->title }}
                                        @endif
                                    </h3>
                                    
                                    @if($prophecy->category)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mb-2"
                                          style="background-color: {{ $prophecy->category->color }}20; color: {{ $prophecy->category->color }}">
                                        <i class="{{ $prophecy->category->icon }} mr-1"></i>
                                        {{ $prophecy->category->name }}
                                    </span>
                                    @endif
                                    
                                    <p class="text-gray-600 mb-4">
                                        @if($prophecy->translations->isNotEmpty() && $prophecy->translations->first()->excerpt)
                                            {{ $prophecy->translations->first()->excerpt }}
                                        @elseif($prophecy->excerpt)
                                            {{ $prophecy->excerpt }}
                                        @else
                                            {{ Str::limit($prophecy->description, 200) }}
                                        @endif
                                    </p>
                                    
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span><i class="fas fa-eye mr-1"></i>{{ $prophecy->view_count }} views</span>
                                        <span><i class="fas fa-download mr-1"></i>{{ $prophecy->download_count }} downloads</span>
                                        <span><i class="fas fa-print mr-1"></i>{{ $prophecy->print_count }} prints</span>
                                    </div>
                                </div>
                                
                                <div class="flex flex-col space-y-2 ml-4">
                                    <a href="{{ route('prophecies.show', ['id' => $prophecy->id, 'language' => $language]) }}"
                                       class="intel-btn-primary px-4 py-2 rounded-lg text-sm font-medium text-center">
                                        <i class="fas fa-eye mr-2"></i>View
                                    </a>
                                    
                                    <a href="{{ route('prophecy.download.web2pdf', ['id' => $prophecy->id, 'language' => $language]) }}"
                                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium text-center transition-colors">
                                        <i class="fas fa-download mr-2"></i>PDF
                                    </a>
                                    
                                    <a href="{{ route('prophecies.print', ['id' => $prophecy->id, 'language' => $language]) }}"
                                       target="_blank"
                                       class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium text-center transition-colors">
                                        <i class="fas fa-print mr-2"></i>Print
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="intel-card rounded-lg p-8">
                    <i class="fas fa-calendar-times text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Prophecies Found</h3>
                    <p class="text-gray-600 mb-4">
                        No prophecies are available for {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }} in the selected language.
                    </p>
                    <a href="{{ route('home') }}" 
                       class="intel-btn-primary px-6 py-3 rounded-lg font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Home
                    </a>
                </div>
            </div>
        @endif
    </main>
</div>
@endsection
