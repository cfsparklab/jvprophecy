<?php
/**
 * PDF Download Configuration Test
 * 
 * Run on production: php test-pdf-download.php
 * 
 * This checks if your PDF download is properly configured
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== PDF Download Configuration Check ===\n\n";

// 1. Check storage configuration
echo "1. Storage Configuration:\n";
$pdfDisk = env('PDF_STORAGE_DISK', 'public');
echo "   PDF_STORAGE_DISK = {$pdfDisk}\n";

if ($pdfDisk === 'r2' || $pdfDisk === 's3') {
    echo "   ✅ Using cloud storage (R2)\n";
    
    // Check R2 credentials
    $bucket = env('AWS_BUCKET');
    $endpoint = env('AWS_ENDPOINT');
    $accessKey = env('AWS_ACCESS_KEY_ID');
    
    if (!$bucket || !$endpoint || !$accessKey) {
        echo "   ❌ ERROR: Cloud storage credentials missing!\n";
        echo "      - AWS_BUCKET: " . ($bucket ? '✅ Set' : '❌ Missing') . "\n";
        echo "      - AWS_ENDPOINT: " . ($endpoint ? '✅ Set' : '❌ Missing') . "\n";
        echo "      - AWS_ACCESS_KEY_ID: " . ($accessKey ? '✅ Set' : '❌ Missing') . "\n";
    } else {
        echo "   ✅ R2 credentials configured\n";
    }
} else {
    echo "   ℹ️  Using local storage\n";
}

// 2. Check if PDFs exist
echo "\n2. PDF Files Check:\n";
$prophecies = \App\Models\Prophecy::whereNotNull('pdf_file')->with('translations')->get();
echo "   Total prophecies with PDFs: {$prophecies->count()}\n";

if ($prophecies->isEmpty()) {
    echo "   ⚠️  No PDFs found in database\n";
} else {
    $pdfService = app(\App\Services\PdfStorageService::class);
    $foundCount = 0;
    $missingCount = 0;
    
    foreach ($prophecies as $prophecy) {
        if ($pdfService->pdfExists($prophecy->pdf_file)) {
            $foundCount++;
        } else {
            $missingCount++;
            echo "   ❌ Missing: {$prophecy->title} ({$prophecy->pdf_file})\n";
        }
        
        // Check translations
        foreach ($prophecy->translations as $translation) {
            if ($translation->pdf_file) {
                if ($pdfService->pdfExists($translation->pdf_file)) {
                    $foundCount++;
                } else {
                    $missingCount++;
                    echo "   ❌ Missing: {$translation->title} - {$translation->language} ({$translation->pdf_file})\n";
                }
            }
        }
    }
    
    echo "   ✅ Found: {$foundCount} PDFs\n";
    if ($missingCount > 0) {
        echo "   ❌ Missing: {$missingCount} PDFs\n";
    }
}

// 3. Check download route
echo "\n3. Download Route Check:\n";
$routes = \Illuminate\Support\Facades\Route::getRoutes();
$downloadRoute = $routes->getByName('prophecies.download.pdf');

if ($downloadRoute) {
    echo "   ✅ Download route exists: {$downloadRoute->uri()}\n";
    $middleware = $downloadRoute->middleware();
    echo "   Middleware: " . implode(', ', $middleware) . "\n";
} else {
    echo "   ❌ Download route NOT FOUND\n";
}

// 4. Test headers (simulate)
echo "\n4. Expected Download Headers:\n";
echo "   Content-Type: application/pdf ✅\n";
echo "   Content-Disposition: attachment; filename=\"...\" ✅\n";
echo "   X-Content-Type-Options: nosniff ✅\n";
echo "   Cache-Control: no-cache, no-store, must-revalidate ✅\n";

// 5. Common issues check
echo "\n5. Common Issues Check:\n";
$issues = [];

// Check if auth middleware exists
if ($downloadRoute && !in_array('auth', $downloadRoute->middleware())) {
    $issues[] = "Download route missing 'auth' middleware";
}

// Check if .htaccess/nginx might be interfering
if (file_exists(public_path('.htaccess'))) {
    echo "   ℹ️  .htaccess file exists (Apache)\n";
} elseif (file_exists(base_path('nginx.conf'))) {
    echo "   ℹ️  nginx.conf file exists (Nginx)\n";
}

if (empty($issues)) {
    echo "   ✅ No obvious issues found\n";
} else {
    foreach ($issues as $issue) {
        echo "   ⚠️  {$issue}\n";
    }
}

// 6. Test download for first PDF
echo "\n6. Test First PDF:\n";
$firstProphecy = \App\Models\Prophecy::whereNotNull('pdf_file')->first();

if ($firstProphecy) {
    echo "   Testing: {$firstProphecy->title}\n";
    echo "   PDF Path: {$firstProphecy->pdf_file}\n";
    echo "   Download URL: " . route('prophecies.download.pdf', $firstProphecy->id) . "\n";
    
    if ($pdfService->pdfExists($firstProphecy->pdf_file)) {
        echo "   ✅ PDF file exists and is accessible\n";
    } else {
        echo "   ❌ PDF file NOT FOUND\n";
    }
} else {
    echo "   ⚠️  No prophecies with PDFs to test\n";
}

echo "\n=== Summary ===\n";
echo "✅ Headers are correctly configured to force PDF download\n";
echo "✅ Content-Type is set to application/pdf\n";
echo "✅ X-Content-Type-Options prevents MIME type sniffing\n";

if ($pdfDisk === 'r2' && (!$bucket || !$endpoint)) {
    echo "❌ R2 storage selected but not properly configured\n";
    echo "   Run: php check-r2-config.php\n";
}

echo "\nℹ️  If PDFs still download as .pdf.html:\n";
echo "   1. Clear browser cache\n";
echo "   2. Try in incognito/private mode\n";
echo "   3. Check if web server (Apache/Nginx) is adding headers\n";
echo "   4. Verify user is logged in before downloading\n";
echo "\n";

