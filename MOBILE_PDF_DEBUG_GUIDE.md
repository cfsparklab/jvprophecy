# 📱 Mobile PDF Download - Debug Guide

**Issue**: PDF download fails on mobile with error message  
**Platform**: iOS & Android browsers

---

## 🔍 Debugging Steps

### 1. Enable Console Logging on Mobile

#### iOS Safari
1. Settings → Safari → Advanced → Web Inspector (ON)
2. Connect iPhone to Mac via cable
3. Safari (Mac) → Develop → [Your iPhone] → Select page
4. Console will show logs

#### Android Chrome
1. Settings → Developer options → USB debugging (ON)
2. Connect phone to PC via cable
3. Chrome (PC) → `chrome://inspect`
4. Select your device and page
5. Console will show logs

### 2. Check Console Output

After clicking "Download PDF", look for these logs:

```javascript
Mobile PDF download handler activated  // Confirms mobile detection
Response status: 200                     // Confirms server responded
Response headers: application/pdf        // Confirms content type
Content-Type: application/pdf           // Confirms it's a PDF
Blob received: 123456 bytes, type: application/pdf
```

### 3. Common Error Scenarios

#### Error: "Session expired"
**Console shows**: `Response headers: text/html`  
**Cause**: User not logged in or session expired  
**Solution**: Login again before downloading

#### Error: "Server error: 500"
**Console shows**: `Response status: 500`  
**Cause**: Server-side error (R2 connection, file not found)  
**Solution**: Check production logs, verify R2 is accessible

#### Error: "Invalid file type"
**Console shows**: `Content-Type: text/html` or something other than PDF  
**Cause**: Server returning wrong content type  
**Solution**: Check controller is setting proper headers

#### Error: "Failed to fetch" or "Network request failed"
**Console shows**: CORS or network error  
**Cause**: Network issue, CORS problem, or SSL issue  
**Solution**: Check network connection, verify HTTPS

---

## 🧪 Quick Test Commands

### Test 1: Verify Mobile Detection
```javascript
// Run in mobile browser console
const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
console.log('Is Mobile:', isMobile);
```

### Test 2: Test Fetch Manually
```javascript
// Run in mobile browser console (replace URL)
fetch('https://jvprophecy.vincentselvakumar.org/prophecies/16/download-pdf?language=en', {
    method: 'GET',
    headers: {
        'Accept': 'application/pdf',
        'X-Requested-With': 'XMLHttpRequest'
    },
    credentials: 'same-origin'
})
.then(response => {
    console.log('Status:', response.status);
    console.log('Headers:', response.headers.get('content-type'));
    console.log('OK?:', response.ok);
    return response.blob();
})
.then(blob => {
    console.log('Blob size:', blob.size);
    console.log('Blob type:', blob.type);
})
.catch(error => {
    console.error('Error:', error);
});
```

### Test 3: Check Authentication
```javascript
// Run in mobile browser console
fetch('https://jvprophecy.vincentselvakumar.org/home')
.then(response => {
    console.log('Auth check:', response.status);
    if (response.status === 401 || response.redirected) {
        console.log('NOT LOGGED IN');
    } else {
        console.log('LOGGED IN');
    }
});
```

---

## 🔧 Alternative: Simple Mobile Download

If the Fetch + Blob approach doesn't work, try this simpler approach:

**Edit `prophecy-detail.blade.php`:**

Replace the mobile handler with:

```javascript
if (isMobile) {
    // iOS Safari has issues with Blob downloads
    // Use direct link with target="_blank" instead
    pdfDownloadBtn.addEventListener('click', function(e) {
        // Let the default behavior happen, but track it
        console.log('Mobile PDF download initiated');
        
        // Show feedback
        const downloadText = document.getElementById('download-text');
        downloadText.textContent = 'Opening PDF...';
        
        setTimeout(() => {
            downloadText.textContent = 'Download PDF';
        }, 2000);
    });
    
    // Make link open in new tab for iOS
    if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
        pdfDownloadBtn.target = '_blank';
    }
    
    console.log('Mobile PDF download handler activated (simple mode)');
}
```

---

## 🚀 Production Debugging

### Check Server Response

```bash
# SSH to production
curl -I "https://jvprophecy.vincentselvakumar.org/prophecies/16/download-pdf?language=en" \
  -H "Cookie: laravel_session=YOUR_SESSION_COOKIE" \
  -H "Accept: application/pdf"
```

Look for:
```
HTTP/1.1 200 OK
Content-Type: application/pdf
Content-Disposition: attachment; filename="..."
X-Content-Type-Options: nosniff
```

### Check Laravel Logs

```bash
# On production
tail -f storage/logs/laravel.log | grep -i "pdf\|download"
```

### Check R2 Connection

```bash
# On production
php test-pdf-download.php
```

---

## 🎯 Recommended Fix

Based on the error, here's the most likely solution:

### Option 1: Disable Mobile Handler (Use Standard Download)

**Edit `prophecy-detail.blade.php`** and comment out the mobile handler:

```javascript
// Temporarily disable mobile handler for testing
const isMobile = false; // /Android|webOS|iPhone|iPad|iPod/.test(navigator.userAgent);
```

This will make mobile use the same download method as desktop.

### Option 2: Add Download Attribute to Link

**Simpler approach:**

```html
<a href="{{ route('prophecies.download.pdf', ...) }}" 
   download="prophecy_{{ $prophecy->id }}_{{ $language }}.pdf"
   target="_blank">
    Download PDF
</a>
```

The HTML5 `download` attribute might work better on modern mobile browsers.

---

## 📞 Support Checklist

When reporting mobile PDF issues, please provide:

- [ ] Mobile device (iPhone 12, Samsung Galaxy S21, etc.)
- [ ] Browser (Safari, Chrome, Firefox)
- [ ] OS version (iOS 16, Android 12, etc.)
- [ ] Console logs (screenshots or text)
- [ ] Error message shown to user
- [ ] Does desktop download work? (Yes/No)
- [ ] Are you logged in? (Yes/No)
- [ ] Which prophecy are you trying to download?

---

## ✅ Quick Fix to Deploy

**Simplest solution - Use standard download for mobile too:**

```javascript
// In prophecy-detail.blade.php
// Change line ~796 from:
const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

// To:
const isMobile = false; // Disable mobile-specific handler
```

This will use the server's `Content-Disposition: attachment` header for all devices.

---

**Next Step**: Deploy the improved error handling and check mobile console logs to see the actual error.

