<?php
/**
 * Route Checker - Verify what controller method is actually being called
 * 
 * DELETE AFTER USE!
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Route Checker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        pre {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 6px;
            overflow-x: auto;
        }
        .success { color: #10b981; }
        .error { color: #ef4444; }
    </style>
</head>
<body>
    <div class="card">
        <h1>📍 Route Information</h1>
        
        <h2>Download Routes Configuration</h2>
        <pre><?php
        $routesFile = '../routes/web.php';
        if (file_exists($routesFile)) {
            $content = file_get_contents($routesFile);
            
            // Extract download-related routes
            preg_match_all('/Route::get\(.*?download.*?\).*?;/s', $content, $matches);
            
            if (!empty($matches[0])) {
                foreach ($matches[0] as $route) {
                    echo $route . "\n\n";
                }
            } else {
                echo "No download routes found!";
            }
        } else {
            echo "Routes file not found!";
        }
        ?></pre>
        
        <h2>What Gets Called?</h2>
        <p>Based on the route <code>prophecies.download.pdf</code>:</p>
        <ul>
            <li><strong>Route:</strong> <code>/prophecies/{id}/download-pdf</code></li>
            <li><strong>Controller:</strong> <code>PublicController</code></li>
            <li><strong>Method:</strong> <code>downloadUploadedProphecyPdf()</code></li>
        </ul>
        
        <h2>Test Links (Replace ID with actual prophecy ID)</h2>
        <pre>https://yoursite.com/prophecies/1/download-pdf?language=en
https://yoursite.com/prophecies/1/download-pdf?language=ta
https://yoursite.com/prophecies/1/download-pdf?language=kn</pre>
        
        <h2>Recent Controller Modifications</h2>
        <pre><?php
        $controllerPath = '../app/Http/Controllers/PublicController.php';
        if (file_exists($controllerPath)) {
            echo "Last Modified: " . date('Y-m-d H:i:s', filemtime($controllerPath)) . "\n";
            echo "File Size: " . number_format(filesize($controllerPath)) . " bytes\n\n";
            
            // Check for the method
            $content = file_get_contents($controllerPath);
            
            // Extract the downloadUploadedProphecyPdf method
            if (preg_match('/public function downloadUploadedProphecyPdf.*?(?=public function|\}\s*$)/s', $content, $match)) {
                $method = $match[0];
                
                // Check for key headers
                $hasContentType = strpos($method, "->header('Content-Type', 'application/pdf')") !== false;
                $hasDisposition = strpos($method, "->header('Content-Disposition'") !== false;
                $hasNoSniff = strpos($method, "X-Content-Type-Options") !== false;
                
                echo "✓ Method exists: downloadUploadedProphecyPdf()\n";
                echo "✓ Has Content-Type header: " . ($hasContentType ? 'YES' : 'NO') . "\n";
                echo "✓ Has Content-Disposition header: " . ($hasDisposition ? 'YES' : 'NO') . "\n";
                echo "✓ Has X-Content-Type-Options: " . ($hasNoSniff ? 'YES' : 'NO') . "\n";
                
                if ($hasContentType && $hasDisposition && $hasNoSniff) {
                    echo "\n<span class='success'>✅ ALL HEADERS ARE IN PLACE!</span>\n";
                } else {
                    echo "\n<span class='error'>❌ SOME HEADERS ARE MISSING!</span>\n";
                }
            } else {
                echo "Method not found!";
            }
        }
        ?></pre>
    </div>
    
    <div class="card" style="background: #fee2e2; border: 2px solid #ef4444;">
        <h2>⚠️ DELETE THIS FILE!</h2>
        <pre>rm public/check-download-route.php</pre>
    </div>
</body>
</html>
```
