<?php
/**
 * OpCache and Cache Clearing Utility
 * 
 * IMPORTANT: Delete this file after use for security!
 * 
 * Usage: Visit https://yoursite.com/clear-opcache.php
 */

// Basic authentication (change these!)
$AUTH_USER = 'admin';
$AUTH_PASS = 'changeme123';  // CHANGE THIS PASSWORD!

// Simple authentication
$authenticated = false;
if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
    if ($_SERVER['PHP_AUTH_USER'] === $AUTH_USER && $_SERVER['PHP_AUTH_PW'] === $AUTH_PASS) {
        $authenticated = true;
    }
}

if (!$authenticated) {
    header('WWW-Authenticate: Basic realm="Cache Clear Utility"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Authentication required';
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Cache Clear Utility</title>
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
        .success { color: #10b981; }
        .error { color: #ef4444; }
        .warning { color: #f59e0b; }
        .info { color: #3b82f6; }
        h1 { margin-top: 0; }
        .status { font-size: 1.5em; margin: 10px 0; }
        .delete-warning {
            background: #fee2e2;
            border: 2px solid #ef4444;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>🔧 Cache Clear Utility</h1>
        
        <h2>OpCache Status</h2>
        <?php if (function_exists('opcache_reset')): ?>
            <?php 
            $status = opcache_get_status();
            $config = opcache_get_configuration();
            ?>
            <div class="status success">✅ OpCache is ENABLED</div>
            <?php if (isset($_GET['clear'])): ?>
                <?php if (opcache_reset()): ?>
                    <div class="status success">✅ OpCache CLEARED successfully!</div>
                <?php else: ?>
                    <div class="status error">❌ Failed to clear OpCache</div>
                <?php endif; ?>
            <?php else: ?>
                <p>OpCache is enabled but not cleared yet.</p>
                <p><strong>Memory Usage:</strong> <?php echo round($status['memory_usage']['used_memory'] / 1024 / 1024, 2); ?> MB</p>
                <p><strong>Cached Scripts:</strong> <?php echo $status['opcache_statistics']['num_cached_scripts']; ?></p>
                <p><a href="?clear=1" style="display: inline-block; background: #3b82f6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px;">Clear OpCache Now</a></p>
            <?php endif; ?>
        <?php else: ?>
            <div class="status warning">⚠️ OpCache is NOT enabled</div>
            <p>OpCache is not available on this server.</p>
        <?php endif; ?>
        
        <h2>APC Cache Status</h2>
        <?php if (function_exists('apc_clear_cache')): ?>
            <div class="status success">✅ APC Cache is enabled</div>
            <?php if (isset($_GET['clear'])): ?>
                <?php if (apc_clear_cache()): ?>
                    <div class="status success">✅ APC Cache CLEARED successfully!</div>
                <?php else: ?>
                    <div class="status error">❌ Failed to clear APC cache</div>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            <div class="status info">ℹ️ APC Cache not available</div>
        <?php endif; ?>
        
        <h2>APCu Cache Status</h2>
        <?php if (function_exists('apcu_clear_cache')): ?>
            <div class="status success">✅ APCu Cache is enabled</div>
            <?php if (isset($_GET['clear'])): ?>
                <?php if (apcu_clear_cache()): ?>
                    <div class="status success">✅ APCu Cache CLEARED successfully!</div>
                <?php else: ?>
                    <div class="status error">❌ Failed to clear APCu cache</div>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            <div class="status info">ℹ️ APCu Cache not available</div>
        <?php endif; ?>
        
        <h2>System Information</h2>
        <p><strong>Server Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
        <p><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></p>
        <p><strong>Server Software:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></p>
        
        <h2>Controller File Check</h2>
        <?php
        $controllerPath = '../app/Http/Controllers/PublicController.php';
        if (file_exists($controllerPath)) {
            $content = file_get_contents($controllerPath);
            $lastModified = date('Y-m-d H:i:s', filemtime($controllerPath));
            
            if (strpos($content, 'X-Content-Type-Options') !== false) {
                echo '<div class="status success">✅ PDF FIX IS DEPLOYED!</div>';
                echo '<p>PublicController.php contains the updated PDF headers.</p>';
            } else {
                echo '<div class="status error">❌ PDF FIX NOT DEPLOYED!</div>';
                echo '<p>Please upload the updated PublicController.php file.</p>';
            }
            echo '<p><strong>Last Modified:</strong> ' . $lastModified . '</p>';
        } else {
            echo '<div class="status error">❌ Controller file not found!</div>';
        }
        ?>
    </div>
    
    <div class="card delete-warning">
        <h2>⚠️ IMPORTANT SECURITY WARNING</h2>
        <p><strong>DELETE THIS FILE IMMEDIATELY AFTER USE!</strong></p>
        <p>This file should not be accessible in production. Remove it by running:</p>
        <code>rm public/clear-opcache.php</code>
        <p style="margin-top: 10px;">Or delete it via FTP/cPanel File Manager.</p>
    </div>
    
    <?php if (isset($_GET['clear'])): ?>
    <div class="card">
        <h2>Next Steps</h2>
        <ol>
            <li>Close this window</li>
            <li>Clear your browser cache (<code>Ctrl + Shift + Delete</code>)</li>
            <li>Try downloading a PDF in an Incognito window</li>
            <li><strong>DELETE THIS FILE!</strong></li>
        </ol>
    </div>
    <?php endif; ?>
</body>
</html>
```
