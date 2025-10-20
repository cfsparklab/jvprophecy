@php
use Illuminate\Support\Facades\Storage;
$pdfService = app(\App\Services\PdfStorageService::class);
@endphp

@extends('layouts.app')

@section('title', $prophecy->title . ' - Jebikalam Vaanga Prophecy')

@section('content')
<div style="min-height: 100vh; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
    
    <!-- Header -->
    <header style="background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 1.5rem 0;">
        <div class="intel-container" style="max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <!-- Back Button -->
                <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #64748b; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9375rem; transition: all 0.2s;"
                   onmouseover="this.style.background='#475569'" 
                   onmouseout="this.style.background='#64748b'">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Home</span>
                </a>
                
                <!-- User Info -->
                <div style="display: flex; align-items: center; gap: 1rem;">
                    @auth
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 36px; height: 36px; background: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.95rem;">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span style="font-size: 0.9rem; font-weight: 600; color: #1e293b;">{{ auth()->user()->name }}</span>
                        </div>
                        
                        @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'editor']))
                        <a href="{{ route('admin.prophecies.show', $prophecy) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #8b5cf6; color: white; padding: 0.75rem 1.25rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.875rem; transition: all 0.2s;"
                           onmouseover="this.style.background='#7c3aed'" 
                           onmouseout="this.style.background='#8b5cf6'">
                            <i class="fas fa-cog"></i>
                            <span>Admin</span>
                        </a>
                        @endif
                        
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #64748b; color: white; padding: 0.75rem 1.25rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem; border: none; cursor: pointer; transition: all 0.2s;"
                                    onmouseover="this.style.background='#475569'" 
                                    onmouseout="this.style.background='#64748b'">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #3b82f6; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9375rem; transition: all 0.2s;"
                           onmouseover="this.style.background='#2563eb'" 
                           onmouseout="this.style.background='#3b82f6'">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main style="padding: 4rem 1.5rem;">
        <div style="max-width: 1200px; margin: 0 auto;">
            
            <!-- Title -->
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <h1 style="font-size: 3rem; font-weight: 700; color: #1e293b; margin: 0 0 0.75rem 0; line-height: 1.2; letter-spacing: -0.02em;">
                    {{ $prophecy->title }}
                </h1>
                <p style="font-size: 1.5rem; font-weight: 500; color: #64748b; margin: 0;">
                    {{ $prophecy->jebikalam_vanga_date->format('F jS, Y') }}
                </p>
            </div>
            
            <!-- Featured Images & PDF Downloads -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 3rem; margin-top: 4rem; max-width: 900px; margin-left: auto; margin-right: auto;">
                
                @php
                    $languages = [
                        'ta' => 'Tamil',
                        'en' => 'English'
                    ];
                @endphp
                
                @foreach($languages as $langCode => $langName)
                    @php
                        $hasPdf = false;
                        $pdfUrl = '';
                        $featuredImage = $prophecy->featured_image;
                        
                        if ($langCode === 'en') {
                            if ($prophecy->pdf_file && $pdfService->pdfExists($prophecy->pdf_file)) {
                                $hasPdf = true;
                                $pdfUrl = $pdfService->getPdfUrl($prophecy->pdf_file);
                            }
                        } else {
                            $translation = $prophecy->translations->where('language', $langCode)->first();
                            if ($translation && $translation->pdf_file && $pdfService->pdfExists($translation->pdf_file)) {
                                $hasPdf = true;
                                $pdfUrl = $pdfService->getPdfUrl($translation->pdf_file);
                            }
                            // Use translation image if available
                            if ($translation && $translation->featured_image) {
                                $featuredImage = $translation->featured_image;
                            }
                        }
                    @endphp
                    
                    <div style="text-align: center;">
                        <!-- Featured Image (Clickable to open PDF) -->
                        @if($hasPdf)
                            <a href="{{ $pdfUrl }}" target="_blank" style="display: block; margin-bottom: 1.5rem; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.15); transition: all 0.3s ease; cursor: pointer;"
                               onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 32px rgba(0,0,0,0.2)';"
                               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 24px rgba(0,0,0,0.15)';">
                                @if($featuredImage)
                                    <img src="{{ Storage::url($featuredImage) }}" 
                                         alt="{{ $langName }} - {{ $prophecy->title }}"
                                         style="width: 100%; height: auto; display: block;">
                                @else
                                    <div style="width: 100%; aspect-ratio: 3/4; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
                                        <i class="fas fa-scroll"></i>
                                    </div>
                                @endif
                            </a>
                        @else
                            <div style="display: block; margin-bottom: 1.5rem; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.1); opacity: 0.5;">
                                @if($featuredImage)
                                    <img src="{{ Storage::url($featuredImage) }}" 
                                         alt="{{ $langName }} - {{ $prophecy->title }}"
                                         style="width: 100%; height: auto; display: block; filter: grayscale(100%);">
                                @else
                                    <div style="width: 100%; aspect-ratio: 3/4; background: #e2e8f0; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 3rem;">
                                        <i class="fas fa-scroll"></i>
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        <!-- Language Label -->
                        <h3 style="font-size: 1.5rem; font-weight: 600; color: #1e293b; margin: 0 0 1.25rem 0;">
                            {{ $langName }}
                        </h3>
                        
                        <!-- Download PDF Button -->
                        @if($hasPdf)
                            <a href="{{ $pdfUrl }}" 
                               download="prophecy_{{ $prophecy->id }}_{{ $langCode }}.pdf"
                               style="display: inline-flex; align-items: center; gap: 0.75rem; background: #2d3748; color: white; padding: 1rem 2.5rem; border-radius: 50px; text-decoration: none; font-weight: 600; font-size: 1rem; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(45, 55, 72, 0.3);"
                               onmouseover="this.style.background='#1a202c'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(45, 55, 72, 0.4)';"
                               onmouseout="this.style.background='#2d3748'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(45, 55, 72, 0.3)';">
                                <span>Download PDF</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        @else
                            <div style="display: inline-flex; align-items: center; gap: 0.75rem; background: #cbd5e1; color: #64748b; padding: 1rem 2.5rem; border-radius: 50px; font-weight: 600; font-size: 1rem; cursor: not-allowed; opacity: 0.6;">
                                <span>PDF Coming Soon</span>
                                <i class="fas fa-clock"></i>
                            </div>
                        @endif
                    </div>
                @endforeach
                
            </div>
            
            <!-- Optional: Add more languages if available -->
            @if($prophecy->translations->whereNotIn('language', ['ta', 'en'])->count() > 0)
                <div style="margin-top: 5rem; text-align: center;">
                    <h2 style="font-size: 1.75rem; font-weight: 700; color: #1e293b; margin-bottom: 2.5rem;">
                        Other Languages Available
                    </h2>
                    
                    <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; justify-content: center; max-width: 800px; margin: 0 auto;">
                        @foreach($prophecy->translations->whereNotIn('language', ['ta', 'en']) as $otherTranslation)
                            @php
                                $otherHasPdf = $otherTranslation->pdf_file && $pdfService->pdfExists($otherTranslation->pdf_file);
                                $otherPdfUrl = $otherHasPdf ? $pdfService->getPdfUrl($otherTranslation->pdf_file) : '';
                                $otherLangName = [
                                    'kn' => 'ಕನ್ನಡ (Kannada)',
                                    'te' => 'తెలుగు (Telugu)',
                                    'ml' => 'മലയാളം (Malayalam)',
                                    'hi' => 'हिंदी (Hindi)'
                                ][$otherTranslation->language] ?? ucfirst($otherTranslation->language);
                            @endphp
                            
                            @if($otherHasPdf)
                                <a href="{{ $otherPdfUrl }}" 
                                   download="prophecy_{{ $prophecy->id }}_{{ $otherTranslation->language }}.pdf"
                                   style="display: inline-flex; align-items: center; gap: 0.625rem; background: #3b82f6; color: white; padding: 0.875rem 1.75rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9375rem; transition: all 0.2s;"
                                   onmouseover="this.style.background='#2563eb'; this.style.transform='translateY(-2px)';"
                                   onmouseout="this.style.background='#3b82f6'; this.style.transform='translateY(0)';">
                                    <i class="fas fa-download"></i>
                                    <span>{{ $otherLangName }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
            
        </div>
    </main>
    
</div>

<style>
/* Responsive Design */
@media (max-width: 768px) {
    h1 {
        font-size: 2rem !important;
    }
    
    p[style*="font-size: 1.5rem"] {
        font-size: 1.125rem !important;
    }
    
    div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
        gap: 2rem !important;
    }
}

@media (max-width: 480px) {
    h1 {
        font-size: 1.5rem !important;
    }
    
    a[style*="padding: 1rem 2.5rem"] {
        padding: 0.875rem 2rem !important;
        font-size: 0.9375rem !important;
    }
}
</style>
@endsection
