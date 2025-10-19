# reCAPTCHA v3 Troubleshooting Guide

## Common Issues and Solutions

### Issue 1: "Cannot read properties of undefined (reading 'contains')"

**Symptoms:**
- JavaScript error in browser console
- Form submission fails
- reCAPTCHA verification doesn't work

**Causes:**
- reCAPTCHA script not loaded properly
- Network connectivity issues
- Invalid reCAPTCHA keys
- Domain mismatch

**Solutions:**

1. **Check reCAPTCHA Keys:**
   ```bash
   php artisan config:show services.recaptcha
   ```
   Ensure keys are not placeholder values (`your_site_key_here`)

2. **Verify Domain Configuration:**
   - Go to [Google reCAPTCHA Console](https://www.google.com/recaptcha/admin)
   - Check that your domain is listed in the site settings
   - For development: Add `localhost` and `127.0.0.1`
   - For production: Add your actual domain

3. **Clear Configuration Cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

4. **Check Network Connectivity:**
   - Ensure Google reCAPTCHA API is accessible
   - Check firewall settings
   - Verify no ad blockers are interfering

### Issue 2: "Security verification service is temporarily unavailable"

**Symptoms:**
- Error message appears on form submission
- reCAPTCHA verification fails on backend

**Causes:**
- Invalid secret key
- Network issues with Google's API
- Rate limiting
- Server-side configuration problems

**Solutions:**

1. **Verify Secret Key:**
   - Check `.env` file has correct `RECAPTCHA_SECRET_KEY`
   - Ensure key matches the one in Google Console
   - Make sure key is for the correct environment (test vs production)

2. **Test reCAPTCHA Service:**
   ```bash
   # Check if reCAPTCHA is enabled
   php artisan tinker
   >>> app(\App\Services\RecaptchaService::class)->isEnabled()
   ```

3. **Check Logs:**
   ```bash
   tail -f storage/logs/laravel.log | grep reCAPTCHA
   ```

4. **Temporary Disable:**
   Add to `.env`:
   ```env
   RECAPTCHA_ENABLED=false
   ```

### Issue 3: Form Works But No reCAPTCHA Protection

**Symptoms:**
- Forms submit successfully
- No reCAPTCHA verification happening
- No security protection

**Causes:**
- reCAPTCHA disabled in configuration
- Missing middleware
- Invalid keys causing fallback mode

**Solutions:**

1. **Check Configuration:**
   ```env
   RECAPTCHA_ENABLED=true
   RECAPTCHA_SITE_KEY=your_actual_site_key
   RECAPTCHA_SECRET_KEY=your_actual_secret_key
   ```

2. **Verify Middleware:**
   Check `routes/web.php`:
   ```php
   Route::post('/login', [LoginController::class, 'login'])->middleware('recaptcha:login');
   Route::post('/register', [RegisterController::class, 'register'])->middleware('recaptcha:register');
   ```

3. **Test Keys:**
   Use Google's test keys for development:
   - Site key: `6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI`
   - Secret key: `6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe`

## Quick Fixes

### Fix 1: Disable reCAPTCHA Temporarily

If you need to quickly disable reCAPTCHA:

```env
RECAPTCHA_ENABLED=false
```

Then clear config:
```bash
php artisan config:clear
```

### Fix 2: Use Test Keys

For development, use Google's test keys:

```env
RECAPTCHA_SITE_KEY=6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI
RECAPTCHA_SECRET_KEY=6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe
RECAPTCHA_ENABLED=true
```

### Fix 3: Fallback Mode

The system automatically falls back to normal form submission if:
- reCAPTCHA is disabled
- Keys are invalid
- Service is unavailable

## Debugging Steps

### Step 1: Check Browser Console

1. Open browser developer tools (F12)
2. Go to Console tab
3. Look for JavaScript errors
4. Check Network tab for failed requests

### Step 2: Check Server Logs

```bash
# View recent logs
tail -n 100 storage/logs/laravel.log

# Monitor logs in real-time
tail -f storage/logs/laravel.log
```

### Step 3: Test Configuration

```bash
# Check all reCAPTCHA config
php artisan config:show services.recaptcha

# Test in Tinker
php artisan tinker
>>> $service = app(\App\Services\RecaptchaService::class);
>>> $service->isEnabled();
>>> $service->getSiteKey();
```

### Step 4: Network Testing

Test if reCAPTCHA API is accessible:
```bash
curl -X POST https://www.google.com/recaptcha/api/siteverify \
  -d "secret=YOUR_SECRET_KEY" \
  -d "response=test"
```

## Environment-Specific Solutions

### Development Environment

```env
# Use test keys
RECAPTCHA_SITE_KEY=6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI
RECAPTCHA_SECRET_KEY=6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe
RECAPTCHA_ENABLED=true
RECAPTCHA_MIN_SCORE=0.1
```

### Production Environment

```env
# Use production keys from Google Console
RECAPTCHA_SITE_KEY=your_production_site_key
RECAPTCHA_SECRET_KEY=your_production_secret_key
RECAPTCHA_ENABLED=true
RECAPTCHA_MIN_SCORE=0.5
```

## Getting Help

### Check These First:
1. Browser console for JavaScript errors
2. Server logs for backend errors
3. Network tab for failed API calls
4. reCAPTCHA console for usage statistics

### Useful Commands:
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Check configuration
php artisan config:show services.recaptcha

# Monitor logs
tail -f storage/logs/laravel.log | grep -i recaptcha
```

### Contact Information:
- Google reCAPTCHA Support: [reCAPTCHA Help Center](https://support.google.com/recaptcha/)
- Laravel Documentation: [Laravel Validation](https://laravel.com/docs/validation)

## Version Information
- **Troubleshooting Guide Version**: 1.0
- **Last Updated**: September 13, 2025
- **Compatible with**: reCAPTCHA v3, Laravel 11.x
