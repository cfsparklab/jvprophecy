<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="home-url" content="{{ route('home') }}">
    
    <title>@yield('title', 'Jebikalam Vaanga Prophecy')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Intel Corporate CSS - Complete Design System -->
    <link rel="stylesheet" href="{{ asset('css/intel-corporate-complete.css') }}">
    
    <!-- Backup: Local Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Custom Intel Corporate Styles -->
    <style>
        :root {
            --intel-blue-50: #eff6ff;
            --intel-blue-100: #dbeafe;
            --intel-blue-200: #bfdbfe;
            --intel-blue-300: #93c5fd;
            --intel-blue-400: #60a5fa;
            --intel-blue-500: #3b82f6;
            --intel-blue-600: #0284c7;
            --intel-blue-700: #075985;
            --intel-blue-800: #0c4a6e;
            --intel-blue-900: #0f172a;
            
            --intel-silver-50: #f8fafc;
            --intel-silver-100: #f1f5f9;
            --intel-silver-200: #e2e8f0;
            --intel-silver-300: #cbd5e1;
            --intel-silver-400: #94a3b8;
            --intel-silver-500: #64748b;
            --intel-silver-600: #475569;
            --intel-silver-700: #334155;
            --intel-silver-800: #1e293b;
            --intel-silver-900: #0f172a;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--intel-silver-50) 0%, var(--intel-blue-50) 100%);
            min-height: 100vh;
        }
        
        .intel-gradient {
            background: linear-gradient(135deg, var(--intel-blue-600) 0%, var(--intel-blue-700) 100%);
        }
        
        .intel-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .intel-btn-primary {
            background: linear-gradient(135deg, var(--intel-blue-600) 0%, var(--intel-blue-700) 100%);
            color: white;
            transition: all 0.3s ease;
        }
        
        .intel-btn-primary:hover {
            background: linear-gradient(135deg, var(--intel-blue-700) 0%, var(--intel-blue-800) 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
        }
        
        .intel-sidebar {
            background: linear-gradient(180deg, var(--intel-silver-800) 0%, var(--intel-silver-900) 100%);
        }
        
        .intel-nav-link {
            color: var(--intel-silver-300);
            transition: all 0.3s ease;
        }
        
        .intel-nav-link:hover {
            color: var(--intel-blue-400);
            background: rgba(59, 130, 246, 0.1);
        }
        
        .intel-nav-link.active {
            color: var(--intel-blue-400);
            background: rgba(59, 130, 246, 0.2);
            border-right: 3px solid var(--intel-blue-500);
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 6rem;
            color: rgba(2, 132, 199, 0.05);
            z-index: -1;
            pointer-events: none;
            user-select: none;
        }
        
        .security-mark {
            position: absolute;
            bottom: 10px;
            right: 10px;
            font-size: 0.7rem;
            color: rgba(2, 132, 199, 0.3);
            user-select: none;
        }
        
        /* Date input styling */
        input[type="date"] {
            position: relative;
        }
        
        input[type="date"]::-webkit-calendar-picker-indicator {
            background: transparent;
            bottom: 0;
            color: transparent;
            cursor: pointer;
            height: auto;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            width: auto;
        }
        
        /* HTML Editor Styling */
        #editor {
            outline: none;
        }
        
        #editor:empty:before {
            content: attr(placeholder);
            color: #9CA3AF;
            font-style: italic;
        }
        
        #editor p {
            margin: 0.5em 0;
        }
        
        #editor ul, #editor ol {
            margin: 0.5em 0;
            padding-left: 1.5em;
        }
        
        #editor strong {
            font-weight: bold;
        }
        
        #editor em {
            font-style: italic;
        }
        
        #editor u {
            text-decoration: underline;
        }
        
        /* User-Friendly Enhancements */
        .tooltip {
            position: relative;
            display: inline-block;
        }
        
        .tooltip .tooltiptext {
            visibility: hidden;
            width: 200px;
            background-color: #1f2937;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px 12px;
            position: absolute;
            z-index: 1000;
            bottom: 125%;
            left: 50%;
            margin-left: -100px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 0.875rem;
            line-height: 1.4;
        }
        
        .tooltip .tooltiptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #1f2937 transparent transparent transparent;
        }
        
        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }
        
        /* Simplified Button Styles */
        .btn-simple {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 1rem;
            transition: all 0.2s ease-in-out;
            text-decoration: none;
            border: none;
            cursor: pointer;
            min-height: 48px;
        }
        
        .btn-primary-simple {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .btn-primary-simple:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }
        
        .btn-secondary-simple {
            background: white;
            color: #374151;
            border: 2px solid #e5e7eb;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-secondary-simple:hover {
            background: #f9fafb;
            border-color: #d1d5db;
            transform: translateY(-1px);
        }
        
        /* Card Enhancements */
        .card-hover {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        /* Mobile Optimizations */
        @media (max-width: 768px) {
            .btn-simple {
                padding: 10px 20px;
                font-size: 0.9rem;
                min-height: 44px;
            }
            
            .tooltip .tooltiptext {
                width: 150px;
                margin-left: -75px;
                font-size: 0.8rem;
            }
        }
        
        /* Success/Error Animations */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Focus Enhancements */
        .focus-ring:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
            border-color: #3b82f6;
        }
        
        /* Help Text Styling */
        .help-text {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 4px;
            display: flex;
            align-items: center;
        }
        
        .help-text i {
            margin-right: 6px;
            color: #3b82f6;
        }
    </style>
    
    @stack('styles')
</head>
<body class="antialiased">
    <!-- Security Watermark -->
    <div class="watermark">
        JV PROPHECY MANAGER
    </div>
    
    <!-- Security Mark -->
    <div class="security-mark">
        Secured • {{ now()->format('d/m/Y H:i:s') }} IST • IP: {{ request()->ip() }}
    </div>
    
    <div id="app">
        @yield('content')
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    @stack('scripts')
    
    <!-- Global Security Script -->
    <script>
        // Disable right-click context menu
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
        
        // Disable text selection on sensitive areas
        document.addEventListener('selectstart', function(e) {
            if (e.target.classList.contains('no-select')) {
                e.preventDefault();
            }
        });
        
        // Disable F12, Ctrl+Shift+I, Ctrl+U
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F12' || 
                (e.ctrlKey && e.shiftKey && e.key === 'I') ||
                (e.ctrlKey && e.key === 'u')) {
                e.preventDefault();
            }
        });
        
        // Log user activity
        function logActivity(action, details = {}) {
            fetch('/api/log-activity', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    action: action,
                    details: details,
                    timestamp: new Date().toISOString(),
                    url: window.location.href
                })
            }).catch(console.error);
        }
        
        // Log page views
        logActivity('page_view', {
            page: window.location.pathname,
            user_agent: navigator.userAgent
        });
    </script>
    
    <!-- Session Expiry Handler -->
    <script src="{{ asset('js/session-handler.js') }}"></script>
</body>
</html>
