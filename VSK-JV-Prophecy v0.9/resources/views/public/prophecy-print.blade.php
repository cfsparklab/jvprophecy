<!DOCTYPE html>
<html lang="{{ $language }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $prophecy->translations->where('language', $language)->first()?->title ?? $prophecy->title }} - Jebikalam Vaanga Prophecy</title>
    
    <!-- Print Styles -->
    <style>
        @media print {
            body { 
                margin: 0; 
                padding: 20px; 
                font-family: 'Noto Sans', 'DejaVu Sans', 'Arial Unicode MS', 'Times New Roman', serif;
                font-size: 16px;
                line-height: 1.8;
            }
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
            .watermark { 
                position: fixed; 
                top: 50%; 
                left: 50%; 
                transform: translate(-50%, -50%) rotate(-45deg);
                font-size: 72px; 
                color: rgba(0, 0, 0, 0.05); 
                z-index: -1; 
                font-weight: bold;
                pointer-events: none;
            }
        }
        
        @media screen {
            body { 
                font-family: 'Noto Sans', 'DejaVu Sans', 'Arial Unicode MS', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                max-width: 800px; 
                margin: 0 auto; 
                padding: 20px; 
                background: #f9fafb;
                font-size: 16px;
                line-height: 1.8;
            }
            .print-container { 
                background: white; 
                padding: 40px; 
                border-radius: 8px; 
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .logo {
            font-size: 18px; /* Increased by 2px from 16px */
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
        }
        
        .subtitle {
            color: #6b7280;
            font-size: 16px; /* Increased by 2px from 14px */
        }
        
        .prophecy-title {
            font-size: 18px; /* Increased by 2px from 16px */
            font-weight: bold;
            color: #1f2937;
            text-align: center;
            margin: 30px 0;
            line-height: 1.3;
        }
        
        .prophecy-meta {
            background: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #3b82f6;
        }
        
        .meta-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .meta-row:last-child {
            margin-bottom: 0;
        }
        
        .meta-label {
            font-weight: 600;
            color: #374151;
        }
        
        .meta-value {
            color: #6b7280;
        }
        
        .prophecy-content {
            line-height: 1.8;
            font-size: 16px; /* Increased by 2px from 14px */
            color: #1f2937;
            margin: 30px 0;
            font-weight: normal; /* Don't make everything bold */
        }
        
        .prophecy-content h1, .prophecy-content h2, .prophecy-content h3 {
            color: #1f2937;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        
        .prophecy-content p {
            margin-bottom: 15px;
        }
        
        .prophecy-content ul, .prophecy-content ol {
            margin: 15px 0;
            padding-left: 30px;
        }
        
        .prophecy-content li {
            margin-bottom: 8px;
        }
        
        /* Preserve ALL inline formatting with highest priority */
        .prophecy-content [style] {
            /* All inline styles take absolute precedence */
        }
        
        .prophecy-content span[style*="color"] {
            /* Preserve color styles with high priority */
        }
        
        .prophecy-content span[style*="font-weight"] {
            /* Preserve font-weight styles with high priority */
        }
        
        .prophecy-content span[style*="font-style"] {
            /* Preserve font-style styles with high priority */
        }
        
        .prophecy-content span[style*="text-decoration"] {
            /* Preserve text-decoration styles with high priority */
        }
        
        /* Ensure ALL styled content is preserved in both screen and print */
        .prophecy-content [style],
        .prophecy-content span[style],
        .prophecy-content p [style] {
            /* Allow ALL inline styles to take absolute precedence */
        }
        
        /* Bold text sizing as per specification */
        .prophecy-content span[style*="font-weight"],
        .prophecy-content b,
        .prophecy-content strong {
            font-size: 18px; /* Increased by 2px from 16px */
        }
        
        /* Reset any default formatting that might override inline styles */
        .prophecy-content p {
            font-weight: inherit; /* Don't override inline font-weight */
            color: inherit; /* Don't override inline colors */
        }
        
        .prayer-points {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .prayer-points h3 {
            color: #92400e;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .prayer-points ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .prayer-points li {
            color: #78350f;
            margin-bottom: 8px;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 16px; /* Increased by 2px from 14px */
            color: #6b7280;
        }
        
        /* Multi-language font support */
        .prophecy-content[lang="ta"],
        .prophecy-content .tamil-text {
            font-family: 'Noto Sans Tamil', 'Latha', 'Vijaya', 'DejaVu Sans', Arial, sans-serif;
            font-size: 18px;
            line-height: 2.0;
            letter-spacing: 0.5px;
        }
        
        .prophecy-content[lang="kn"] {
            font-family: 'Noto Sans Kannada', 'DejaVu Sans', Arial, sans-serif;
            font-size: 18px;
            line-height: 1.9;
        }
        
        .prophecy-content[lang="te"] {
            font-family: 'Noto Sans Telugu', 'DejaVu Sans', Arial, sans-serif;
            font-size: 18px;
            line-height: 1.9;
        }
        
        .prophecy-content[lang="ml"] {
            font-family: 'Noto Sans Malayalam', 'DejaVu Sans', Arial, sans-serif;
            font-size: 18px;
            line-height: 1.9;
        }
        
        .prophecy-content[lang="hi"] {
            font-family: 'Noto Sans Devanagari', 'DejaVu Sans', Arial, sans-serif;
            font-size: 18px;
            line-height: 1.8;
        }
        
        .security-notice {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            font-size: 16px; /* Increased by 2px from 14px */
            text-align: center;
        }
        
        .print-actions {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            background: #f3f4f6;
            border-radius: 8px;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background: #3b82f6;
            color: white;
        }
        
        .btn-primary:hover {
            background: #2563eb;
        }
        
        .btn-secondary {
            background: #6b7280;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #4b5563;
        }
        
        .language-indicator {
            display: inline-block;
            background: #dbeafe;
            color: #1e40af;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 16px; /* Increased by 2px from 14px */
            font-weight: 600;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">JEBIKALAM VAANGA PROPHECY</div>
    
    <div class="print-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                Jebikalam Vaanga Prophecy
            </div>
            <div class="language-indicator">
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
        
        <!-- Print Actions (Screen Only) -->
        <div class="print-actions no-print">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Print Prophecy
            </button>
            <a href="{{ route('prophecies.show', ['id' => $prophecy->id, 'language' => $language]) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to View
            </a>
        </div>
        
        <!-- Prophecy Title -->
        <h1 class="prophecy-title">
            {{ $prophecy->translations->where('language', $language)->first()?->title ?? $prophecy->title }}
        </h1>
        
        <!-- Prophecy Metadata -->
        <div class="prophecy-meta">
            <div class="meta-row">
                <span class="meta-label">Jebikalam Vaanga Date:</span>
                <span class="meta-value">
                    {{ $prophecy->jebikalam_vanga_date ? \Carbon\Carbon::parse($prophecy->jebikalam_vanga_date)->format('d/m/Y') : 'Not specified' }}
                </span>
            </div>
            
            <div class="meta-row">
                <span class="meta-label">Category:</span>
                <span class="meta-value">{{ $prophecy->category?->name ?? 'Uncategorized' }}</span>
            </div>
            
            <div class="meta-row">
                <span class="meta-label">Language:</span>
                <span class="meta-value">
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
            
            <div class="meta-row">
                <span class="meta-label">Published:</span>
                <span class="meta-value">
                    {{ $prophecy->published_at ? $prophecy->published_at->format('d/m/Y') : 'Not published' }}
                </span>
            </div>
            
            <div class="meta-row">
                <span class="meta-label">Print Date:</span>
                <span class="meta-value">{{ now()->format('d/m/Y H:i:s') }} IST</span>
            </div>
        </div>
        

        
        <!-- Prophecy Content -->
        <div class="prophecy-content" lang="{{ $language }}">
            @php
                $translation = $prophecy->translations->where('language', $language)->first();
            @endphp
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
        <div class="prayer-points">
            <h3><i class="fas fa-praying-hands"></i> Prayer Points</h3>
            <div class="prayer-points-content" lang="{{ $language }}">
                {!! $prayerPoints !!}
            </div>
        </div>
        @endif
        
        <!-- Footer -->
        <div class="footer">
            <p>
                <strong>Jebikalam Vaanga Prophecy</strong><br>
                Generated on {{ now()->format('d/m/Y H:i:s') }} IST | 
                Document Security Level: Protected | 
                Print Count: {{ $prophecy->print_count + 1 }}
            </p>
            
            <div style="margin-top: 15px; font-size: 10px;">
                <p>
                    This document contains spiritual content and should be handled with reverence and care. 
                    For questions or concerns, please contact Voice of Jesus at vojmedia@gmail.com.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Auto-print script -->
    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() { window.print(); }
        
        // Print function
        function printPage() {
            window.print();
        }
        
        // Disable right-click and keyboard shortcuts for security
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
        
        document.addEventListener('keydown', function(e) {
            // Disable F12, Ctrl+Shift+I, Ctrl+U, Ctrl+S
            if (e.key === 'F12' || 
                (e.ctrlKey && e.shiftKey && e.key === 'I') ||
                (e.ctrlKey && e.key === 'u') ||
                (e.ctrlKey && e.key === 's')) {
                e.preventDefault();
            }
        });
        
        // Add print timestamp
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Prophecy printed at: ' + new Date().toISOString());
        });
    </script>
</body>
</html>
