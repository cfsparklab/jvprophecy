# 🚨 Production R2 Setup - Quick Fix

**Error:** `AWS_BUCKET` is null on production

---

## ⚡ Quick Fix (5 Minutes)

### Step 1: SSH to Production

```bash
ssh your-production-server
cd /var/www/html  # or your project path
```

### Step 2: Edit .env File

```bash
nano .env
```

Add these lines (or update if they exist):

```env
PDF_STORAGE_DISK=r2

AWS_BUCKET=fls-a026b282-8d52-49fc-8351-19bb0eb16be5
AWS_DEFAULT_REGION=auto
AWS_ENDPOINT=https://367be3a2035528943240074d0096e0cd.r2.cloudflarestorage.com
AWS_URL=https://fls-a026b282-8d52-49fc-8351-19bb0eb16be5.laravel.cloud
AWS_ACCESS_KEY_ID=b49877672d559a3e1f599c53a0605e99
AWS_SECRET_ACCESS_KEY=ac447f334fc50ae58e5ec6307cdc41720941c36c46869a1e2790fa428841c250
AWS_USE_PATH_STYLE_ENDPOINT=false
```

Save: `Ctrl+X`, then `Y`, then `Enter`

### Step 3: Clear All Caches

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Step 4: Verify Configuration

```bash
php artisan tinker
```

In tinker, run:
```php
config('filesystems.disks.r2.bucket');
// Should output: fls-a026b282-8d52-49fc-8351-19bb0eb16be5

Storage::disk('r2')->put('test.txt', 'Hello R2!');
// Should return: true or the file path

Storage::disk('r2')->exists('test.txt');
// Should return: true

Storage::disk('r2')->delete('test.txt');
// Clean up

exit
```

### Step 5: Migrate PDFs

```bash
php artisan pdfs:migrate-to-cloud
```

---

## 🔍 Troubleshooting

### If .env changes don't work:

**Option A: Check permissions**
```bash
ls -la .env
# Should be readable by web server user
chmod 644 .env
```

**Option B: Use config file directly** (temporary workaround)

Edit `config/filesystems.php`:

```php
'r2' => [
    'driver' => 's3',
    'key' => 'b49877672d559a3e1f599c53a0605e99',
    'secret' => 'ac447f334fc50ae58e5ec6307cdc41720941c36c46869a1e2790fa428841c250',
    'region' => 'auto',
    'bucket' => 'fls-a026b282-8d52-49fc-8351-19bb0eb16be5',
    'url' => 'https://fls-a026b282-8d52-49fc-8351-19bb0eb16be5.laravel.cloud',
    'endpoint' => 'https://367be3a2035528943240074d0096e0cd.r2.cloudflarestorage.com',
    'use_path_style_endpoint' => false,
    'visibility' => 'public',
    'throw' => false,
    'report' => false,
],
```

⚠️ **Not recommended for security**, but works if env() isn't loading.

### If using Apache/Nginx with environment variables:

**Apache (.htaccess or virtualhost):**
```apache
SetEnv AWS_BUCKET "fls-a026b282-8d52-49fc-8351-19bb0eb16be5"
SetEnv AWS_ACCESS_KEY_ID "b49877672d559a3e1f599c53a0605e99"
# ... etc
```

**Nginx (fastcgi_params):**
```nginx
fastcgi_param AWS_BUCKET "fls-a026b282-8d52-49fc-8351-19bb0eb16be5";
fastcgi_param AWS_ACCESS_KEY_ID "b49877672d559a3e1f599c53a0605e99";
# ... etc
```

### Debug with our checker script:

```bash
# Upload check-r2-config.php to production
php check-r2-config.php
```

---

## 🎯 Expected Output After Fix

When you run:
```bash
php artisan tinker
config('filesystems.disks.r2.bucket');
```

You should see:
```
=> "fls-a026b282-8d52-49fc-8351-19bb0eb16be5"
```

NOT:
```
=> null  ❌
```

---

## ✅ Verification Checklist

- [ ] .env file contains all AWS_* variables
- [ ] config:clear has been run
- [ ] config('filesystems.disks.r2.bucket') returns correct value
- [ ] Storage::disk('r2')->put('test.txt', 'test') works
- [ ] PDFs can be uploaded via admin panel
- [ ] PDFs can be downloaded on public site

---

## 📞 Still Having Issues?

Common causes:
1. **Cached config** - Always run `php artisan config:clear` after editing .env
2. **File permissions** - .env must be readable by web server
3. **Wrong path** - Make sure you're editing the right .env file
4. **Syntax error** - No spaces around = in .env (use `AWS_BUCKET=value` not `AWS_BUCKET = value`)
5. **Quotes** - Usually don't need quotes in .env, but if value has spaces, use quotes

Run the checker script: `php check-r2-config.php`

