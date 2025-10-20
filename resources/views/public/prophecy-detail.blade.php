@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('title', ($translation ? $translation->title : $prophecy->title) . ' - Jebikalam Vaanga Prophecy')

@section('content')
<!-- Professional Book-Style Themes -->
@include('components.book-styles')

<div style="min-height: 100vh;" class="paper-background">
    
    <!-- Professional Header -->
    <header style="background: white; box-shadow: var(--shadow-sm); border-bottom: 1px solid var(--intel-gray-200);">
        <div class="intel-container">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-lg) 0;">
                <!-- Navigation -->
                <div style="display: flex; align-items: center; gap: var(--space-lg);">
                    <a href="{{ route('home') }}" class="intel-btn intel-btn-secondary intel-btn-sm">
                        <i class="fas fa-arrow-left"></i>
                        Back to Home
                    </a>
                    
                    <div style="display: flex; align-items: center; gap: var(--space-sm);">
                        <i class="fas fa-scroll" style="color: var(--intel-blue-600); font-size: 1.25rem;"></i>
                        <h1 style="margin: 0; font-size: 1.25rem; font-weight: 600; color: var(--intel-gray-900);">Jebikalam Vaanga Prophecy</h1>
                    </div>
                </div>
                
                <!-- User Actions -->
                <div style="display: flex; align-items: center; gap: var(--space-md);">
                    @auth
                        <div style="display: flex; align-items: center; gap: var(--space-sm);">
                            <div style="width: 32px; height: 32px; background: var(--intel-blue-600); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.875rem;">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span style="font-size: 0.875rem; color: var(--intel-gray-700);">{{ auth()->user()->name }}</span>
                        </div>
                        
                        @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'editor']))
                        <a href="{{ route('admin.prophecies.show', $prophecy) }}" class="intel-btn intel-btn-info intel-btn-sm">
                            <i class="fas fa-cog"></i>
                            Admin View
                        </a>
                        @endif
                        
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="intel-btn intel-btn-secondary intel-btn-sm">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="intel-btn intel-btn-primary intel-btn-sm">
                            <i class="fas fa-sign-in-alt"></i>
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="intel-container" style="padding: var(--space-xl) 0;">
        
        <!-- Language Switcher -->
        <div style="display: flex; justify-content: center; margin-bottom: var(--space-xl);">
            <div class="intel-card" style="padding: var(--space-lg);">
                <div style="display: flex; align-items: center; gap: var(--space-lg); flex-wrap: wrap; justify-content: center;">
                    <span style="font-size: 0.875rem; font-weight: 600; color: var(--intel-gray-700);">
                        <i class="fas fa-language" style="margin-right: var(--space-sm);"></i>
                        Switch Language:
                    </span>
                    <div style="display: flex; gap: var(--space-sm); flex-wrap: wrap;">
                        @php
                            $languages = [
                                'en' => 'English',
                                'ta' => 'தமிழ்', 
                                'kn' => 'ಕನ್ನಡ',
                                'te' => 'తెలుగు',
                                'ml' => 'മലയാളം',
                                'hi' => 'हिंदी'
                            ];
                        @endphp
                        
                        @foreach($languages as $langCode => $langName)
                        <a href="{{ route('prophecies.show', ['id' => $prophecy->id, 'language' => $langCode]) }}"
                           class="intel-btn {{ $language === $langCode ? 'intel-btn-primary' : 'intel-btn-secondary' }} intel-btn-sm"
                           style="min-width: 80px; text-align: center;">
                            {{ $langName }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Book-Style Content -->
        <article class="book-page page-curl" style="max-width: 850px; margin: 0 auto; padding: 3.5rem 4rem 4rem 4rem; position: relative;">
            
            <!-- Decorative page number top -->
            <div style="position: absolute; top: 1.5rem; right: 2rem; font-family: 'Cinzel', serif; font-size: 0.875rem; font-weight: 600;" class="theme-accent-dark">
                {{ date('d.m.Y') }}
            </div>
            
            <!-- Decorative ornament -->
            <div style="text-align: center; margin-bottom: 2rem;">
                <svg width="100" height="20" viewBox="0 0 100 20" style="opacity: 0.6;">
                    <path d="M0 10 Q25 5, 50 10 T100 10" fill="none" stroke="var(--accent-color)" stroke-width="1.5"/>
                    <circle cx="50" cy="10" r="3" fill="var(--accent-color)"/>
                    <circle cx="25" cy="8" r="1.5" fill="var(--accent-color)"/>
                    <circle cx="75" cy="8" r="1.5" fill="var(--accent-color)"/>
                </svg>
            </div>
            
            <!-- Prophecy Header -->
            <header style="margin-bottom: 3rem; text-align: center; padding-bottom: 2rem;" class="theme-border">
                <h1 style="margin: 0 0 1rem 0; font-family: 'Cinzel', serif; font-size: 2.5rem; font-weight: 700; line-height: 1.3; letter-spacing: 0.02em; text-shadow: 1px 1px 2px var(--shadow-color);" class="theme-heading">
                    @if($translation && $translation->title)
                        {{ $translation->title }}
                    @else
                        {{ $prophecy->title }}
                    @endif
                </h1>
                
                <!-- Subtitle ornament -->
                <div style="font-family: 'Merriweather', serif; font-size: 0.95rem; font-style: italic; margin-top: 0.5rem;" class="theme-accent-dark">
                    Divine Revelation • {{ $prophecy->jebikalam_vanga_date->format('F d, Y') }}
                </div>
                
                
                <!-- Action Buttons -->
                <div style="display: flex; justify-content: center; gap: var(--space-md); flex-wrap: wrap; margin: 2rem 0;">
                    @if($prophecy->video_url)
                    <button onclick="openVideoModal('{{ $prophecy->video_url }}')" class="youtube-btn" style="background: #ff0000; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fab fa-youtube"></i>
                        Watch Video
                    </button>
                    @endif
                    
                        <!-- PDF Download (only if uploaded PDF exists) -->
                        @php
                            $hasPdf = false;
                            if ($language === 'en') {
                                $hasPdf = $prophecy->pdf_file && Storage::disk('public')->exists($prophecy->pdf_file);
                            } else {
                                $translation = $prophecy->translations->where('language', $language)->first();
                                $hasPdf = $translation && $translation->pdf_file && Storage::disk('public')->exists($translation->pdf_file);
                            }
                        @endphp
                        
                        @if($hasPdf)
                            <a href="{{ route('prophecies.download.pdf', ['id' => $prophecy->id, 'language' => $language]) }}" 
                               id="pdf-download-btn"
                               class="pdf-download-link"
                               style="background: #10b981; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease;" 
                               onmouseover="this.style.background='#059669'" 
                               onmouseout="this.style.background='#10b981'">
                                <i class="fas fa-file-pdf"></i>
                                <span id="download-text">Download PDF</span>
                            </a>
                        @else
                            <div style="background: #f3f4f6; color: #6b7280; padding: 12px 24px; border: 1px solid #d1d5db; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                                <i class="fas fa-clock"></i>
                                PDF Coming Soon
                            </div>
                        @endif
                    
                    <a href="{{ route('prophecies.print', ['id' => $prophecy->id, 'language' => $language]) }}" target="_blank" style="background: #3b82f6; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
                        <i class="fas fa-print"></i>
                        Print
                    </a>
                </div>
            </header>
            
            <!-- Featured Image -->
            @if($prophecy->featured_image)
            <div style="margin-bottom: var(--space-xl); text-align: center;">
                <img src="{{ Storage::url($prophecy->featured_image) }}" 
                     alt="{{ $prophecy->title }}"
                     style="max-width: 100%; height: auto; border-radius: var(--radius-lg); box-shadow: var(--shadow-md);">
            </div>
            @endif
            
            <!-- Excerpt - Illuminated Quote -->
            @if(($translation && $translation->excerpt) || $prophecy->excerpt)
            <div class="theme-bg-accent theme-border" style="border-left-width: 4px; border-right-width: 4px; padding: 2rem; margin-bottom: 3rem; border-radius: 4px; position: relative; box-shadow: inset 0 0 20px var(--quote-bg);">
                <!-- Opening quote mark -->
                <div style="position: absolute; top: -10px; left: 20px; font-size: 4rem; opacity: 0.3; font-family: Georgia, serif; line-height: 1;" class="theme-accent">"</div>
                
                <p style="margin: 0; font-family: 'Merriweather', serif; font-size: 1.25rem; font-weight: 400; line-height: 1.8; font-style: italic; text-align: center; padding: 0 1rem;" class="theme-heading">
                    {{ ($translation && $translation->excerpt) ? $translation->excerpt : $prophecy->excerpt }}
                </p>
                
                <!-- Closing quote mark -->
                <div style="position: absolute; bottom: -10px; right: 20px; font-size: 4rem; opacity: 0.3; font-family: Georgia, serif; line-height: 1;" class="theme-accent">"</div>
            </div>
            @endif
            
            <!-- Main Content - Book Reading Style -->
            <div class="reading-content prophecy-content" style="margin-bottom: 3rem;">
                @if($translation && !empty($translation->content) && strlen($translation->content) > 0)
                    <div class="content-display" lang="{{ $language }}">
                        {!! $translation->content !!}
                    </div>
                @elseif($translation && !empty($translation->description) && strlen($translation->description) > 0)
                    <div class="content-display" lang="{{ $language }}">
                        {!! $translation->description !!}
                    </div>
                @elseif($prophecy->description)
                    <div class="content-display" lang="en">
                        {!! $prophecy->description !!}
                    </div>
                @else
                    <!-- Translation Not Available -->
                    <div style="background: linear-gradient(135deg, var(--warning-color-light) 0%, #fef3c7 100%); border: 1px solid var(--warning-color); border-radius: var(--radius-lg); padding: var(--space-xl); text-align: center;">
                        <i class="fas fa-language" style="font-size: 3rem; color: var(--warning-color); margin-bottom: var(--space-lg);"></i>
                        <h3 style="margin: 0 0 var(--space-md) 0; font-size: 1.25rem; font-weight: 600; color: #92400e;">Translation Not Available</h3>
                        <p style="margin: 0 0 var(--space-md) 0; color: #b45309;">
                            This prophecy is not yet available in 
                            @switch($language)
                                @case('ta') தமிழ் (Tamil) @break
                                @case('kn') ಕನ್ನಡ (Kannada) @break
                                @case('te') తెలుగు (Telugu) @break
                                @case('ml') മലയാളം (Malayalam) @break
                                @case('hi') हिंदी (Hindi) @break
                                @default {{ ucfirst($language) }}
                            @endswitch
                        </p>
                        <p style="margin: 0; font-size: 0.875rem; color: #a16207;">
                            Please try selecting English or another available language above, or check back later.
                        </p>
                    </div>
                @endif
            </div>
            
            <!-- Prayer Points Section -->
            @php
                $prayerPoints = null;
                if ($translation && !empty($translation->prayer_points)) {
                    $prayerPoints = $translation->prayer_points;
                } elseif (!empty($prophecy->prayer_points)) {
                    $prayerPoints = $prophecy->prayer_points;
                }
            @endphp
            
            @if($prayerPoints)
            <!-- Prayer Points - Decorative Section -->
            <section style="margin-top: 3rem; padding-top: 3rem; border-top: 3px double var(--accent-color);">
                <!-- Decorative ornament -->
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <svg width="120" height="30" viewBox="0 0 120 30" style="opacity: 0.6;">
                        <path d="M10 15 L50 15 M70 15 L110 15" stroke="var(--accent-color)" stroke-width="1.5"/>
                        <circle cx="60" cy="15" r="8" fill="none" stroke="var(--accent-color)" stroke-width="1.5"/>
                        <path d="M55 15 L60 10 L65 15 L60 20 Z" fill="var(--accent-color)"/>
                    </svg>
                </div>
                
                <header style="margin-bottom: 2rem; text-align: center;">
                    <h2 style="margin: 0; font-family: 'Cinzel', serif; font-size: 2rem; font-weight: 600; letter-spacing: 0.05em; display: flex; align-items: center; justify-content: center; gap: 0.75rem;" class="theme-heading">
                        <i class="fas fa-praying-hands theme-accent"></i>
                        Prayer Points
                    </h2>
                    <p style="margin: 0.75rem 0 0 0; font-size: 0.95rem; font-family: 'Merriweather', serif; font-style: italic;" class="theme-accent-dark">
                        Sacred Intercessions for this Divine Word
                    </p>
                </header>
                
                <div class="reading-content prayer-points-content theme-bg-accent" style="border: 2px solid var(--border-color); border-radius: 6px; padding: 2.5rem; box-shadow: inset 0 0 30px var(--quote-bg);" lang="{{ $language }}">
                    {!! $prayerPoints !!}
                </div>
            </section>
            @endif
            
            <!-- Closing ornament -->
            <div style="text-align: center; margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
                <svg width="80" height="20" viewBox="0 0 80 20" style="opacity: 0.5;">
                    <path d="M0 10 L80 10" stroke="var(--accent-color)" stroke-width="1"/>
                    <circle cx="40" cy="10" r="4" fill="var(--accent-color)"/>
                    <circle cx="20" cy="10" r="2" fill="var(--accent-color)"/>
                    <circle cx="60" cy="10" r="2" fill="var(--accent-color)"/>
                </svg>
                <p style="margin: 1rem 0 0 0; font-family: 'Cinzel', serif; font-size: 0.875rem; letter-spacing: 0.1em;" class="theme-accent-dark">
                    ✦ END OF PROPHECY ✦
                </p>
            </div>
            
        </article>
        
    </main>
</div>

<!-- Video Modal -->
<div id="videoModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.9); z-index: 9999; justify-content: center; align-items: center;">
    <div style="position: relative; width: 90%; max-width: 1000px; background: white; border-radius: var(--radius-xl); overflow: hidden; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
        <!-- Modal Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-lg); background: linear-gradient(135deg, var(--intel-blue-600), var(--intel-blue-700)); color: white;">
            <h3 style="margin: 0; font-size: 1.25rem; font-weight: 600;">
                <i class="fas fa-play-circle" style="margin-right: var(--space-sm);"></i>
                Prophecy Video
            </h3>
            <button onclick="closeVideoModal()" style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer; padding: var(--space-sm); border-radius: var(--radius-md); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Video Container -->
        <div id="videoContainer" style="position: relative; width: 100%; height: 0; padding-bottom: 56.25%; /* 16:9 aspect ratio */">
            <iframe id="videoFrame" 
                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;"
                    allowfullscreen>
            </iframe>
        </div>
        
        <!-- Modal Footer -->
        <div style="padding: var(--space-lg); background: var(--intel-gray-50); text-align: center;">
            <p style="margin: 0; font-size: 0.875rem; color: var(--intel-gray-600);">
                <i class="fas fa-info-circle" style="margin-right: var(--space-xs);"></i>
                Press ESC to close or click the X button above
            </p>
        </div>
    </div>
</div>

<style>
/* Professional Content Display Styling */
.prophecy-content {
    font-family: 'Inter', 'Noto Sans', 'DejaVu Sans', 'Arial Unicode MS', Arial, sans-serif;
}

.content-display {
    line-height: 1.8;
    word-wrap: break-word;
}

.content-display p {
    margin-bottom: 1rem;
    line-height: 1.8;
}

.content-display strong, 
.content-display b {
    font-weight: 600 !important;
}

.content-display em, 
.content-display i {
    font-style: italic !important;
}

.content-display h1, .content-display h2, .content-display h3, 
.content-display h4, .content-display h5, .content-display h6 {
    font-weight: 600;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    color: var(--intel-gray-900);
}

.content-display ul, .content-display ol {
    margin: 1rem 0;
    padding-left: 2rem;
}

.content-display li {
    margin-bottom: 0.5rem;
    line-height: 1.6;
}

/* Multi-language Typography */
.content-display[lang="ta"] {
    font-family: 'Noto Sans Tamil', 'Latha', 'Vijaya', 'DejaVu Sans', Arial, sans-serif;
    font-size: 1.125rem;
    line-height: 2.0;
    letter-spacing: 0.5px;
}

.content-display[lang="kn"] {
    font-family: 'Noto Sans Kannada', 'DejaVu Sans', Arial, sans-serif;
    font-size: 1.125rem;
    line-height: 1.9;
}

.content-display[lang="te"] {
    font-family: 'Noto Sans Telugu', 'DejaVu Sans', Arial, sans-serif;
    font-size: 1.125rem;
    line-height: 1.9;
}

.content-display[lang="ml"] {
    font-family: 'Noto Sans Malayalam', 'DejaVu Sans', Arial, sans-serif;
    font-size: 1.125rem;
    line-height: 1.9;
}

.content-display[lang="hi"] {
    font-family: 'Noto Sans Devanagari', 'DejaVu Sans', Arial, sans-serif;
    font-size: 1.125rem;
    line-height: 1.8;
}

/* Prayer Points Content Styling */
.prayer-points-content {
    font-family: 'Inter', 'Noto Sans', 'DejaVu Sans', 'Arial Unicode MS', Arial, sans-serif;
    line-height: 1.8;
    word-wrap: break-word;
}

.prayer-points-content p {
    margin-bottom: 1rem;
    line-height: 1.8;
}

.prayer-points-content strong, 
.prayer-points-content b {
    font-weight: 600 !important;
}

.prayer-points-content em, 
.prayer-points-content i {
    font-style: italic !important;
}

.prayer-points-content h1, .prayer-points-content h2, .prayer-points-content h3, 
.prayer-points-content h4, .prayer-points-content h5, .prayer-points-content h6 {
    font-weight: 600;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    color: var(--intel-gray-900);
}

.prayer-points-content ul, .prayer-points-content ol {
    margin: 1rem 0;
    padding-left: 2rem;
}

.prayer-points-content li {
    margin-bottom: 0.5rem;
    line-height: 1.6;
}

/* Multi-language Typography for Prayer Points */
.prayer-points-content[lang="ta"] {
    font-family: 'Noto Sans Tamil', 'Latha', 'Vijaya', 'DejaVu Sans', Arial, sans-serif;
    font-size: 1.125rem;
    line-height: 2.0;
    letter-spacing: 0.5px;
}

.prayer-points-content[lang="kn"] {
    font-family: 'Noto Sans Kannada', 'DejaVu Sans', Arial, sans-serif;
    font-size: 1.125rem;
    line-height: 1.9;
}

.prayer-points-content[lang="te"] {
    font-family: 'Noto Sans Telugu', 'DejaVu Sans', Arial, sans-serif;
    font-size: 1.125rem;
    line-height: 1.9;
}

.prayer-points-content[lang="ml"] {
    font-family: 'Noto Sans Malayalam', 'DejaVu Sans', Arial, sans-serif;
    font-size: 1.125rem;
    line-height: 1.9;
}

.prayer-points-content[lang="hi"] {
    font-family: 'Noto Sans Devanagari', 'DejaVu Sans', Arial, sans-serif;
    font-size: 1.125rem;
    line-height: 1.8;
}

/* Responsive Design */
@media (max-width: 768px) {
    .intel-container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .prophecy-content {
        font-size: 0.9rem;
    }
    
    .content-display[lang="ta"],
    .content-display[lang="kn"],
    .content-display[lang="te"],
    .content-display[lang="ml"],
    .content-display[lang="hi"] {
        font-size: 1rem;
    }
    
    .prayer-points-content {
        font-size: 0.9rem;
    }
    
    .prayer-points-content[lang="ta"],
    .prayer-points-content[lang="kn"],
    .prayer-points-content[lang="te"],
    .prayer-points-content[lang="ml"],
    .prayer-points-content[lang="hi"] {
        font-size: 1rem;
    }
}

/* YouTube Button Styling */
.youtube-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #FF0000 0%, #CC0000 100%);
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(255, 0, 0, 0.3);
    text-decoration: none;
    position: relative;
    overflow: hidden;
}

.youtube-btn:hover {
    background: linear-gradient(135deg, #E60000 0%, #B30000 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 0, 0, 0.4);
}

.youtube-btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(255, 0, 0, 0.3);
}

.youtube-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.youtube-btn:hover::before {
    left: 100%;
}

.youtube-btn i {
    font-size: 1.2rem;
}

/* Print Styles */
@media print {
    .prophecy-content {
        font-size: 14px;
        line-height: 1.6;
    }
    
    .content-display h1, .content-display h2, .content-display h3 {
        font-size: 16px;
        font-weight: bold;
    }
    
    .content-display strong, .content-display b {
        font-size: 16px;
        font-weight: bold;
    }
    
    .youtube-btn {
        display: none !important;
    }
    
    .prayer-points-content {
        font-size: 14px;
        line-height: 1.6;
        background: #f8f9fa !important;
        border: 1px solid #dee2e6 !important;
    }
    
    .prayer-points-content h1, .prayer-points-content h2, .prayer-points-content h3 {
        font-size: 16px;
        font-weight: bold;
    }
    
    .prayer-points-content strong, .prayer-points-content b {
        font-size: 16px;
        font-weight: bold;
    }
}
</style>

<script>
// Video Modal Functions
function openVideoModal(videoUrl) {
    const modal = document.getElementById('videoModal');
    const videoFrame = document.getElementById('videoFrame');
    const videoContainer = document.getElementById('videoContainer');
    
    try {
        // Show loading state
        showVideoLoading();
        
        // Show modal first
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
        
        // Convert video URL to embed format
        const embedUrl = convertToEmbedUrl(videoUrl);
        
        if (embedUrl) {
            console.log('Loading video:', embedUrl);
            
            // Create new iframe with enhanced attributes for autoplay
            const newIframe = document.createElement('iframe');
            newIframe.id = 'videoFrame';
            newIframe.src = embedUrl;
            newIframe.style.cssText = 'position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;';
            newIframe.setAttribute('allowfullscreen', '');
            newIframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
            
            // Add load event listener
            newIframe.onload = function() {
                console.log('Video loaded successfully');
                // Hide loading state after a short delay to ensure video is ready
                setTimeout(() => {
                    const loadingDiv = videoContainer.querySelector('.video-loading');
                    if (loadingDiv) {
                        loadingDiv.style.display = 'none';
                    }
                }, 1000);
            };
            
            newIframe.onerror = function() {
                console.error('Error loading video');
                showVideoError('Unable to load video. The video may be unavailable or restricted.');
            };
            
            // Replace the iframe
            videoContainer.innerHTML = '';
            videoContainer.appendChild(newIframe);
            
            // Focus on modal for accessibility
            modal.focus();
            
        } else {
            showVideoError('Invalid video URL format. Please check the video link.');
        }
    } catch (error) {
        console.error('Error opening video modal:', error);
        showVideoError('An error occurred while trying to load the video.');
    }
}

function showVideoLoading() {
    const videoContainer = document.getElementById('videoContainer');
    
    videoContainer.innerHTML = `
        <div class="video-loading" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; background: #000; color: white; text-align: center; padding: 2rem;">
            <div style="width: 60px; height: 60px; border: 4px solid #333; border-top: 4px solid #FF0000; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 1rem;"></div>
            <h3 style="margin: 0 0 0.5rem 0; color: white;">Loading Video...</h3>
            <p style="margin: 0; color: #ccc; font-size: 0.9rem;">Please wait while the video loads</p>
        </div>
        <style>
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
    `;
}

function showVideoError(message) {
    const modal = document.getElementById('videoModal');
    const videoContainer = document.getElementById('videoContainer');
    
    videoContainer.innerHTML = `
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; background: #f8f9fa; color: #6c757d; text-align: center; padding: 2rem;">
            <i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 1rem; color: #ffc107;"></i>
            <h3 style="margin: 0 0 1rem 0; color: #495057;">Video Unavailable</h3>
            <p style="margin: 0; max-width: 400px; line-height: 1.5;">${message}</p>
            <button onclick="closeVideoModal()" style="margin-top: 1.5rem; padding: 0.5rem 1rem; background: #007bff; color: white; border: none; border-radius: 0.25rem; cursor: pointer;">
                Close
            </button>
        </div>
    `;
}

function closeVideoModal() {
    const modal = document.getElementById('videoModal');
    const videoFrame = document.getElementById('videoFrame');
    const videoContainer = document.getElementById('videoContainer');
    
    modal.style.display = 'none';
    videoFrame.src = ''; // Stop video playback
    document.body.style.overflow = ''; // Restore scrolling
    
    // Reset video container to original iframe structure
    videoContainer.innerHTML = `
        <iframe id="videoFrame" 
                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;"
                allowfullscreen>
        </iframe>
    `;
}

function convertToEmbedUrl(url) {
    try {
        // YouTube URL conversion with enhanced autoplay parameters
        if (url.includes('youtube.com/watch?v=')) {
            const videoId = url.split('v=')[1].split('&')[0];
            return `https://www.youtube.com/embed/${videoId}?autoplay=1&mute=0&rel=0&modestbranding=1&fs=1&cc_load_policy=0&iv_load_policy=3&enablejsapi=1&origin=${window.location.origin}`;
        }
        
        // YouTube short URL conversion with enhanced autoplay parameters
        if (url.includes('youtu.be/')) {
            const videoId = url.split('youtu.be/')[1].split('?')[0];
            return `https://www.youtube.com/embed/${videoId}?autoplay=1&mute=0&rel=0&modestbranding=1&fs=1&cc_load_policy=0&iv_load_policy=3&enablejsapi=1&origin=${window.location.origin}`;
        }
        
        // Vimeo URL conversion with enhanced autoplay parameters
        if (url.includes('vimeo.com/')) {
            const videoId = url.split('vimeo.com/')[1].split('?')[0];
            return `https://player.vimeo.com/video/${videoId}?autoplay=1&muted=0&title=0&byline=0&portrait=0&background=0`;
        }
        
        // If already an embed URL, enhance it with autoplay if missing
        if (url.includes('/embed/') || url.includes('player.vimeo.com')) {
            if (!url.includes('autoplay=1')) {
                const separator = url.includes('?') ? '&' : '?';
                return url + separator + 'autoplay=1';
            }
            return url;
        }
        
        // For other URLs, try to use as iframe src directly
        return url;
    } catch (error) {
        console.error('Error converting video URL:', error);
        return url; // Return original URL as fallback
    }
}

// Close modal on ESC key press
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeVideoModal();
    }
});

// Close modal when clicking outside the video container
document.getElementById('videoModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeVideoModal();
    }
});

// Log prophecy view activity
document.addEventListener('DOMContentLoaded', function() {
    // Log activity if function exists
    if (typeof logActivity === 'function') {
        logActivity('prophecy_view', {
            prophecy_id: {{ $prophecy->id }},
            prophecy_title: '{{ addslashes($prophecy->title) }}',
            language: '{{ $language }}',
            jebikalam_vanga_date: '{{ $prophecy->jebikalam_vanga_date->format('Y-m-d') }}'
        });
    }
    
    // Increment view count
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        fetch('/api/prophecies/{{ $prophecy->id }}/increment-view', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
            }
        }).catch(function(error) {
            console.log('View count increment failed:', error);
        });
    }
    
    // Mobile PDF Download Handler
    // NOTE: Disabled mobile-specific handler as it causes session issues
    // Mobile now uses same method as desktop (which works perfectly)
    const pdfDownloadBtn = document.getElementById('pdf-download-btn');
    
    if (pdfDownloadBtn) {
        // Detect if user is on mobile device
        // Temporarily disabled to use standard download for all devices
        const isMobile = false; // /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        
        if (isMobile) {
            pdfDownloadBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const url = this.href;
                const downloadText = document.getElementById('download-text');
                const originalText = downloadText.textContent;
                
                // Show loading state
                downloadText.textContent = 'Downloading...';
                this.style.opacity = '0.7';
                this.style.pointerEvents = 'none';
                
                // Fetch PDF as blob
                fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/pdf',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers.get('content-type'));
                    
                    if (!response.ok) {
                        console.error('Response not OK:', response.status, response.statusText);
                        throw new Error('Server error: ' + response.status);
                    }
                    
                    // Check content type - be more lenient
                    const contentType = response.headers.get('content-type');
                    console.log('Content-Type:', contentType);
                    
                    // Accept both application/pdf and application/octet-stream
                    if (contentType && !contentType.includes('application/pdf') && !contentType.includes('application/octet-stream')) {
                        console.error('Invalid content type:', contentType);
                        // If it's HTML, user might not be logged in
                        if (contentType.includes('text/html')) {
                            throw new Error('Session expired. Please login again.');
                        }
                        throw new Error('Invalid file type received');
                    }
                    
                    return response.blob();
                })
                .then(blob => {
                    console.log('Blob received:', blob.size, 'bytes, type:', blob.type);
                    // Create blob URL
                    const blobUrl = window.URL.createObjectURL(blob);
                    
                    // Extract filename from URL or use default
                    const filename = 'prophecy_{{ $prophecy->id }}_{{ $language }}.pdf';
                    
                    // Create temporary link and trigger download
                    const tempLink = document.createElement('a');
                    tempLink.href = blobUrl;
                    tempLink.download = filename;
                    tempLink.style.display = 'none';
                    document.body.appendChild(tempLink);
                    tempLink.click();
                    document.body.removeChild(tempLink);
                    
                    // Clean up blob URL
                    setTimeout(() => {
                        window.URL.revokeObjectURL(blobUrl);
                    }, 100);
                    
                    // Reset button state
                    downloadText.textContent = 'Download Complete! ✓';
                    setTimeout(() => {
                        downloadText.textContent = originalText;
                        pdfDownloadBtn.style.opacity = '1';
                        pdfDownloadBtn.style.pointerEvents = 'auto';
                    }, 2000);
                })
                .catch(error => {
                    console.error('PDF download failed:', error);
                    downloadText.textContent = 'Download Failed';
                    
                    // Show specific error message
                    let errorMsg = 'Failed to download PDF. ';
                    if (error.message.includes('Session expired')) {
                        errorMsg = 'Your session has expired. Please login again and try downloading.';
                    } else if (error.message.includes('Server error')) {
                        errorMsg = 'Server error occurred. Please try again in a moment.';
                    } else if (error.message.includes('Invalid file')) {
                        errorMsg = 'Invalid file received from server. Please contact support.';
                    } else {
                        errorMsg += 'Error: ' + error.message;
                    }
                    
                    alert(errorMsg);
                    
                    setTimeout(() => {
                        downloadText.textContent = originalText;
                        pdfDownloadBtn.style.opacity = '1';
                        pdfDownloadBtn.style.pointerEvents = 'auto';
                    }, 3000);
                });
            });
            
            console.log('Mobile PDF download handler activated');
        } else {
            console.log('Desktop detected - using standard download');
        }
    }
});
</script>
@endsection