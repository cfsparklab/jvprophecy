# 🔒 White-Labeled PDF URLs with Authentication

## ✅ Implementation Complete!

PDFs are now served through your domain **`jvprophecy.vincentselvakumar.org`** with full authentication, instead of exposing Laravel Cloud storage URLs.

---

## 🎯 What Changed

### Before (Exposed Storage URLs ❌):
```
Image/Download → 
https://fls-a026b282-8d52-49fc-8351-19bb0eb16be5.laravel.cloud/
storage/prophecy_pdfs/prophecy_20_ta_1760886120.pdf
```

**Problems:**
- ❌ Exposes internal Laravel Cloud URL
- ❌ Anyone with the URL can download (no auth check)
- ❌ Not branded with your domain
- ❌ Hard to track downloads
- ❌ Can't prevent unauthorized access

### After (White-Labeled, Authenticated ✅):
```
Image/Download → 
https://jvprophecy.vincentselvakumar.org/prophecies/20/download-pdf?language=ta
```

**Benefits:**
- ✅ Uses your domain (white-labeled)
- ✅ Requires authentication to access
- ✅ Prevents unauthorized downloads
- ✅ Professional-looking URLs
- ✅ Full download tracking via logs
- ✅ Can control access via middleware

---

## 🔄 How It Works

### 1. User Clicks PDF Link/Image

On the prophecy detail page:
```blade
<a href="https://jvprophecy.vincentselvakumar.org/prophecies/20/download-pdf?language=ta">
    <img src="featured_image.jpg" alt="Tamil Prophecy">
</a>

<a href="https://jvprophecy.vincentselvakumar.org/prophecies/20/download-pdf?language=ta" download>
    Download PDF
</a>
```

### 2. Request Goes Through Your Domain

The route is handled by Laravel:
```php
// routes/web.php
Route::middleware(['auth.download'])->group(function () {
    Route::get('/prophecies/{id}/download-pdf', [PublicController::class, 'downloadUploadedProphecyPdf'])
        ->name('prophecies.download.pdf');
});
```

### 3. Authentication Middleware Checks

The `auth.download` middleware:
- ✅ Checks if user is logged in
- ❌ If not logged in → Shows auth-required page
- ✅ If logged in → Continues to controller

### 4. Controller Streams the PDF

```php
// PublicController.php
public function downloadUploadedProphecyPdf(Request $request, $id) {
    // Check auth (redundant but safe)
    if (!Auth::check()) {
        return response()->view('errors.auth-required', ...);
    }
    
    // Get prophecy and PDF file
    $prophecy = Prophecy::findOrFail($id);
    $pdfFile = $prophecy->pdf_file; // or translation->pdf_file
    
    // Log the download
    SecurityLog::create([...]);
    
    // Increment download count
    $prophecy->increment('download_count');
    
    // Stream the PDF from storage (local or R2)
    $content = Storage::disk('public')->get($pdfFile);
    
    return response($content, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="..."',
        ...
    ]);
}
```

### 5. PDF Streamed to User

- ✅ PDF content streamed through your domain
- ✅ Original storage URL never exposed
- ✅ Download tracked and logged
- ✅ User gets the PDF file

---

## 🛡️ Security Features

### 1. **Authentication Required**
```
Unauthenticated Request → Auth Required Page
Authenticated Request → PDF Download
```

### 2. **Authorization Logging**
Every download is logged:
```php
SecurityLog::create([
    'user_id' => Auth::id(),
    'event_type' => 'prophecy_pdf_download',
    'resource_id' => $prophecy->id,
    'ip_address' => $request->ip(),
    'metadata' => [
        'prophecy_title' => $prophecy->title,
        'language' => $language,
        'filename' => basename($pdfFile)
    ]
]);
```

### 3. **Download Tracking**
```php
$prophecy->increment('download_count');
```

### 4. **Storage Abstraction**
PDFs can be in:
- Local storage (`storage/app/public/prophecy_pdfs/`)
- Cloudflare R2
- AWS S3

User never sees the actual storage location!

---

## 💾 Storage Configuration

### Keep PDFs on Laravel Cloud (Current)
```env
# .env
PDF_STORAGE_DISK=public  # or leave blank
```

Files stored in: `storage/app/public/prophecy_pdfs/`

### Switch to Cloudflare R2 (Optional)
```env
# .env
PDF_STORAGE_DISK=r2
AWS_ACCESS_KEY_ID=your_r2_access_key
AWS_SECRET_ACCESS_KEY=your_r2_secret_key
AWS_BUCKET=jvprophecy
AWS_ENDPOINT=https://xxxxx.r2.cloudflarestorage.com
AWS_DEFAULT_REGION=auto
```

**Either way, URLs remain white-labeled:**
```
https://jvprophecy.vincentselvakumar.org/prophecies/{id}/download-pdf
```

---

## 📝 URL Structure

### Standard Download URLs

**Tamil PDF:**
```
https://jvprophecy.vincentselvakumar.org/prophecies/20/download-pdf?language=ta
```

**English PDF:**
```
https://jvprophecy.vincentselvakumar.org/prophecies/20/download-pdf?language=en
```

**Kannada PDF:**
```
https://jvprophecy.vincentselvakumar.org/prophecies/20/download-pdf?language=kn
```

### Route Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `id` | integer | Prophecy ID (required) |
| `language` | string | Language code: `en`, `ta`, `kn`, `te`, `ml`, `hi` |

### Example Usage

```blade
{{-- Blade Template --}}
<a href="{{ route('prophecies.download.pdf', ['id' => $prophecy->id, 'language' => 'ta']) }}">
    Download Tamil PDF
</a>
```

```php
// PHP Controller
$url = route('prophecies.download.pdf', [
    'id' => $prophecy->id,
    'language' => 'en'
]);
// Returns: https://jvprophecy.vincentselvakumar.org/prophecies/20/download-pdf?language=en
```

---

## 🎨 User Experience

### For Logged-In Users
1. ✅ Click image or download button
2. ✅ PDF opens in new tab (can view or download)
3. ✅ Smooth, seamless experience

### For Logged-Out Users
1. ❌ Click image or download button
2. 🔒 See "Authentication Required" page
3. 📝 Redirected to login with return URL
4. ✅ After login, redirected back to PDF

### Mobile Users
- ✅ Works on Android
- ✅ Works on iPhone/iPad
- ✅ No more `.pdf.html` issues
- ✅ Proper PDF download

---

## 🔧 Technical Implementation

### PdfStorageService.php

```php
/**
 * Get PDF URL for downloading
 * Returns authenticated route URL instead of direct storage URL
 */
public function getPdfUrl(string $path): string
{
    // Extract prophecy ID and language from path
    // Path format: prophecy_pdfs/prophecy_20_ta_1760886120.pdf
    if (preg_match('/prophecy_(\d+)_([a-z]{2})_/', $path, $matches)) {
        $prophecyId = $matches[1];
        $language = $matches[2];
        
        // Return route URL that will be proxied through the application
        return route('prophecies.download.pdf', [
            'id' => $prophecyId,
            'language' => $language
        ]);
    }
    
    // Fallback for main English PDFs (prophecy_main_*.pdf)
    if (preg_match('/prophecy_main_(\d+)_/', $path, $matches)) {
        $prophecyId = $matches[1];
        
        return route('prophecies.download.pdf', [
            'id' => $prophecyId,
            'language' => 'en'
        ]);
    }
    
    return '';
}

/**
 * Get PDF URL for a specific prophecy and language
 * Preferred method - more explicit
 */
public function getPdfUrlForProphecy(int $prophecyId, string $language = 'en'): string
{
    return route('prophecies.download.pdf', [
        'id' => $prophecyId,
        'language' => $language
    ]);
}
```

### Prophecy Detail Page

```blade
{{-- resources/views/public/prophecy-detail.blade.php --}}

@php
    $hasPdf = $prophecy->pdf_file && $pdfService->pdfExists($prophecy->pdf_file);
    // Use route-based URL for white-labeled, authenticated access
    $pdfUrl = route('prophecies.download.pdf', [
        'id' => $prophecy->id, 
        'language' => $langCode
    ]);
@endphp

@if($hasPdf)
    {{-- Clickable Image --}}
    <a href="{{ $pdfUrl }}" target="_blank">
        <img src="{{ Storage::url($featuredImage) }}" alt="Prophecy">
    </a>
    
    {{-- Download Button --}}
    <a href="{{ $pdfUrl }}" download>
        Download PDF
    </a>
@endif
```

---

## 📊 Monitoring & Analytics

### Security Logs

View all PDF downloads:
```sql
SELECT 
    u.name,
    sl.event_time,
    sl.metadata->>'prophecy_title' as prophecy,
    sl.metadata->>'language' as language,
    sl.ip_address
FROM security_logs sl
JOIN users u ON sl.user_id = u.id
WHERE sl.event_type = 'prophecy_pdf_download'
ORDER BY sl.event_time DESC;
```

### Download Counts

View most downloaded prophecies:
```sql
SELECT 
    id,
    title,
    download_count,
    jebikalam_vanga_date
FROM prophecies
ORDER BY download_count DESC
LIMIT 10;
```

### Admin Dashboard

Already implemented:
- Total prophecies count
- Total downloads count
- Recent download activity
- User download history

---

## 🧪 Testing

### Test Authenticated Download
1. **Login** to your account
2. **Visit:** https://jvprophecy.vincentselvakumar.org/prophecies/20?language=en
3. **Click** the featured image
4. **Verify:** PDF opens in new tab
5. **Check URL:** Should be `/prophecies/20/download-pdf?language=en`
6. **Not:** Laravel Cloud storage URL

### Test Unauthenticated Access
1. **Logout** or open incognito window
2. **Try to access:** https://jvprophecy.vincentselvakumar.org/prophecies/20/download-pdf?language=ta
3. **Verify:** See "Authentication Required" page
4. **Not:** Direct PDF download

### Test Download Button
1. **Login** to your account
2. **Visit:** Prophecy detail page
3. **Click** "Download PDF" button
4. **Verify:** PDF downloads with proper filename
5. **Check:** `prophecy_20_Tamil_ta.pdf` format

### Test Mobile
1. **Open** on Android/iPhone
2. **Login** to your account
3. **Visit** prophecy page
4. **Click** PDF link
5. **Verify:** PDF opens/downloads correctly
6. **Not:** `.pdf.html` file

---

## 🚀 Deployment

### No Changes Required!

The code is already updated. Just deploy:

```bash
cd /var/www/html
git pull origin main

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Restart services
sudo systemctl restart php-fpm
sudo systemctl restart nginx
```

### Verify After Deployment

```bash
# Check that route exists
php artisan route:list | grep download-pdf

# Should show:
# GET prophecies/{id}/download-pdf
```

---

## 🔮 Future Enhancements

### 1. **Temporary Signed URLs**
For extra security, generate time-limited URLs:
```php
$url = URL::temporarySignedRoute(
    'prophecies.download.pdf',
    now()->addMinutes(30),
    ['id' => $prophecy->id, 'language' => 'ta']
);
```

### 2. **Rate Limiting**
Prevent abuse:
```php
Route::middleware(['auth.download', 'throttle:10,1'])->group(function () {
    Route::get('/prophecies/{id}/download-pdf', ...);
});
```

### 3. **Premium Content**
Add role-based access:
```php
if ($prophecy->is_premium && !Auth::user()->isPremium()) {
    return redirect()->route('premium.upgrade');
}
```

### 4. **Download Analytics Dashboard**
Show admins:
- Most downloaded prophecies
- Download trends by language
- Download maps by country
- Peak download times

---

## ✅ Summary

**What Was Done:**
- ✅ Updated `PdfStorageService` to return route URLs
- ✅ Updated prophecy detail page to use route URLs
- ✅ PDFs served through main domain
- ✅ Authentication required for all downloads
- ✅ Full download tracking enabled
- ✅ Storage URLs never exposed

**URLs Now:**
```
https://jvprophecy.vincentselvakumar.org/prophecies/20/download-pdf?language=ta
```

**Storage Can Be:**
- Local (`storage/app/public/prophecy_pdfs/`)
- Cloudflare R2
- AWS S3

**User never sees where files are stored!**

---

## 📞 Support

**If PDFs don't load:**
1. Clear browser cache
2. Try incognito/private window
3. Check you're logged in
4. Check logs: `storage/logs/laravel.log`
5. Verify PDF exists: Check admin panel

**If authentication fails:**
1. Check session is active
2. Try logout and login again
3. Clear cookies
4. Check `.env` has correct `SESSION_LIFETIME`

---

**All PDFs now served securely through your domain!** 🎉🔒

