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
    <main class="intel-container" style="padding: 2.5rem 0;">
        
        <!-- Modern Content Container -->
        <div style="max-width: 1100px; margin: 0 auto;">
            
            <!-- Prophecy Header Card -->
            <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); padding: 2.5rem; margin-bottom: 2rem;">
                
                <!-- Meta Info Bar -->
                <div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 2px solid #f1f5f9; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-calendar-alt" style="color: #3b82f6; font-size: 1.125rem;"></i>
                        <span style="font-size: 0.9rem; font-weight: 600; color: #475569;">{{ $prophecy->jebikalam_vanga_date->format('F d, Y') }}</span>
                    </div>
                    
                    @if($prophecy->category)
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-tag" style="color: #8b5cf6; font-size: 1.125rem;"></i>
                        <span style="font-size: 0.9rem; font-weight: 600; color: #475569;">{{ $prophecy->category->name }}</span>
                    </div>
                    @endif
                    
                    @if($prophecy->week_number)
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-calendar-week" style="color: #f59e0b; font-size: 1.125rem;"></i>
                        <span style="font-size: 0.9rem; font-weight: 600; color: #475569;">Week {{ $prophecy->week_number }}</span>
                    </div>
                    @endif
                </div>
                
                <!-- Title -->
                <h1 style="margin: 0 0 1.5rem 0; font-size: 2.5rem; font-weight: 800; line-height: 1.2; color: #1e293b; letter-spacing: -0.02em;">
                    @if($translation && $translation->title)
                        {{ $translation->title }}
                    @else
                        {{ $prophecy->title }}
                    @endif
                </h1>
                
                <!-- Language Switcher -->
                <div style="margin-bottom: 1.5rem; padding: 1.25rem; background: #f8fafc; border-radius: 12px;">
                    <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-language" style="color: #3b82f6; font-size: 1.125rem;"></i>
                            <span style="font-size: 0.9rem; font-weight: 600; color: #475569;">Language:</span>
                        </div>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
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
                               style="padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; text-decoration: none; transition: all 0.2s; {{ $language === $langCode ? 'background: #3b82f6; color: white;' : 'background: white; color: #64748b; border: 1px solid #e2e8f0;' }}"
                               onmouseover="if ('{{ $language }}' !== '{{ $langCode }}') { this.style.background='#e2e8f0'; this.style.borderColor='#cbd5e1'; }"
                               onmouseout="if ('{{ $language }}' !== '{{ $langCode }}') { this.style.background='white'; this.style.borderColor='#e2e8f0'; }">
                                {{ $langName }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                    @if($prophecy->video_url)
                    <button onclick="openVideoModal('{{ $prophecy->video_url }}')" 
                            style="background: #ff0000; color: white; padding: 0.875rem 1.5rem; border: none; border-radius: 10px; font-weight: 600; font-size: 0.9375rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.625rem; transition: all 0.2s; box-shadow: 0 2px 8px rgba(255, 0, 0, 0.2);"
                            onmouseover="this.style.background='#cc0000'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(255, 0, 0, 0.3)';"
                            onmouseout="this.style.background='#ff0000'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(255, 0, 0, 0.2)';">
                        <i class="fab fa-youtube" style="font-size: 1.125rem;"></i>
                        <span>Watch Video</span>
                    </button>
                    @endif
                    
                    @php
                        $hasPdf = false;
                        $pdfUrl = '';
                        $pdfService = app(\App\Services\PdfStorageService::class);
                        
                        if ($language === 'en') {
                            if ($prophecy->pdf_file && $pdfService->pdfExists($prophecy->pdf_file)) {
                                $hasPdf = true;
                                $pdfUrl = $pdfService->getPdfUrl($prophecy->pdf_file);
                            }
                        } else {
                            $translation = $prophecy->translations->where('language', $language)->first();
                            if ($translation && $translation->pdf_file && $pdfService->pdfExists($translation->pdf_file)) {
                                $hasPdf = true;
                                $pdfUrl = $pdfService->getPdfUrl($translation->pdf_file);
                            }
                        }
                    @endphp
                    
                    @if($hasPdf && $pdfUrl)
                        <a href="{{ $pdfUrl }}" 
                           target="_blank"
                           download="prophecy_{{ $prophecy->id }}_{{ $language }}.pdf"
                           id="pdf-download-btn"
                           style="background: #10b981; color: white; padding: 0.875rem 1.5rem; border: none; border-radius: 10px; font-weight: 600; font-size: 0.9375rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.625rem; transition: all 0.2s; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);"
                           onmouseover="this.style.background='#059669'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.3)';"
                           onmouseout="this.style.background='#10b981'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(16, 185, 129, 0.2)';">
                            <i class="fas fa-file-pdf" style="font-size: 1.125rem;"></i>
                            <span id="download-text">Download PDF</span>
                        </a>
                    @else
                        <div style="background: #f3f4f6; color: #6b7280; padding: 0.875rem 1.5rem; border: 1px solid #d1d5db; border-radius: 10px; font-weight: 600; font-size: 0.9375rem; display: inline-flex; align-items: center; gap: 0.625rem;">
                            <i class="fas fa-clock" style="font-size: 1.125rem;"></i>
                            <span>PDF Coming Soon</span>
                        </div>
                    @endif
                
                    <a href="{{ route('prophecies.print', ['id' => $prophecy->id, 'language' => $language]) }}" 
                       target="_blank" 
                       style="background: #3b82f6; color: white; padding: 0.875rem 1.5rem; border: none; border-radius: 10px; font-weight: 600; font-size: 0.9375rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.625rem; transition: all 0.2s; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);"
                       onmouseover="this.style.background='#2563eb'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.3)';"
                       onmouseout="this.style.background='#3b82f6'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(59, 130, 246, 0.2)';">
                        <i class="fas fa-print" style="font-size: 1.125rem;"></i>
                        <span>Print</span>
                    </a>
                </div>
            </div>
            
            <!-- Content Card -->
            <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); padding: 3rem;">
                
                <!-- Featured Image -->
                @if($prophecy->featured_image)
                <div style="margin-bottom: 2rem;">
                    <img src="{{ Storage::url($prophecy->featured_image) }}" 
                         alt="{{ $prophecy->title }}"
                         style="width: 100%; height: auto; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.1);">
                </div>
                @endif
                
                <!-- Excerpt Highlight -->
                @if(($translation && $translation->excerpt) || $prophecy->excerpt)
                <div style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-left: 4px solid #3b82f6; padding: 1.75rem 2rem; margin-bottom: 2.5rem; border-radius: 12px;">
                    <div style="display: flex; gap: 1rem;">
                        <div style="flex-shrink: 0;">
                            <i class="fas fa-quote-left" style="font-size: 1.75rem; color: #3b82f6; opacity: 0.7;"></i>
                        </div>
                        <p style="margin: 0; font-size: 1.125rem; font-weight: 500; line-height: 1.75; color: #1e40af; font-style: italic;">
                            {{ ($translation && $translation->excerpt) ? $translation->excerpt : $prophecy->excerpt }}
                        </p>
                    </div>
                </div>
                @endif
                
                <!-- Content Section Header -->
                <div style="margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid #f1f5f9;">
                    <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-scroll" style="color: #8b5cf6; font-size: 1.25rem;"></i>
                        <span>Prophecy Message</span>
                    </h2>
                </div>
                
                <!-- Main Content -->
                <div class="prophecy-content" style="font-size: 1.0625rem; line-height: 1.8; color: #334155;">
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
                        <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border: 2px solid #f59e0b; border-radius: 12px; padding: 2.5rem; text-align: center;">
                            <i class="fas fa-language" style="font-size: 3.5rem; color: #d97706; margin-bottom: 1.5rem; display: block;"></i>
                            <h3 style="margin: 0 0 1rem 0; font-size: 1.5rem; font-weight: 700; color: #92400e;">Translation Not Available</h3>
                            <p style="margin: 0 0 1rem 0; font-size: 1.0625rem; color: #b45309;">
                                This prophecy is not yet available in 
                                @switch($language)
                                    @case('ta') <strong>தமிழ் (Tamil)</strong> @break
                                    @case('kn') <strong>ಕನ್ನಡ (Kannada)</strong> @break
                                    @case('te') <strong>తెలుగు (Telugu)</strong> @break
                                    @case('ml') <strong>മലയാളം (Malayalam)</strong> @break
                                    @case('hi') <strong>हिंदी (Hindi)</strong> @break
                                    @default <strong>{{ ucfirst($language) }}</strong>
                                @endswitch
                            </p>
                            <p style="margin: 0; font-size: 0.9375rem; color: #a16207;">
                                Please select <strong>English</strong> or another available language above, or check back later.
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
                <div style="margin-top: 3rem; padding-top: 3rem; border-top: 3px solid #e2e8f0;">
                    <!-- Section Header -->
                    <div style="margin-bottom: 2rem;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.75rem;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);">
                                <i class="fas fa-praying-hands" style="color: white; font-size: 1.25rem;"></i>
                            </div>
                            <div>
                                <h2 style="margin: 0; font-size: 1.75rem; font-weight: 700; color: #1e293b;">Prayer Points</h2>
                                <p style="margin: 0.25rem 0 0 0; font-size: 0.9375rem; color: #64748b;">Pray these declarations over your life</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Prayer Content -->
                    <div style="background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%); border: 2px solid #a78bfa; border-radius: 12px; padding: 2.5rem;" lang="{{ $language }}">
                        <div class="prayer-points-content" style="font-size: 1.0625rem; line-height: 1.8; color: #334155;">
                            {!! $prayerPoints !!}
                        </div>
                    </div>
                </div>
                @endif
                
            </div>
            
        </div>
        
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

/* Better content formatting */
.content-display p {
    margin-bottom: 1.25rem;
}

.content-display h1, 
.content-display h2, 
.content-display h3, 
.content-display h4 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 700;
    color: #1e293b;
}

.content-display h1 { font-size: 2rem; }
.content-display h2 { font-size: 1.5rem; }
.content-display h3 { font-size: 1.25rem; }
.content-display h4 { font-size: 1.125rem; }

.content-display ul,
.content-display ol {
    margin-bottom: 1.25rem;
    padding-left: 2rem;
}

.content-display li {
    margin-bottom: 0.625rem;
}

.content-display strong {
    font-weight: 700;
    color: #1e293b;
}

.content-display em {
    font-style: italic;
    color: #475569;
}

/* Prayer points styling */
.prayer-points-content p {
    margin-bottom: 1rem;
}

.prayer-points-content ul,
.prayer-points-content ol {
    margin-bottom: 1rem;
    padding-left: 1.75rem;
}

.prayer-points-content li {
    margin-bottom: 0.75rem;
    line-height: 1.75;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .content-display {
        font-size: 1rem !important;
    }
    
    .content-display h1 { font-size: 1.75rem; }
    .content-display h2 { font-size: 1.375rem; }
    .content-display h3 { font-size: 1.125rem; }
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
    
    // PDF Download now uses direct R2 URLs - no special handling needed
    // The download attribute and target="_blank" handle everything automatically
    console.log('PDF downloads configured - using direct cloud storage URLs');
});
</script>
@endsection