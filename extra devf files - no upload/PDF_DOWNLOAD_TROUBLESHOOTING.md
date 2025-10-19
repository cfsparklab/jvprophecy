# PDF Download Troubleshooting Guide

## Issue: PDFs Still Downloading as .pdf.html

If PDFs are still downloading with `.html` extension after the fix, follow these steps:

---

## ✅ Step 1: Verify Files Are Uploaded to Production

Make sure you've uploaded the updated file to your production server:
- **File:** `app/Http/Controllers/PublicController.php`
- **Location:** Upload to exact same path on production server

### How to Verify:
1. Check file modification date on server
2. Open the file on server and verify lines 288-299 contain the new headers

---

## ✅ Step 2: Clear All Caches

### A. Server-Side Cache (CRITICAL!)

Run these commands on your production server via SSH or cPanel Terminal:

```bash
cd /path/to/your/project

# Clear application cache
php artisan cache:clear

# Clear config cache
php artisan config:clear

# Clear route cache  
php artisan route:clear

# Clear view cache
php artisan view:clear

# Restart PHP-FPM (if available)
sudo systemctl restart php-fpm
# OR
sudo service php7.4-fpm restart   # adjust version number
```

### B. OpCache Clear (Very Important!)

If your server uses OpCache, you MUST reset it. Add this file to your production server:

**Create:** `public/clear-opcache.php`

```php
<?php
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "✅ OpCache cleared successfully!<br>";
} else {
    echo "⚠️ OpCache not enabled<br>";
}

if (function_exists('apc_clear_cache')) {
    apc_clear_cache();
    echo "✅ APC cache cleared!<br>";
}

echo "<br>Server Time: " . date('Y-m-d H:i:s');
echo "<br>PHP Version: " . PHP_VERSION;
phpinfo(INFO_MODULES);
?>
```

**Then visit:** `https://yoursite.com/clear-opcache.php`

**IMPORTANT:** Delete this file after clearing cache for security!

---

## ✅ Step 3: Clear Browser Cache

### Chrome/Edge:
1. Press `Ctrl + Shift + Delete` (Windows) or `Cmd + Shift + Delete` (Mac)
2. Select "All time" 
3. Check "Cached images and files"
4. Click "Clear data"
5. **Hard Refresh:** `Ctrl + F5` on the download page

### Firefox:
1. Press `Ctrl + Shift + Delete`
2. Time range: "Everything"
3. Check "Cache"
4. Click "Clear Now"
5. **Hard Refresh:** `Ctrl + Shift + R`

### Safari:
1. Go to Safari > Preferences > Advanced
2. Enable "Show Develop menu in menu bar"
3. Develop > Empty Caches
4. **Hard Refresh:** `Cmd + Option + R`

---

## ✅ Step 4: Test in Incognito/Private Mode

This bypasses all browser cache:

1. **Chrome:** `Ctrl + Shift + N`
2. **Firefox:** `Ctrl + Shift + P`
3. **Safari:** `Cmd + Shift + N`
4. Visit your site and try downloading PDF

---

## ✅ Step 5: Check .htaccess File (Production Server)

Your production server might have a different `.htaccess` file. Add this to the TOP of `public/.htaccess`:

```apache
<IfModule mod_headers.c>
    # Force correct MIME type for PDFs
    <FilesMatch "\.(pdf)$">
        Header set Content-Type "application/pdf"
        Header set Content-Disposition "attachment"
    </FilesMatch>
</IfModule>

# Existing content below...
```

---

## ✅ Step 6: Check Server PHP Configuration

Some servers override Content-Type headers. Add this to `public/.htaccess`:

```apache
# Prevent Apache from overriding Content-Type
<IfModule mod_mime.c>
    AddType application/pdf .pdf
</IfModule>

# Disable output compression for PDFs
<IfModule mod_deflate.c>
    SetEnvIfNoCase Request_URI \.pdf$ no-gzip
</IfModule>
```

---

## ✅ Step 7: Test with Direct URL

Visit the download URL directly in your browser:

```
https://yoursite.com/prophecies/1/download-pdf?language=en
```

**Right-click the link** instead of clicking it, and choose "Save Link As..." 

Check if the file is still named `.pdf.html`

---

## ✅ Step 8: Check Response Headers (Developer Tools)

1. Open browser Developer Tools (`F12`)
2. Go to **Network** tab
3. Click the PDF download button
4. Find the download request
5. Check **Response Headers**, should show:

```
Content-Type: application/pdf
Content-Disposition: attachment; filename="prophecy_xxxxx.pdf"
Content-Length: [size]
Content-Transfer-Encoding: binary
```

**If you see `text/html` instead of `application/pdf`:**
- The file wasn't uploaded correctly
- Cache wasn't cleared
- Server is overriding headers

---

## ✅ Step 9: Production Server Specific Fixes

### For cPanel Hosting:

1. Go to cPanel
2. **Select PHP Version** (or similar)
3. Look for PHP extensions
4. Make sure these are enabled:
   - `zip`
   - `gd`
   - `mbstring`
   - `dom`

### For Apache:

Restart Apache:
```bash
sudo systemctl restart apache2
# OR
sudo service apache2 restart
```

### For Nginx:

Check your Nginx config doesn't override headers:
```nginx
location ~ \.pdf$ {
    add_header Content-Type application/pdf;
    add_header Content-Disposition attachment;
}
```

Then restart:
```bash
sudo systemctl restart nginx
```

---

## ✅ Step 10: Alternative Download Method

If all else fails, use this alternate download approach:

**Add to `routes/web.php`:**

```php
Route::get('/prophecies/{id}/force-download-pdf', function($id) {
    $language = request('language', 'en');
    $controller = new App\Http\Controllers\PublicController();
    
    // Get the PDF response
    $response = $controller->downloadUploadedProphecyPdf(request(), $id);
    
    // Force binary download
    return response()->download(
        $response->getFile(),
        $response->headers->get('content-disposition'),
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment'
        ]
    );
})->middleware('auth')->name('prophecies.force-download');
```

---

## 🔍 Quick Diagnostic

Run this on your server to check if the fix is deployed:

**Create:** `public/check-controller.php`

```php
<?php
$file = '../app/Http/Controllers/PublicController.php';
if (file_exists($file)) {
    $content = file_get_contents($file);
    
    if (strpos($content, 'X-Content-Type-Options') !== false) {
        echo "✅ FIX IS DEPLOYED!<br>";
        echo "File last modified: " . date('Y-m-d H:i:s', filemtime($file));
    } else {
        echo "❌ FIX NOT DEPLOYED YET!<br>";
        echo "Please upload the updated PublicController.php file";
    }
} else {
    echo "❌ Controller file not found!";
}
?>
```

Visit: `https://yoursite.com/check-controller.php`

**Delete this file after checking!**

---

## 🆘 Still Not Working?

If none of the above works, the issue might be:

1. **CDN Cache** - If you use CloudFlare/CDN, purge the cache there
2. **Proxy Server** - Your server might be behind a proxy that modifies headers
3. **Security Plugin** - Some security plugins modify download headers
4. **File Permissions** - Make sure the controller file is readable by PHP

### Contact Your Hosting Provider

Ask them to check:
1. Is PHP OpCache enabled? (needs clearing)
2. Are there any reverse proxies modifying HTTP headers?
3. Is there any security software intercepting downloads?
4. Are there any Apache/Nginx modules that might interfere?

---

## ✅ Success Test

After applying fixes:

1. Clear ALL caches (server + browser)
2. Open incognito window
3. Download a prophecy PDF
4. File should be named: `prophecy_[title]_[lang]_[date].pdf`
5. File should open correctly as PDF

---

**Questions? Need Help?**

Provide this information:
- Server type (Apache/Nginx)
- PHP version: `<?php echo PHP_VERSION; ?>`
- Hosting provider (cPanel/Plesk/VPS/Shared)
- Response headers from F12 Developer Tools

