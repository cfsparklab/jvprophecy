<!DOCTYPE html>
<html lang="{{ $language ?? 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $prophecy->title ?? 'Prophecy' }} - Jebikalam Vaanga Prophecy</title>
    
    <!-- Google Fonts for better Unicode support -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;600;700&family=Noto+Sans+Tamil:wght@400;600;700&family=Noto+Sans+Devanagari:wght@400;600;700&family=Noto+Sans+Kannada:wght@400;600;700&family=Noto+Sans+Telugu:wght@400;600;700&family=Noto+Sans+Malayalam:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Noto Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #1a1a1a;
            background: white;
            padding: 20px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Language-specific fonts */
        .lang-ta, .tamil-text {
            font-family: 'Noto Sans Tamil', 'Noto Sans', sans-serif;
            font-size: 15px;
            line-height: 1.8;
        }

        .lang-hi, .hindi-text {
            font-family: 'Noto Sans Devanagari', 'Noto Sans', sans-serif;
            font-size: 15px;
            line-height: 1.8;
        }

        .lang-kn, .kannada-text {
            font-family: 'Noto Sans Kannada', 'Noto Sans', sans-serif;
            font-size: 15px;
            line-height: 1.8;
        }

        .lang-te, .telugu-text {
            font-family: 'Noto Sans Telugu', 'Noto Sans', sans-serif;
            font-size: 15px;
            line-height: 1.8;
        }

        .lang-ml, .malayalam-text {
            font-family: 'Noto Sans Malayalam', 'Noto Sans', sans-serif;
            font-size: 15px;
            line-height: 1.8;
        }

        /* Container */
        .pdf-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            position: relative;
        }

        /* Header */
        .pdf-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #0284c7;
        }

        .pdf-logo {
            font-size: 24px;
            font-weight: 700;
            color: #0284c7;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .pdf-subtitle {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Title */
        .prophecy-title {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            text-align: center;
            margin-bottom: 25px;
            padding: 15px;
            background: #f8fafc;
            border-left: 4px solid #0284c7;
        }

        /* Metadata table */
        .metadata-table {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
            font-size: 12px;
        }

        .metadata-table th,
        .metadata-table td {
            padding: 8px 12px;
            text-align: left;
            border: 1px solid #e2e8f0;
        }

        .metadata-table th {
            background: #f1f5f9;
            font-weight: 600;
            color: #475569;
            width: 30%;
        }

        .metadata-table td {
            background: white;
            color: #1e293b;
        }

        /* Content sections */
        .content-section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e2e8f0;
        }

        .section-content {
            font-size: 14px;
            line-height: 1.7;
            color: #374151;
            text-align: justify;
        }

        /* Prayer points */
        .prayer-points {
            background: #fefce8;
            border: 1px solid #fde047;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .prayer-points .section-title {
            color: #a16207;
            border-bottom-color: #fde047;
        }

        .prayer-points .section-content {
            color: #713f12;
        }

        /* Footer */
        .pdf-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            font-size: 11px;
            color: #64748b;
            text-align: center;
        }

        .footer-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .footer-left,
        .footer-right {
            flex: 1;
        }

        .footer-right {
            text-align: right;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 48px;
            font-weight: 700;
            color: rgba(2, 132, 199, 0.1);
            z-index: -1;
            pointer-events: none;
            white-space: nowrap;
        }

        /* Print optimizations */
        @media print {
            body {
                padding: 0;
                font-size: 12px;
            }
            
            .pdf-container {
                max-width: none;
                margin: 0;
            }
            
            .watermark {
                position: absolute;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            body {
                padding: 10px;
                font-size: 13px;
            }
            
            .prophecy-title {
                font-size: 18px;
            }
            
            .pdf-logo {
                font-size: 20px;
            }
            
            .footer-info {
                flex-direction: column;
                text-align: center;
            }
            
            .footer-right {
                text-align: center;
            }
        }
    </style>
</head>
<body class="lang-{{ $language }}">
        <!-- Watermark -->
        <div class="watermark">JEBIKALAM VAANGA PROPHECY</div>

    <div class="pdf-container">
        <!-- Header -->
        <div class="pdf-header">
            <div class="pdf-logo">JEBIKALAM VAANGA PROPHECY</div>
        </div>

        <!-- Title -->
        <div class="prophecy-title {{ $language !== 'en' ? 'lang-' . $language : '' }}">
            {{ $translation->title ?? $prophecy->title }}
        </div>

        <!-- Metadata Table -->
        <table class="metadata-table">
            <tr>
                <th>Prophecy ID</th>
                <td>#{{ str_pad($prophecy->id, 4, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <th>Category</th>
                <td>{{ $prophecy->category->name ?? 'General' }}</td>
            </tr>
            <tr>
                <th>Date Uttered</th>
                <td>{{ $prophecy->date_uttered ? \Carbon\Carbon::parse($prophecy->date_uttered)->format('d/m/Y') : 'Not specified' }}</td>
            </tr>
            <tr>
                <th>Location</th>
                <td>{{ $prophecy->location ?? 'Not specified' }}</td>
            </tr>
            <tr>
                <th>Language</th>
                <td>
                    @switch($language)
                        @case('ta') Tamil @break
                        @case('hi') Hindi @break
                        @case('kn') Kannada @break
                        @case('te') Telugu @break
                        @case('ml') Malayalam @break
                        @default English
                    @endswitch
                </td>
            </tr>
            <tr>
                <th>Generated On</th>
                <td>{{ now()->setTimezone('Asia/Kolkata')->format('d/m/Y H:i:s') }} IST</td>
            </tr>
            @if($user)
            <tr>
                <th>Generated For</th>
                <td>{{ $user->name }} ({{ $user->email }})</td>
            </tr>
            @endif
        </table>

        <!-- Summary/Description -->
        @if($translation->summary ?? $prophecy->description)
        <div class="content-section">
            <div class="section-title">Summary</div>
            <div class="section-content {{ $language !== 'en' ? 'lang-' . $language : '' }}">
                {!! nl2br(e($translation->summary ?? $prophecy->description)) !!}
            </div>
        </div>
        @endif

        <!-- Main Content -->
        @if($translation->content ?? $prophecy->content)
        <div class="content-section">
            <div class="section-title">Prophecy Content</div>
            <div class="section-content {{ $language !== 'en' ? 'lang-' . $language : '' }}">
                {!! nl2br(e($translation->content ?? $prophecy->content)) !!}
            </div>
        </div>
        @endif

        <!-- Prayer Points -->
        @if($translation->prayer_points ?? $prophecy->prayer_points)
        <div class="prayer-points">
            <div class="section-title">Prayer Points</div>
            <div class="section-content {{ $language !== 'en' ? 'lang-' . $language : '' }}">
                {!! nl2br(e($translation->prayer_points ?? $prophecy->prayer_points)) !!}
            </div>
        </div>
        @endif

        <!-- Additional Information -->
        @if($prophecy->tags || $prophecy->media_type || $prophecy->unlock_condition)
        <div class="content-section">
            <div class="section-title">Additional Information</div>
            <table class="metadata-table">
                @if($prophecy->tags)
                <tr>
                    <th>Tags</th>
                    <td>{{ $prophecy->tags }}</td>
                </tr>
                @endif
                @if($prophecy->media_type)
                <tr>
                    <th>Media Type</th>
                    <td>{{ ucfirst($prophecy->media_type) }}</td>
                </tr>
                @endif
                @if($prophecy->unlock_condition)
                <tr>
                    <th>Unlock Condition</th>
                    <td>{{ $prophecy->unlock_condition }}</td>
                </tr>
                @endif
            </table>
        </div>
        @endif

        <!-- Footer -->
        <div class="pdf-footer">
            <div class="footer-info">
                <div class="footer-left">
                    <strong>Jebikalam Vaanga Prophecy</strong><br>
                    Document ID: {{ $download_id ?? uniqid('pdf_', true) }}<br>
                    Security Level: PROTECTED
                </div>
                <div class="footer-right">
                    Generated: {{ now()->setTimezone('Asia/Kolkata')->format('d/m/Y H:i:s') }} IST<br>
                    Page 1 of 1<br>
                    © {{ date('Y') }} Jebikalam Vaanga Prophecy
                </div>
            </div>
        </div>
    </div>

    <script>
        // Ensure fonts are loaded before PDF generation
        document.fonts.ready.then(() => {
            console.log('All fonts loaded successfully');
            // Add a small delay to ensure complete rendering
            setTimeout(() => {
                document.body.classList.add('fonts-loaded');
            }, 500);
        });

        // Add print-ready class after page load
        window.addEventListener('load', () => {
            setTimeout(() => {
                document.body.classList.add('print-ready');
            }, 1000);
        });
    </script>
</body>
</html>
