<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication Required - JV Prophecy</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
            padding: 48px;
            text-align: center;
        }
        
        .icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: white;
        }
        
        h1 {
            font-size: 28px;
            color: #1a202c;
            margin-bottom: 16px;
            font-weight: 700;
        }
        
        p {
            font-size: 16px;
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 32px;
        }
        
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 14px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        
        .footer {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #e2e8f0;
            font-size: 14px;
            color: #718096;
        }
        
        .auto-redirect {
            margin-top: 16px;
            font-size: 14px;
            color: #718096;
        }
        
        .countdown {
            font-weight: 600;
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            🔒
        </div>
        
        <h1>Authentication Required</h1>
        
        <p>{{ $message ?? 'Your session has expired. Please login again to access this content.' }}</p>
        
        <a href="{{ $login_url }}" class="button">
            Login to Continue
        </a>
        
        <div class="auto-redirect">
            Redirecting to login in <span class="countdown" id="countdown">5</span> seconds...
        </div>
        
        <div class="footer">
            <strong>JV Prophecy Manager</strong><br>
            Secured Access • {{ date('d/m/Y H:i:s') }} IST
        </div>
    </div>
    
    <script>
        // Auto-redirect after 5 seconds
        let seconds = 5;
        const countdownEl = document.getElementById('countdown');
        const loginUrl = '{{ $login_url }}';
        const returnUrl = '{{ $return_url ?? '' }}';
        
        const interval = setInterval(() => {
            seconds--;
            countdownEl.textContent = seconds;
            
            if (seconds <= 0) {
                clearInterval(interval);
                // Redirect to login with return URL
                if (returnUrl) {
                    window.location.href = loginUrl + '?redirect=' + encodeURIComponent(returnUrl);
                } else {
                    window.location.href = loginUrl;
                }
            }
        }, 1000);
    </script>
</body>
</html>
```
