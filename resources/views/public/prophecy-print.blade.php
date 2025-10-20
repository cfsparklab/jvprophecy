<!DOCTYPE html>
<html lang="{{ $language }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $prophecy->translations->where('language', $language)->first()?->title ?? $prophecy->title }} - Jebikalam Vaanga Prophecy</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
/* Modern Clean Print Styles */
* {
                margin: 0; 
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
    line-height: 1.6;
    color: #334155;
    background: #f8fafc;
}

.container {
    max-width: 1100px;
                margin: 0 auto; 
    padding: 2.5rem 1.5rem;
}

/* Header Card */
.header-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 2.5rem;
    margin-bottom: 2rem;
}

/* Meta Info Bar */
.meta-info {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid #f1f5f9;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.meta-item i {
    font-size: 1.125rem;
}

.meta-item span {
    font-size: 0.9rem;
    font-weight: 600;
    color: #475569;
}

/* Title */
h1 {
    margin: 0 0 1.5rem 0;
    font-size: 2.5rem;
    font-weight: 800;
    line-height: 1.2;
    color: #1e293b;
    letter-spacing: -0.02em;
}

/* Language Badge */
.language-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: #3b82f6;
    color: white;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
}

/* Content Card */
.content-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 3rem;
}

/* Featured Image */
.featured-image {
    margin-bottom: 2rem;
}

.featured-image img {
    width: 100%;
    height: auto;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}

/* Excerpt */
.excerpt-box {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    border-left: 4px solid #3b82f6;
    padding: 1.75rem 2rem;
    margin-bottom: 2.5rem;
    border-radius: 12px;
    display: flex;
    gap: 1rem;
}

.excerpt-box i {
    font-size: 1.75rem;
    color: #3b82f6;
    opacity: 0.7;
    flex-shrink: 0;
}

.excerpt-box p {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 500;
    line-height: 1.75;
    color: #1e40af;
    font-style: italic;
}

/* Section Headers */
.section-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f1f5f9;
}

.section-header h2 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-header h2 i {
    font-size: 1.25rem;
    color: #8b5cf6;
}

/* Content */
.prophecy-content {
    font-size: 1.0625rem;
    line-height: 1.8;
    color: #334155;
}

.prophecy-content p {
    margin-bottom: 1.25rem;
}

.prophecy-content h1,
.prophecy-content h2,
.prophecy-content h3,
.prophecy-content h4 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 700;
    color: #1e293b;
}

.prophecy-content h2 { font-size: 1.5rem; }
.prophecy-content h3 { font-size: 1.25rem; }
.prophecy-content h4 { font-size: 1.125rem; }

.prophecy-content ul,
.prophecy-content ol {
    margin-bottom: 1.25rem;
    padding-left: 2rem;
}

.prophecy-content li {
    margin-bottom: 0.625rem;
}

.prophecy-content strong {
    font-weight: 700;
    color: #1e293b;
}

/* Prayer Points */
.prayer-section {
    margin-top: 3rem;
    padding-top: 3rem;
    border-top: 3px solid #e2e8f0;
}

.prayer-header {
    margin-bottom: 2rem;
}

.prayer-title-wrapper {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.75rem;
}

.prayer-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);
}

.prayer-icon i {
    color: white;
    font-size: 1.25rem;
}

.prayer-title h2 {
    margin: 0;
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
}

.prayer-subtitle {
    margin: 0.25rem 0 0 0;
    font-size: 0.9375rem;
    color: #64748b;
}

.prayer-content {
    background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%);
    border: 2px solid #a78bfa;
    border-radius: 12px;
    padding: 2.5rem;
}

.prayer-content p {
    margin-bottom: 1rem;
}

.prayer-content ul,
.prayer-content ol {
    margin-bottom: 1rem;
    padding-left: 1.75rem;
}

.prayer-content li {
    margin-bottom: 0.75rem;
    line-height: 1.75;
}

/* Print Actions (Screen Only) */
        .print-actions {
            text-align: center;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .btn {
            display: inline-block;
    padding: 0.875rem 1.5rem;
    margin: 0 0.5rem;
    border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
    font-size: 0.9375rem;
            transition: all 0.2s;
    border: none;
    cursor: pointer;
        }
        
        .btn-primary {
            background: #3b82f6;
            color: white;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
        }
        
        .btn-primary:hover {
            background: #2563eb;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .btn-secondary {
            background: #6b7280;
            color: white;
    box-shadow: 0 2px 8px rgba(107, 114, 128, 0.2);
        }
        
        .btn-secondary:hover {
            background: #4b5563;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
}

.btn i {
    margin-right: 0.5rem;
}

/* Print Styles */
@media print {
    body {
        background: white;
        padding: 0;
    }
    
    .container {
        max-width: 100%;
        padding: 1rem;
    }
    
    .no-print {
        display: none !important;
    }
    
    .header-card,
    .content-card {
        box-shadow: none;
        border: 1px solid #e2e8f0;
        page-break-inside: avoid;
    }
    
    h1 {
        page-break-after: avoid;
    }
    
    .prayer-section {
        page-break-before: auto;
    }
}

@media screen and (max-width: 768px) {
    .container {
        padding: 1rem;
    }
    
    .header-card,
    .content-card {
        padding: 1.5rem;
    }
    
    h1 {
        font-size: 2rem;
    }
    
    .prophecy-content {
        font-size: 1rem;
    }
    
    .meta-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Print Actions (Screen Only) -->
        <div class="print-actions no-print">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i>Print Prophecy
            </button>
            <a href="{{ route('prophecies.show', ['id' => $prophecy->id, 'language' => $language]) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>Back to View
            </a>
        </div>
        
        <!-- Header Card -->
        <div class="header-card">
            <!-- Meta Info Bar -->
            <div class="meta-info">
                <div class="meta-item">
                    <i class="fas fa-calendar-alt" style="color: #3b82f6;"></i>
                    <span>{{ $prophecy->jebikalam_vanga_date->format('F d, Y') }}</span>
                </div>
                
                @if($prophecy->category)
                <div class="meta-item">
                    <i class="fas fa-tag" style="color: #8b5cf6;"></i>
                    <span>{{ $prophecy->category->name }}</span>
                </div>
                @endif
                
                @if($prophecy->week_number)
                <div class="meta-item">
                    <i class="fas fa-calendar-week" style="color: #f59e0b;"></i>
                    <span>Week {{ $prophecy->week_number }}</span>
                </div>
                @endif
            </div>
            
            <!-- Title -->
            <h1>{{ $prophecy->translations->where('language', $language)->first()?->title ?? $prophecy->title }}</h1>
            
            <!-- Language Badge -->
            <div class="language-badge">
                @switch($language)
                    @case('ta') தமிழ் (Tamil) @break
                    @case('kn') ಕನ್ನಡ (Kannada) @break
                    @case('te') తెలుగు (Telugu) @break
                    @case('ml') മലയാളം (Malayalam) @break
                    @case('hi') हिंदी (Hindi) @break
                    @default English
                @endswitch
            </div>
        </div>
        
        <!-- Content Card -->
        <div class="content-card">
            <!-- Featured Image -->
            @if($prophecy->featured_image)
            <div class="featured-image">
                <img src="{{ asset('storage/' . $prophecy->featured_image) }}" alt="{{ $prophecy->title }}">
            </div>
            @endif
            
            <!-- Excerpt -->
            @php
                $translation = $prophecy->translations->where('language', $language)->first();
                $excerpt = $translation?->excerpt ?? $prophecy->excerpt;
            @endphp
            
            @if($excerpt)
            <div class="excerpt-box">
                <i class="fas fa-quote-left"></i>
                <p>{{ $excerpt }}</p>
            </div>
            @endif
            
            <!-- Content Section Header -->
            <div class="section-header">
                <h2>
                    <i class="fas fa-scroll"></i>
                    <span>Prophecy Message</span>
                </h2>
            </div>
            
            <!-- Main Content -->
            <div class="prophecy-content">
                @php
                    if ($translation && !empty($translation->content)) {
                        $content = $translation->content;
                    } elseif ($translation && !empty($translation->description)) {
                        $content = $translation->description;
                    } elseif ($prophecy->description) {
                        $content = $prophecy->description;
                    } else {
                        $content = '<p><em>Content not available in this language.</em></p>';
                    }
                @endphp
                
                {!! $content !!}
            </div>
            
            <!-- Prayer Points -->
            @php
                $prayerPoints = $translation?->prayer_points ?? $prophecy->prayer_points;
            @endphp
            
            @if($prayerPoints)
            <div class="prayer-section">
                <div class="prayer-header">
                    <div class="prayer-title-wrapper">
                        <div class="prayer-icon">
                            <i class="fas fa-praying-hands"></i>
                        </div>
                        <div class="prayer-title">
                            <h2>Prayer Points</h2>
                            <p class="prayer-subtitle">Pray these declarations over your life</p>
                        </div>
                    </div>
        </div>
        
                <div class="prayer-content">
                {!! $prayerPoints !!}
            </div>
        </div>
        @endif
        </div>
    </div>
</body>
</html>
