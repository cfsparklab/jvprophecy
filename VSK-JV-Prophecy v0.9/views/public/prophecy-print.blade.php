<!DOCTYPE html>
<html lang="{{ $language }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $prophecy->translations->where('language', $language)->first()?->title ?? $prophecy->title }} - Jebikalam Vaanga Prophecy</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&family=Cinzel:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Book-Style Professional Themes -->
    <style>
/* COLOR SCHEMES - Classic as default for print */
:root,
body {
    --bg-color: #f4f1ea;
    --bg-lines: #e8e4d9;
    --bg-subtle: #f9f6ef;
    --page-bg-start: #f9f6ef;
    --page-bg-mid: #fcfaf5;
    --page-bg-end: #f9f6ef;
    --border-color: #e8dcc8;
    --text-color: #2d2820;
    --heading-color: #5a4a2f;
    --accent-color: #c9a961;
    --accent-dark: #8b6914;
    --quote-bg: rgba(201, 169, 97, 0.08);
    --shadow-color: rgba(0, 0, 0, 0.1);
}

/* PAPER BACKGROUND */
.paper-background {
    background-color: var(--bg-color);
    background-image: 
        linear-gradient(90deg, transparent 79px, var(--bg-lines) 79px, var(--bg-lines) 81px, transparent 81px),
        linear-gradient(var(--bg-subtle) 0.1em, transparent 0.1em);
    background-size: 100% 1.5em;
    position: relative;
}

.paper-background::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='4' height='4'%3E%3Cpath fill='%23000000' fill-opacity='0.02' d='M0 0h2v2H0zm2 2h2v2H2z'/%3E%3C/svg%3E");
    pointer-events: none;
}

/* BOOK PAGE STYLE */
.book-page {
    background: linear-gradient(to right, 
        var(--page-bg-start) 0%, 
        var(--page-bg-mid) 50%, 
        var(--page-bg-end) 100%
    );
    box-shadow: 
        0 2px 10px var(--shadow-color),
        inset 0 0 80px rgba(0, 0, 0, 0.03),
        inset 0 0 30px rgba(0, 0, 0, 0.02);
    border: 1px solid var(--border-color);
    border-radius: 4px;
    position: relative;
}

/* READING CONTENT */
.reading-content {
    font-family: 'Merriweather', 'Georgia', 'Times New Roman', serif;
    font-size: 1.125rem;
    line-height: 1.9;
    color: var(--text-color);
    text-align: justify;
    hyphens: auto;
}

/* Drop cap - first letter (DISABLED per user request) */
/* .reading-content > p:first-of-type::first-letter {
    font-size: 4.5rem;
    line-height: 0.85;
    float: left;
    font-weight: 700;
    margin: 0.1em 0.15em 0 0;
    color: var(--accent-dark);
    text-shadow: 2px 2px 4px var(--shadow-color);
} */

/* Headings */
.reading-content h1, .reading-content h2, .reading-content h3 {
    font-family: 'Cinzel', 'Georgia', serif;
    color: var(--heading-color);
    text-align: center;
    margin: 2rem 0 1.5rem 0;
    letter-spacing: 0.05em;
}

/* Quotes/blockquotes */
.reading-content blockquote {
    border-left: 4px solid var(--accent-color);
    background: var(--quote-bg);
    padding: 1.5rem 2rem;
    margin: 2rem 0;
    font-style: italic;
    border-radius: 4px;
}

/* THEME UTILITY CLASSES */
.theme-accent { color: var(--accent-color) !important; }
.theme-accent-dark { color: var(--accent-dark) !important; }
.theme-heading { color: var(--heading-color) !important; }
.theme-text { color: var(--text-color) !important; }
.theme-border { border-color: var(--accent-color) !important; }
.theme-bg-accent { background: var(--quote-bg) !important; }

/* PAGE CURL EFFECT */
.page-curl {
    position: relative;
}

.page-curl::after {
    content: '';
    position: absolute;
    bottom: 0;
    right: 0;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, transparent 50%, var(--shadow-color) 50%);
    box-shadow: -2px -2px 10px var(--shadow-color);
}

/* PRINT STYLES */
@media print {
    body {
        margin: 0;
        padding: 0;
    }
    
    .no-print {
        display: none !important;
    }
    
    .paper-background::before {
        display: none;
    }
    
    .page-curl::after {
        display: none;
    }
    
    .book-page {
        box-shadow: none;
        page-break-inside: avoid;
    }
    
    .watermark {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        font-size: 72px;
        color: rgba(0, 0, 0, 0.03);
        z-index: -1;
        font-weight: bold;
        pointer-events: none;
        font-family: 'Cinzel', serif;
    }
}

/* SCREEN STYLES */
@media screen {
    body {
        min-height: 100vh;
        margin: 0;
        padding: 40px 20px;
    }
    
    .print-actions {
        text-align: center;
        margin: 20px auto 30px;
        padding: 20px;
        background: white;
        border-radius: 12px;
        max-width: 850px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .btn {
        display: inline-block;
        padding: 12px 24px;
        margin: 0 8px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        border: none;
        cursor: pointer;
    }
    
    .btn-primary {
        background: #3b82f6;
        color: white;
    }
    
    .btn-primary:hover {
        background: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
    }
    
    .btn-secondary {
        background: #6b7280;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #4b5563;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(107, 114, 128, 0.3);
    }
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .reading-content {
        font-size: 1rem;
        line-height: 1.8;
    }
    
    /* Drop cap disabled */
    /* .reading-content > p:first-of-type::first-letter {
        font-size: 3.5rem;
    } */
    
    .book-page {
        padding: 2rem 1.5rem !important;
    }
}
    </style>
</head>
<body class="paper-background">
    <!-- Watermark for Print -->
    <div class="watermark">JEBIKALAM VAANGA</div>
    
    <!-- Print Actions (Screen Only) -->
    <div class="print-actions no-print">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Print Prophecy
        </button>
        <a href="{{ route('prophecies.show', ['id' => $prophecy->id, 'language' => $language]) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to View
        </a>
    </div>
    
    <!-- Book-Style Content -->
    <article class="book-page page-curl" style="max-width: 850px; margin: 0 auto; padding: 3.5rem 4rem 4rem 4rem;">
        
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
                {{ $prophecy->translations->where('language', $language)->first()?->title ?? $prophecy->title }}
            </h1>
            
            <!-- Subtitle ornament -->
            <div style="font-family: 'Merriweather', serif; font-size: 0.95rem; font-style: italic; margin-top: 0.5rem;" class="theme-accent-dark">
                Divine Revelation • {{ $prophecy->jebikalam_vanga_date->format('F d, Y') }}
            </div>
            
            <!-- Language Badge -->
            <div style="display: inline-block; margin-top: 1rem; background: var(--quote-bg); color: var(--accent-dark); padding: 6px 16px; border-radius: 20px; font-size: 0.875rem; font-weight: 600; border: 1px solid var(--accent-color);">
                @switch($language)
                    @case('ta') தமிழ் (Tamil) @break
                    @case('kn') ಕನ್ನಡ (Kannada) @break
                    @case('te') తెలుగు (Telugu) @break
                    @case('ml') മലയാളം (Malayalam) @break
                    @case('hi') हिंदी (Hindi) @break
                    @default English
                @endswitch
            </div>
        </header>
        
        <!-- Metadata Box -->
        <div class="theme-bg-accent" style="border-left: 4px solid var(--accent-color); border-radius: 6px; padding: 1.5rem; margin-bottom: 3rem; font-size: 0.9rem; line-height: 1.6;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div>
                    <strong class="theme-heading">Category:</strong>
                    <span class="theme-text">{{ $prophecy->category?->name ?? 'Uncategorized' }}</span>
                </div>
                <div>
                    <strong class="theme-heading">Published:</strong>
                    <span class="theme-text">{{ $prophecy->published_at ? $prophecy->published_at->format('d/m/Y') : 'Not published' }}</span>
                </div>
                <div>
                    <strong class="theme-heading">Print Date:</strong>
                    <span class="theme-text">{{ now()->format('d/m/Y H:i') }} IST</span>
                </div>
                <div>
                    <strong class="theme-heading">Print Count:</strong>
                    <span class="theme-text">{{ $prophecy->print_count + 1 }}</span>
                </div>
            </div>
        </div>
        
        <!-- Excerpt - Illuminated Quote -->
        @php
            $translation = $prophecy->translations->where('language', $language)->first();
            $excerpt = ($translation && $translation->excerpt) ? $translation->excerpt : $prophecy->excerpt;
        @endphp
        
        @if($excerpt)
        <div class="theme-bg-accent theme-border" style="border-left-width: 4px; border-right-width: 4px; padding: 2rem; margin-bottom: 3rem; border-radius: 4px; position: relative; box-shadow: inset 0 0 20px var(--quote-bg);">
            <!-- Opening quote mark -->
            <div style="position: absolute; top: -10px; left: 20px; font-size: 4rem; opacity: 0.3; font-family: Georgia, serif; line-height: 1;" class="theme-accent">"</div>
            
            <p style="margin: 0; font-family: 'Merriweather', serif; font-size: 1.25rem; font-weight: 400; line-height: 1.8; font-style: italic; text-align: center; padding: 0 1rem;" class="theme-heading">
                {{ $excerpt }}
            </p>
            
            <!-- Closing quote mark -->
            <div style="position: absolute; bottom: -10px; right: 20px; font-size: 4rem; opacity: 0.3; font-family: Georgia, serif; line-height: 1;" class="theme-accent">"</div>
        </div>
        @endif
        
        <!-- Main Content - Book Reading Style -->
        <div class="reading-content prophecy-content" style="margin-bottom: 3rem;" lang="{{ $language }}">
            @if($translation?->content)
                {!! $translation->content !!}
            @else
                {!! $prophecy->description !!}
            @endif
        </div>
        
        <!-- Prayer Points -->
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
        
        <!-- Footer Info -->
        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-color); text-align: center; font-size: 0.85rem; color: var(--text-color); opacity: 0.7;">
            <p style="margin: 0; font-style: italic;">
                Jebikalam Vaanga Prophecy Ministry • Generated on {{ now()->format('d/m/Y H:i:s') }} IST
            </p>
            <p style="margin: 0.5rem 0 0 0; font-size: 0.75rem;">
                This document contains spiritual content and should be handled with reverence and care.<br>
                For questions, please contact Voice of Jesus at vojmedia@gmail.com
            </p>
        </div>
        
    </article>
    
    <!-- Auto-print script -->
    <script>
        // Disable right-click for security
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
        
        // Disable certain keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F12' || 
                (e.ctrlKey && e.shiftKey && e.key === 'I') ||
                (e.ctrlKey && e.key === 'u') ||
                (e.ctrlKey && e.key === 's')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
