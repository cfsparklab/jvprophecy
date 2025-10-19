<?php
/**
 * R2 Configuration Checker
 * 
 * Upload this file to your production server and run:
 * php check-r2-config.php
 * 
 * This will help diagnose R2 configuration issues
 */

echo "=== Cloudflare R2 Configuration Check ===\n\n";

// Check if .env file exists
$envPath = __DIR__ . '/.env';
echo "1. Checking .env file...\n";
if (file_exists($envPath)) {
    echo "   ✅ .env file exists\n";
    
    // Check if R2 variables are in .env
    $envContent = file_get_contents($envPath);
    $r2Vars = [
        'PDF_STORAGE_DISK',
        'AWS_BUCKET',
        'AWS_DEFAULT_REGION',
        'AWS_ENDPOINT',
        'AWS_URL',
        'AWS_ACCESS_KEY_ID',
        'AWS_SECRET_ACCESS_KEY',
        'AWS_USE_PATH_STYLE_ENDPOINT'
    ];
    
    echo "\n2. Checking R2 environment variables in .env...\n";
    foreach ($r2Vars as $var) {
        if (strpos($envContent, $var) !== false) {
            // Extract the value (basic extraction)
            if (preg_match("/^$var=(.*)$/m", $envContent, $matches)) {
                $value = trim($matches[1]);
                // Don't show full secret key
                if ($var === 'AWS_SECRET_ACCESS_KEY' && strlen($value) > 10) {
                    $value = substr($value, 0, 10) . '...(hidden)';
                }
                if ($var === 'AWS_ACCESS_KEY_ID' && strlen($value) > 10) {
                    $value = substr($value, 0, 10) . '...' . substr($value, -5);
                }
                echo "   ✅ $var = $value\n";
            } else {
                echo "   ⚠️  $var found but value unclear\n";
            }
        } else {
            echo "   ❌ $var NOT FOUND in .env\n";
        }
    }
} else {
    echo "   ❌ .env file NOT FOUND at: $envPath\n";
}

echo "\n3. Checking PHP environment variables...\n";
foreach ($r2Vars as $var) {
    $value = getenv($var);
    if ($value !== false && $value !== '') {
        // Don't show full secret key
        if ($var === 'AWS_SECRET_ACCESS_KEY' && strlen($value) > 10) {
            $value = substr($value, 0, 10) . '...(hidden)';
        }
        if ($var === 'AWS_ACCESS_KEY_ID' && strlen($value) > 10) {
            $value = substr($value, 0, 10) . '...' . substr($value, -5);
        }
        echo "   ✅ $var = $value\n";
    } else {
        echo "   ❌ $var = (not set)\n";
    }
}

echo "\n4. Checking Laravel config cache...\n";
$cachedConfig = __DIR__ . '/bootstrap/cache/config.php';
if (file_exists($cachedConfig)) {
    echo "   ⚠️  Config cache exists: $cachedConfig\n";
    echo "   📝 Run: php artisan config:clear\n";
    
    // Try to check what's in the cached config
    $config = include $cachedConfig;
    if (isset($config['filesystems']['disks']['r2'])) {
        echo "   ✅ R2 disk configured in cache\n";
        if (isset($config['filesystems']['disks']['r2']['bucket'])) {
            $bucket = $config['filesystems']['disks']['r2']['bucket'];
            echo "   📦 Bucket in cache: " . ($bucket ?: '(empty)') . "\n";
        }
    } else {
        echo "   ❌ R2 disk NOT in cached config\n";
    }
} else {
    echo "   ✅ No config cache (config will be read fresh)\n";
}

echo "\n5. Permission check...\n";
if (is_readable($envPath)) {
    echo "   ✅ .env is readable\n";
} else {
    echo "   ❌ .env is NOT readable (check permissions)\n";
}

echo "\n=== Recommended Actions ===\n";
echo "1. If config cache exists: php artisan config:clear\n";
echo "2. If .env has correct values: php artisan config:cache\n";
echo "3. Test R2 connection: php artisan tinker\n";
echo "   Then run: Storage::disk('r2')->put('test.txt', 'test');\n";
echo "\n";
echo "=== Quick Fix Commands ===\n";
echo "php artisan config:clear\n";
echo "php artisan cache:clear\n";
echo "php artisan config:cache\n";
echo "\n";

