<!DOCTYPE html>
<html lang="{{ $language }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $translation?->title ?? $prophecy->title }} - Jebikalam Vaanga Prophecy</title>
    
    <style>
        @page {
            margin: 2cm;
        }
        
        body {
            font-family: 'Arial Unicode MS', 'DejaVu Sans', Arial, sans-serif;
            font-size: 16px; /* Increased by 2px from 14px */
            line-height: 1.8; /* Better readability */
            color: #333;
            margin: 0;
            padding: 0;
            position: relative;
            font-weight: normal; /* Don't make everything bold */
        }
        
        /* Indian languages unified styling - all use Arial Unicode MS for consistency */
        .prophecy-content[lang="ta"],
        .prophecy-content[lang="te"],
        .prophecy-content[lang="kn"],
        .prophecy-content[lang="ml"],
        .prophecy-content[lang="hi"],
        body[lang="ta"],
        body[lang="te"],
        body[lang="kn"],
        body[lang="ml"],
        body[lang="hi"] {
            font-family: 'Arial Unicode MS', 'DejaVu Sans', Arial, sans-serif !important;
            font-size: 18px;
            line-height: 2.0;
            font-weight: normal;
        }
        
        /* Professional font sizing for content elements */
        .prophecy-content {
            font-size: 16px; /* Increased by 2px from 14px */
            line-height: 1.8;
        }
        
        .prophecy-content p {
            font-size: 16px; /* Increased by 2px from 14px */
            line-height: 1.8;
            margin-bottom: 12px;
        }
        
        .prophecy-content span {
            font-size: inherit; /* Inherit from parent */
        }
        
        /* Headings and important text */
        .prophecy-content span[style*="font-weight"] {
            font-size: 18px; /* Increased by 2px from 16px */
        }
        
        /* Preserve ALL inline formatting with highest priority */
        [style] {
            /* All inline styles take absolute precedence */
        }
        
        span[style*="color"] {
            /* Preserve color styles with high priority */
        }
        
        span[style*="font-weight"] {
            /* Preserve font-weight styles with high priority */
        }
        
        span[style*="font-style"] {
            /* Preserve font-style styles with high priority */
        }
        
        span[style*="text-decoration"] {
            /* Preserve text-decoration styles with high priority */
        }
        
        /* Ensure all styled content is preserved */
        .prophecy-content [style],
        .prophecy-content span[style],
        .prophecy-content p [style] {
            /* Allow ALL inline styles to take absolute precedence */
        }
        
        /* Don't override content formatting */
        .prophecy-content {
            font-weight: inherit; /* Don't force bold */
        }
        

        
        .header {
            text-align: center;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .logo {
            font-size: 18px; /* Increased by 2px from 16px */
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        
        .subtitle {
            color: #6b7280;
            font-size: 16px; /* Increased by 2px from 14px */
            margin-bottom: 10px;
        }
        
        .language-indicator {
            display: inline-block;
            background: #dbeafe;
            color: #1e40af;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 14px; /* Increased by 2px from 12px */
            font-weight: bold;
            margin-top: 5px;
        }
        
        .prophecy-title {
            font-size: 18px; /* Increased by 2px from 16px */
            font-weight: bold;
            color: #1f2937;
            text-align: center;
            margin: 30px 0;
            line-height: 1.3;
            page-break-inside: avoid;
            page-break-after: avoid;
        }
        
        /* Unified font support for all Indian languages */
        .lang-ta, .lang-kn, .lang-te, .lang-ml, .lang-hi {
            font-family: 'Arial Unicode MS', 'DejaVu Sans', Arial, sans-serif !important;
            font-size: 18px;
            line-height: 2.0;
            font-weight: normal;
        }
        
        .prophecy-meta {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #3b82f6;
            page-break-inside: avoid;
        }
        
        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .meta-table td {
            padding: 5px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px; /* Increased from 11px */
        }
        
        .meta-table td:first-child {
            font-weight: bold;
            color: #374151;
            width: 30%;
        }
        
        .meta-table td:last-child {
            color: #6b7280;
        }
        
        .security-notice {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 12px;
            margin: 20px 0;
            font-size: 12px; /* Increased from 10px */
            text-align: center;
            page-break-inside: avoid;
        }
        
        .prophecy-content {
            line-height: 1.8;
            font-size: 13px;
            color: #1f2937;
            margin: 30px 0;
            text-align: justify;
        }
        
        .prophecy-content h1,
        .prophecy-content h2,
        .prophecy-content h3 {
            color: #1f2937;
            margin-top: 25px;
            margin-bottom: 12px;
            page-break-after: avoid;
        }
        
        .prophecy-content h1 {
            font-size: 18px;
        }
        
        .prophecy-content h2 {
            font-size: 16px;
        }
        
        .prophecy-content h3 {
            font-size: 14px;
        }
        
        .prophecy-content p {
            margin-bottom: 12px;
            text-indent: 20px;
        }
        
        .prophecy-content ul,
        .prophecy-content ol {
            margin: 12px 0;
            padding-left: 25px;
        }
        
        .prophecy-content li {
            margin-bottom: 6px;
        }
        
        .prayer-points {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            padding: 15px;
            margin: 25px 0;
            page-break-inside: avoid;
        }
        
        .prayer-points h3 {
            color: #92400e;
            margin-top: 0;
            margin-bottom: 12px;
            font-size: 16px;
        }
        
        .prayer-points ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .prayer-points li {
            color: #78350f;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px; /* Increased from 10px */
            color: #6b7280;
            page-break-inside: avoid;
        }
        
        .footer-simple {
            text-align: center;
            font-size: 12px; /* Increased from 10px */
            color: #666;
            font-weight: normal;
        }
        
        .download-info {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            color: #0c4a6e;
            padding: 10px;
            margin: 15px 0;
            font-size: 12px; /* Increased from 10px */
            page-break-inside: avoid;
            opacity: 0.3;
        }

        .page-break {
            page-break-before: always;
        }
        
        /* Prevent orphans and widows */
        p, li {
            orphans: 2;
            widows: 2;
        }
        
        h1, h2, h3 {
            orphans: 3;
            widows: 3;
        }
    </style>
</head>
<body lang="{{ $language }}">

    
    <!-- Language Indicator -->
    <div class="language-indicator">
        @switch($language)
            @case('ta') Tamil @break
            @case('kn') Kannada @break
            @case('te') Telugu @break
            @case('ml') Malayalam @break
            @case('hi') Hindi @break
            @default English
        @endswitch
    </div>
    

    

    <!-- Prophecy Title -->
    <h1 class="prophecy-title lang-{{ $language }}">
        @if(isset($title_image))
            <!-- Display title as image for Indian languages -->
            <div style="text-align: center; margin: 20px 0;">
                <img src="{{ $title_image['base64'] ?? $title_image['path'] }}" alt="Prophecy Title" style="max-width: 100%; height: auto; display: block; margin: 0 auto;">
            </div>
        @elseif($language === 'ta' && $translation?->title)
            <div class="tamil-text" style="padding: 10px; background: #f1f5f9; border-radius: 8px; margin-bottom: 10px; font-size: 18px;">
                {{ $translation->title }}
            </div>
        @else
            {{ $translation?->title ?? $prophecy->title }}
        @endif
    </h1>
    
    <!-- Prophecy Metadata -->
    <div class="prophecy-meta">
        <table class="meta-table">
            <tr>
                <td>Jebikalam Vaanga Date:</td>
                <td>{{ $prophecy->jebikalam_vanga_date ? \Carbon\Carbon::parse($prophecy->jebikalam_vanga_date)->format('d/m/Y') : 'Not specified' }}</td>
            </tr>
            <tr>
                <td>Category:</td>
                <td>{{ $prophecy->category?->name ?? 'Uncategorized' }}</td>
            </tr>
            <tr>
                <td>Language:</td>
                <td>
                    @switch($language)
                        @case('ta') Tamil @break
                        @case('kn') Kannada @break
                        @case('te') Telugu @break
                        @case('ml') Malayalam @break
                        @case('hi') Hindi @break
                        @default English
                    @endswitch
                </td>
            </tr>
            <tr>
                <td>Published:</td>
                <td>{{ $prophecy->published_at ? $prophecy->published_at->format('d/m/Y') : 'Not published' }}</td>
            </tr>
        </table>
    </div>
    
    <!-- Prophecy Content -->
    <div class="prophecy-content lang-{{ $language }}">

        @if(isset($content_image))
            <!-- Indian Language Content as Image -->
            <div style="background: #f0f9ff; border: 1px solid #0ea5e9; padding: 15px; margin-bottom: 20px; border-radius: 8px;">
                <p style="margin: 0; font-weight: bold; color: #0369a1; font-size: 14px;">
                    📝 {{ $language_fallback_message ?? 'Content rendered as image for proper display.' }}
                </p>
            </div>
            
            <!-- Content Image -->
            <div style="text-align: center; margin: 20px 0;">
                <img src="{{ $content_image['base64'] ?? $content_image['path'] }}" alt="Prophecy Content" style="max-width: 100%; height: auto; display: block; margin: 0 auto; border: 1px solid #e5e7eb; border-radius: 8px;">
            </div>
            
        @elseif(in_array($language, ['ta', 'te', 'kn', 'ml', 'hi']) && $translation?->content)
            <!-- Fallback for Indian languages without images -->
            <div style="background: #fef3c7; border: 1px solid #f59e0b; padding: 15px; margin-bottom: 20px; border-radius: 8px;">
                <p style="margin: 0; font-weight: bold; color: #92400e; font-size: 14px;">
                    📝 Indian Language Content Notice: This prophecy contains {{ ucfirst($language) }} text. If characters appear as boxes below, 
                    please view the online version for proper script display.
                </p>
            </div>
            
            <!-- Indian Language Content (may show as boxes) -->
            <div style="padding: 20px; background: #f8fafc; border-radius: 8px;">
                {!! $translation->content !!}
            </div>
        @else
            <!-- English or other languages -->
            @if($translation?->content)
                {!! $translation->content !!}
            @else
                {!! $prophecy->description !!}
            @endif
        @endif
    </div>
    
    <!-- Prayer Points -->
    @php
        $prayerPoints = null;
        if ($translation && isset($translation->metadata['prayer_points'])) {
            $prayerPoints = $translation->metadata['prayer_points'];
        } elseif ($prophecy->prayer_points) {
            $prayerPoints = $prophecy->prayer_points;
        }
        
        if (is_string($prayerPoints)) {
            $prayerPoints = json_decode($prayerPoints, true) ?: explode("\n", $prayerPoints);
        }
    @endphp
    
    @if($prayerPoints && (is_array($prayerPoints) ? count($prayerPoints) > 0 : !empty($prayerPoints)))
    <div class="prayer-points lang-{{ $language }}">
        <h3>🙏 Prayer Points</h3>
        @if(is_array($prayerPoints))
            <ul>
                @foreach($prayerPoints as $point)
                    @if(is_string($point) && trim($point))
                        <li>{{ trim($point) }}</li>
                    @endif
                @endforeach
            </ul>
        @else
            <p>{{ $prayerPoints }}</p>
        @endif
    </div>
    @endif
    
    <!-- Footer -->
    <div class="footer">
        <!-- Download Information in Footer -->
        <div class="download-info">
            <strong>Download Information:</strong> This PDF was generated on {{ $generated_at->format('d/m/Y H:i:s') }} IST
            @if($user)
            for {{ $user->name }} ({{ $user->email }})
            @endif
            . This document is digitally protected and tracked for security purposes.
        </div>
        
        <div style="margin-top: 15px; text-align: center; font-size: 6px; color: #f3f4f6; opacity: 0.1;">
            This document contains spiritual content and should be handled with reverence and care.<br>
            For questions or concerns, please contact Voice of Jesus at vojmedia@gmail.com.<br>
            © {{ date('Y') }} Jebikalam Vaanga Prophecy. All rights reserved.
        </div>
    </div>
</body>
</html>
