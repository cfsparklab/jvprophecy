<?php
/**
 * PDF Download Header Diagnostic Tool
 * DELETE AFTER USE!
 */

// Create a small test PDF
$pdfContent = "%PDF-1.4
1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj
2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj
3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 612 792]/Contents 4 0 R>>endobj
4 0 obj<</Length 44>>stream
BT /F1 12 Tf 100 700 Td (Test PDF) Tj ET
endstream endobj
xref
0 5
0000000000 65535 f 
0000000009 00000 n 
0000000058 00000 n 
0000000115 00000 n 
0000000234 00000 n 
trailer<</Size 5/Root 1 0 R>>
startxref
315
%%EOF";

$filename = 'test_download_' . date('YmdHis') . '.pdf';

// If not downloading, show info page
if (!isset($_GET['download'])) {
    ?>
<!DOCTYPE html>
<html>
<head>
    <title>PDF Header Test</title>
    <style>
        body { font-family: Arial; max-width: 900px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        .card { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .button { display: inline-block; background: #3b82f6; color: white; padding: 12px 24px; 
                 border-radius: 6px; text-decoration: none; margin: 10px 5px; }
        pre { background: #f3f4f6; padding: 15px; border-radius: 6px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="card">
        <h1>🔍 PDF Header Diagnostic</h1>
        <a href="?download=1" class="button">Test PDF Download</a>
        <a href="?check=1" class="button" style="background: #10b981;">Check Server</a>
        
        <?php if (isset($_GET['check'])): ?>
        <h2>Server Info</h2>
        <pre><?php
        echo "PHP: " . PHP_VERSION . "\n";
        echo "Server: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
        echo "Output Buffering: " . (ob_get_level() > 0 ? 'ENABLED' : 'DISABLED') . "\n";
        echo "Headers Sent: " . (headers_sent() ? 'YES (BAD!)' : 'NO (Good)') . "\n\n";
        
        $ctrl = '../app/Http/Controllers/PublicController.php';
        if (file_exists($ctrl)) {
            $content = file_get_contents($ctrl);
            echo "Controller Modified: " . date('Y-m-d H:i:s', filemtime($ctrl)) . "\n";
            echo "Has X-Content-Type-Options: " . (strpos($content, 'X-Content-Type-Options') !== false ? 'YES ✓' : 'NO ✗');
        }
        ?></pre>
        <?php endif; ?>
    </div>
    <div class="card" style="background: #fee2e2;">
        <strong>⚠️ DELETE THIS FILE AFTER USE!</strong>
        <pre>rm public/test-pdf-headers.php</pre>
    </div>
</body>
</html>
    <?php
    exit;
}

// Actual download test
if (ob_get_level()) ob_end_clean();

header('Content-Type: application/pdf', true);
header('Content-Disposition: attachment; filename="' . $filename . '"', true);
header('Content-Length: ' . strlen($pdfContent), true);
header('Content-Transfer-Encoding: binary', true);
header('Accept-Ranges: bytes', true);
header('Cache-Control: private, max-age=0, must-revalidate', true);
header('Pragma: public', true);
header('Expires: 0', true);
header('X-Content-Type-Options: nosniff', true);

echo $pdfContent;
exit;
?>
```
