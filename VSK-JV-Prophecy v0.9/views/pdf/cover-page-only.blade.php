<!DOCTYPE html>
<html lang="{{ $language }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $translation && $translation->title ? $translation->title : $prophecy->title }} - Cover Page</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Times New Roman', serif;
            font-size: 14px;
            line-height: 1.6;
            color: #2c3e50;
        }
        
        /* Professional Cover Page */
        .cover-page {
            width: 100%;
            height: 100vh;
            position: relative;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 3px solid #1e40af;
        }
        
        /* Header Section */
        .cover-header {
            text-align: center;
            padding: 60px 40px 40px 40px;
            border-bottom: 2px solid #e2e8f0;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
        }
        
        .ministry-name {
            font-size: 1.8rem;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .ministry-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            font-style: italic;
        }
        
        /* Main Content */
        .cover-main {
            padding: 60px 40px;
            text-align: center;
            flex-grow: 1;
        }
        
        .prophecy-title {
            font-size: 2.2rem;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 30px;
            line-height: 1.3;
            text-align: center;
            padding: 0 20px;
        }
        
        .prophecy-subtitle {
            font-size: 1.1rem;
            color: #64748b;
            margin-bottom: 50px;
            font-style: italic;
        }
        
        /* Simplified Professional Details Section */
        .details-section {
            max-width: 550px;
            margin: 0 auto;
            background: #ffffff;
            border: 3px solid #1e40af;
            page-break-inside: avoid;
            break-inside: avoid;
        }
        
        .details-header {
            background: #1e40af;
            color: white;
            padding: 15px 20px;
            text-align: center;
            font-weight: bold;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .details-body {
            padding: 0;
        }
        
        .detail-row {
            display: table;
            width: 100%;
            border-bottom: 1px solid #d1d5db;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-row:nth-child(even) {
            background: #f8fafc;
        }
        
        .detail-label {
            display: table-cell;
            background: #e5e7eb;
            padding: 14px 20px;
            font-weight: bold;
            color: #374151;
            width: 40%;
            border-right: 1px solid #d1d5db;
            font-size: 0.9rem;
            text-transform: uppercase;
            vertical-align: middle;
        }
        
        .detail-value {
            display: table-cell;
            padding: 14px 20px;
            color: #111827;
            width: 60%;
            font-weight: 500;
            font-size: 1rem;
            vertical-align: middle;
        }
        
        /* Security Watermarks */
        .security-left {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%) rotate(-90deg);
            transform-origin: center;
            opacity: 0.3;
            font-size: 10px;
            color: #64748b;
            font-family: 'Courier New', monospace;
            line-height: 1.4;
        }
        
        .security-right {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%) rotate(90deg);
            transform-origin: center;
            opacity: 0.15;
            font-size: 8px;
            color: #94a3b8;
            font-family: 'Courier New', monospace;
            line-height: 1.2;
            letter-spacing: 1px;
        }
        
        /* Bottom Section */
        .cover-bottom {
            position: absolute;
            bottom: 30px;
            left: 0;
            right: 0;
            text-align: center;
            color: #64748b;
            font-size: 0.9rem;
        }
        
        .copyright {
            margin-bottom: 10px;
        }
        
        .generation-info {
            font-size: 0.8rem;
            opacity: 0.7;
        }
    </style>
</head>
<body class="{{ $language }}">
    
    <!-- Professional Cover Page -->
    <div class="cover-page">
        
        <!-- Header Section -->
        <div class="cover-header">
            <div class="ministry-name">Jebikalam Vaanga Prophecy</div>
            <div class="ministry-subtitle">Divine Revelations & Prophetic Ministry</div>
        </div>
        
        <!-- Main Content -->
        <div class="cover-main">
            <h1 class="prophecy-title">
                {{ $translation && $translation->title ? $translation->title : $prophecy->title }}
            </h1>
            
            <p class="prophecy-subtitle">
                Prophetic Word & Divine Revelation
            </p>
            
            <!-- Professional Details Section -->
            <div class="details-section">
                <div class="details-header">
                    Prophecy Information
                </div>
                <div class="details-body">
                    <div class="detail-row">
                        <div class="detail-label">Prophecy Date</div>
                        <div class="detail-value">{{ $prophecy->jebikalam_vanga_date->format('d F Y') }}</div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Category</div>
                        <div class="detail-value">{{ $prophecy->category ? $prophecy->category->name : 'General Prophecies' }}</div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Language</div>
                        <div class="detail-value">
                            @switch($language)
                                @case('ta') Tamil (தமிழ்) @break
                                @case('kn') Kannada (ಕನ್ನಡ) @break
                                @case('te') Telugu (తెలుగు) @break
                                @case('ml') Malayalam (മലയാളം) @break
                                @case('hi') Hindi (हिंदी) @break
                                @default English
                            @endswitch
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Document ID</div>
                        <div class="detail-value">
                            <strong style="font-family: 'Courier New', monospace; background: #f3f4f6; padding: 2px 6px;">
                                JVP-{{ $prophecy->id }}-{{ strtoupper($language) }}-{{ now()->format('Ymd') }}
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Security Watermarks -->
        <div class="security-left">
            Downloaded: {{ now()->format('d/m/Y H:i:s T') }}<br>
            @if(Auth::check())
            User: {{ Auth::user()->email }}
            @else
            User: Guest
            @endif
        </div>
        
        <div class="security-right">
            @php
                // Generate digital fingerprint for leak tracing
                $fingerprint = hash('sha256', 
                    $prophecy->id . 
                    ($translation && isset($translation->id) ? $translation->id : 'main') . 
                    $language . 
                    (Auth::check() ? Auth::user()->id : 'guest') . 
                    now()->format('YmdHis')
                );
                $chunks = str_split(strtoupper($fingerprint), 8);
            @endphp
            @foreach(array_slice($chunks, 0, 8) as $chunk)
                {{ $chunk }}<br>
            @endforeach
        </div>
        
        <!-- Bottom Section -->
        <div class="cover-bottom">
            <div class="generation-info">
                Generated on {{ now()->format('d F Y \a\t H:i:s T') }} | Confidential Document
            </div>
        </div>
        
    </div>
    
</body>
</html>
