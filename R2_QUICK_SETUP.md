# ⚡ Quick Setup: Cloudflare R2 for PDFs

**5-Minute Setup Guide**

---

## 1️⃣ Add to `.env` File

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

---

## 2️⃣ Clear Config Cache

```bash
php artisan config:clear
php artisan cache:clear
```

---

## 3️⃣ Test Connection (Optional)

```bash
php artisan tinker
```

In tinker:
```php
Storage::disk('r2')->put('test.txt', 'Hello R2');
Storage::disk('r2')->exists('test.txt'); // Should return true
Storage::disk('r2')->delete('test.txt');
exit
```

---

## 4️⃣ Migrate Existing PDFs (If Any)

```bash
# See what would be migrated
php artisan pdfs:migrate-to-cloud --dry-run

# Actually migrate
php artisan pdfs:migrate-to-cloud
```

---

## 5️⃣ Test PDF Upload & Download

1. **Upload**: Go to Admin → Prophecies → Edit → Upload PDF
2. **Download**: Visit prophecy page → Click "Download PDF"

---

## ✅ Done!

All PDFs are now stored in Cloudflare R2!

**Benefits**:
- ✅ Unlimited storage
- ✅ Global CDN
- ✅ No egress fees
- ✅ Automatic backups

**Full Documentation**: See `CLOUDFLARE_R2_SETUP.md`

---

**Questions?** Email: vojmedia@gmail.com

