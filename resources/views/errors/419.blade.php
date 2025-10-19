<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired - Jebikalam Vaanga Prophecy</title>
    <style>
        :root {
            --intel-blue-600: #0284c7;
            --intel-blue-700: #0369a1;
            --intel-blue-800: #075985;
            --intel-silver-50: #f8fafc;
            --intel-silver-100: #f1f5f9;
            --intel-silver-900: #0f172a;
            --space-lg: 2rem;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--intel-blue-600) 0%, var(--intel-blue-800) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .error-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 500px;
            width: 90%;
        }

        .error-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }

        .error-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }

        .error-message {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .countdown {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 2rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .countdown-number {
            font-size: 2rem;
            font-weight: 700;
            color: #fbbf24;
            display: inline-block;
            min-width: 2rem;
        }

        .btn-home {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: var(--intel-silver-900);
            padding: 0.875rem 2rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(251, 191, 36, 0.3);
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(251, 191, 36, 0.4);
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .progress-bar {
            width: 100%;
            height: 4px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
            margin-top: 2rem;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #fbbf24, #f59e0b);
            border-radius: 2px;
            transition: width 1s linear;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .pulse {
            animation: pulse 1s infinite;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⏰</div>
        <h1 class="error-title">Session Expired</h1>
        <p class="error-message">
            Your session has expired for security reasons. You will be automatically redirected to the home page.
        </p>
        
        <div class="countdown">
            Redirecting in <span class="countdown-number pulse" id="countdown">5</span> seconds...
        </div>
        
        <a href="{{ route('home') }}" class="btn-home" id="homeBtn">
            🏠 Go to Home Now
        </a>
        
        <div class="progress-bar">
            <div class="progress-fill" id="progressBar" style="width: 100%;"></div>
        </div>
    </div>

    <script>
        let countdown = 5;
        const countdownElement = document.getElementById('countdown');
        const progressBar = document.getElementById('progressBar');
        const homeBtn = document.getElementById('homeBtn');
        
        // Update progress bar immediately
        progressBar.style.width = '100%';
        
        const timer = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            
            // Update progress bar
            const progress = (countdown / 5) * 100;
            progressBar.style.width = progress + '%';
            
            if (countdown <= 0) {
                clearInterval(timer);
                // Redirect to home
                window.location.href = "{{ route('home') }}";
            }
        }, 1000);
        
        // Allow manual redirect
        homeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            clearInterval(timer);
            window.location.href = "{{ route('home') }}";
        });
        
        // Handle browser back button
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.href = "{{ route('home') }}";
            }
        });
    </script>
</body>
</html>
