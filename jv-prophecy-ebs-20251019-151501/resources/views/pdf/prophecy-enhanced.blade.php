<!DOCTYPE html>
<html lang="{{ $language }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $translation && $translation->title ? $translation->title : $prophecy->title }} - Jebikalam Vaanga Prophecy</title>
    
    <style>
        @page {
            margin: 0;
            size: A4;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Arial Unicode MS', 'Noto Sans', Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
        }
        
        /* Cover Page Styles */
        .cover-page {
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            background-color: #f0f9ff !important;
            color: #1e40af !important;
            page-break-after: always;
            position: relative;
        }
        
        .cover-content {
            position: relative;
            z-index: 1;
            max-width: 600px;
            padding: 40px;
        }
        
        .cover-logo {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #1e40af !important;
            text-shadow: none;
        }
        
        .cover-title {
            font-size: 2rem;
            font-weight: 600;
            margin: 30px 0;
            line-height: 1.3;
            color: #1e40af !important;
            text-shadow: none;
        }
        
        .cover-subtitle {
            font-size: 1.1rem;
            margin: 20px 0;
            color: #1e40af !important;
            opacity: 0.8;
        }
        
        .cover-details {
            margin-top: 40px;
            padding: 20px;
            background: rgba(30,64,175,0.1);
            border-radius: 10px;
            border: 1px solid rgba(30,64,175,0.2);
        }
        
        .cover-detail-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            font-size: 0.95rem;
            color: #1e40af !important;
        }
        
        .cover-detail-label {
            font-weight: 600;
            color: #1e40af !important;
        }
        
        .cover-footer {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.9rem;
            color: #1e40af !important;
            opacity: 0.8;
        }
        
        /* Content Page Styles */
        .content-page {
            padding: 60px 50px 80px 50px;
            min-height: calc(100vh - 140px);
            position: relative;
        }
        
        .content-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #3b82f6;
        }
        
        .content-title {
            font-size: 1.8rem;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
        }
        
        .content-meta {
            font-size: 0.9rem;
            color: #666;
            margin: 5px 0;
        }
        
        .content-body {
            margin: 30px 0;
            text-align: justify;
        }
        
        .content-body h1, .content-body h2, .content-body h3 {
            color: #1e40af;
            margin-top: 25px;
            margin-bottom: 15px;
        }
        
        .content-body p {
            margin-bottom: 15px;
        }
        
        .content-body ul, .content-body ol {
            margin: 15px 0;
            padding-left: 25px;
        }
        
        .content-body li {
            margin-bottom: 8px;
        }
        
        .prayer-points {
            margin-top: 40px;
            padding: 25px;
            background: #f8fafc;
            border-left: 4px solid #3b82f6;
            border-radius: 0 8px 8px 0;
        }
        
        .prayer-points h2 {
            color: #1e40af;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
        }
        
        .prayer-points h2::before {
            content: "🙏";
            margin-right: 10px;
        }
        
        /* Footer Styles */
        .page-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: #1e40af;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 50px;
            font-size: 0.85rem;
            z-index: 1000;
        }
        
        .footer-left {
            display: flex;
            align-items: center;
        }
        
        .footer-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .footer-logo {
            font-weight: bold;
            margin-right: 15px;
        }
        
        /* Language-specific fonts */
        .tamil { font-family: 'Noto Sans Tamil', 'Latha', 'Tamil Sangam MN', serif; }
        .kannada { font-family: 'Noto Sans Kannada', 'Tunga', serif; }
        .telugu { font-family: 'Noto Sans Telugu', 'Gautami', serif; }
        .malayalam { font-family: 'Noto Sans Malayalam', 'Kartika', serif; }
        .hindi { font-family: 'Noto Sans Devanagari', 'Mangal', serif; }
        
        /* Print optimizations */
        @media print {
            .page-footer {
                position: fixed !important;
                bottom: 0 !important;
            }
        }
    </style>
</head>
<body class="{{ $language }}">
    
    <!-- Cover Page -->
    <div class="cover-page" style="background-color: #f0f9ff !important; color: #1e40af !important;">
        <div class="cover-content" style="color: #1e40af !important;">
            <div class="cover-logo" style="color: #1e40af !important;">
                ✨ Jebikalam Vaanga Prophecy ✨
            </div>
            
            <h1 class="cover-title" style="color: #1e40af !important;">
                {{ $translation && $translation->title ? $translation->title : $prophecy->title }}
            </h1>
            
            <p class="cover-subtitle" style="color: #1e40af !important;">
                Divine Revelation and Prophetic Word
            </p>
            
            <div class="cover-details" style="color: #1e40af !important;">
                <div class="cover-detail-row" style="color: #1e40af !important;">
                    <span class="cover-detail-label" style="color: #1e40af !important;">Jebikalam Vaanga Date:</span>
                    <span style="color: #1e40af !important;">{{ $prophecy->jebikalam_vanga_date->format('d/m/Y') }}</span>
                </div>
                
                <div class="cover-detail-row" style="color: #1e40af !important;">
                    <span class="cover-detail-label" style="color: #1e40af !important;">Category:</span>
                    <span style="color: #1e40af !important;">{{ $prophecy->category ? $prophecy->category->name : 'General Prophecies' }}</span>
                </div>
                
                <div class="cover-detail-row" style="color: #1e40af !important;">
                    <span class="cover-detail-label" style="color: #1e40af !important;">Language:</span>
                    <span style="color: #1e40af !important;">
                        @switch($language)
                            @case('ta') Tamil (தமிழ்) @break
                            @case('kn') Kannada (ಕನ್ನಡ) @break
                            @case('te') Telugu (తెలుగు) @break
                            @case('ml') Malayalam (മലയാളം) @break
                            @case('hi') Hindi (हिंदी) @break
                            @default English
                        @endswitch
                    </span>
                </div>
                
                <div class="cover-detail-row" style="color: #1e40af !important;">
                    <span class="cover-detail-label" style="color: #1e40af !important;">Published:</span>
                    <span style="color: #1e40af !important;">{{ $prophecy->published_at ? $prophecy->published_at->format('d/m/Y') : 'Draft' }}</span>
                </div>
                
                <div class="cover-detail-row" style="color: #1e40af !important;">
                    <span class="cover-detail-label" style="color: #1e40af !important;">Print Date:</span>
                    <span style="color: #1e40af !important;">{{ now()->format('d/m/Y H:i:s T') }}</span>
                </div>
            </div>
        </div>
        
        <div class="cover-footer" style="color: #1e40af !important;">
            © {{ now()->year }} Jebikalam Vaanga Prophecy Ministry - All Rights Reserved
        </div>
    </div>
    
    <!-- Content Page -->
    <div class="content-page">
        <div class="content-header">
            <h1 class="content-title">
                {{ $translation && $translation->title ? $translation->title : $prophecy->title }}
            </h1>
            <div class="content-meta">
                <strong>Jebikalam Vaanga Date:</strong> {{ $prophecy->jebikalam_vanga_date->format('l, F j, Y') }}
            </div>
            @if($prophecy->category)
            <div class="content-meta">
                <strong>Category:</strong> {{ $prophecy->category->name }}
            </div>
            @endif
        </div>
        
        <!-- Excerpt -->
        @if(($translation && $translation->excerpt) || $prophecy->excerpt)
        <div style="background: #f0f9ff; border-left: 4px solid #0ea5e9; padding: 20px; margin-bottom: 30px; border-radius: 0 8px 8px 0;">
            <p style="margin: 0; font-style: italic; font-size: 1.1rem; color: #0c4a6e;">
                {{ $translation && $translation->excerpt ? $translation->excerpt : $prophecy->excerpt }}
            </p>
        </div>
        @endif
        
        <!-- Main Content -->
        <div class="content-body">
            @if($translation && $translation->content)
                {!! $translation->content !!}
            @elseif($prophecy->description)
                {!! $prophecy->description !!}
            @else
                <p>Content not available in the requested language.</p>
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
        <div class="prayer-points">
            <h2>Prayer Points</h2>
            <div>
                {!! $prayerPoints !!}
            </div>
        </div>
        @endif
    </div>
    
    <!-- Footer -->
    <div class="page-footer">
        <div class="footer-left">
            <span class="footer-logo">Jebikalam Vaanga Prophecy</span>
            <span>{{ $prophecy->jebikalam_vanga_date->format('d/m/Y') }}</span>
        </div>
        <div class="footer-right">
            <span>Generated: {{ now()->format('d/m/Y H:i T') }}</span>
            <span>Page 1</span>
        </div>
    </div>
    
</body>
</html>
