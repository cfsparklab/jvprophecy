# 📦 Cloudflare R2 Storage Setup for PDF Files

**Date**: October 19, 2025  
**Purpose**: Configure cloud storage for all prophecy PDF files  
**Storage Provider**: Cloudflare R2 (S3-compatible)

---

## 🎯 Overview

All PDF files for prophecies and translations are now stored in **Cloudflare R2** cloud storage instead of local server storage. This provides:

- ✅ **Unlimited scalability** - No server disk space limitations
- ✅ **Global CDN distribution** - Faster downloads worldwide
- ✅ **Cost-effective** - R2 has no egress fees
- ✅ **Reliability** - Cloud-backed redundancy
- ✅ **Easy backup** - Cloud-native backups

---

## 🔧 Configuration

### 1. Environment Variables

Add these to your `.env` file:

```env
# PDF Storage Configuration
PDF_STORAGE_DISK=r2

# Cloudflare R2 Configuration
AWS_BUCKET=fls-a026b282-8d52-49fc-8351-19bb0eb16be5
AWS_DEFAULT_REGION=auto
AWS_ENDPOINT=https://367be3a2035528943240074d0096e0cd.r2.cloudflarestorage.com
AWS_URL=https://fls-a026b282-8d52-49fc-8351-19bb0eb16be5.laravel.cloud
AWS_ACCESS_KEY_ID=b49877672d559a3e1f599c53a0605e99
AWS_SECRET_ACCESS_KEY=ac447f334fc50ae58e5ec6307cdc41720941c36c46869a1e2790fa428841c250
AWS_USE_PATH_STYLE_ENDPOINT=false
```

### 2. Verify Configuration

The R2 bucket is already configured in `config/filesystems.php` with the 'r2' disk.

---

## 📤 Migrating Existing PDFs to Cloud

### Option 1: Using Artisan Command (Recommended)

```bash
# Dry run - see what would be migrated
php artisan pdfs:migrate-to-cloud --dry-run

# Actual migration
php artisan pdfs:migrate-to-cloud

# Force re-migration (overwrites existing files in cloud)
php artisan pdfs:migrate-to-cloud --force
```

### Option 2: Manual Upload

If you have PDFs stored locally in production:

1. **Connect to your server via SSH**
2. **Navigate to project directory**
3. **Run the migration command**:
   ```bash
   cd /var/app/current
   php artisan pdfs:migrate-to-cloud
   ```

---

## 🔄 How It Works

### PDF Upload Flow

```
User uploads PDF
    ↓
Laravel receives file
    ↓
PdfStorageService stores to R2
    ↓
Database saves R2 path
    ↓
User can download from cloud
```

### PDF Download Flow

```
User requests PDF download
    ↓
Check if file exists in R2
    ↓
Fetch content from R2
    ↓
Stream to user's browser
```

---

## 📂 File Structure in R2

PDFs are organized in R2 with this structure:

```
bucket/
├── prophecy_pdfs/
│   ├── prophecy_main_1_1634567890.pdf
│   ├── prophecy_main_2_1634567891.pdf
│   └── ...
└── translations/
    ├── prophecy_ta_1_1634567892.pdf
    ├── prophecy_hi_2_1634567893.pdf
    └── ...
```

---

## 🧪 Testing

### 1. Test PDF Upload

1. Go to Admin → Prophecies → Edit any prophecy
2. Upload a PDF file
3. Save
4. Check R2 bucket to verify file was uploaded

### 2. Test PDF Download

1. Go to public prophecy page
2. Click "Download PDF"
3. Verify PDF downloads correctly
4. Check download count increments

### 3. Verify Cloud Storage

```bash
# Check if PDFs exist in R2
php artisan tinker

# In tinker:
$service = app(\App\Services\PdfStorageService::class);
$prophecy = \App\Models\Prophecy::first();
$exists = $service->pdfExists($prophecy->pdf_file);
dd($exists); // Should return true
```

---

## 🔐 Security

### Access Control

- ✅ PDFs are publicly accessible via signed URLs
- ✅ Direct bucket access is restricted to API keys
- ✅ User authentication required before download
- ✅ Security logs track all downloads

### API Key Management

**⚠️ IMPORTANT**: The AWS credentials provided have full access to your R2 bucket:

- **Never commit** these credentials to Git
- **Keep .env file** secure and excluded from version control
- **Rotate keys regularly** via Cloudflare dashboard
- **Use environment-specific** keys for dev/staging/production

---

## 📊 Monitoring

### Check Storage Usage

Via Cloudflare Dashboard:
1. Go to **R2** → **Your Bucket**
2. View **Storage** metrics
3. Monitor **Request** counts

Via Laravel:
```bash
php artisan tinker

# Count PDFs in database
\App\Models\Prophecy::whereNotNull('pdf_file')->count();
\App\Models\ProphecyTranslation::whereNotNull('pdf_file')->count();
```

---

## 🆘 Troubleshooting

### Issue: PDF Upload Fails

**Symptoms**: Error when uploading PDF in admin panel

**Solutions**:
1. Check `.env` credentials are correct
2. Verify bucket name matches
3. Check API key has write permissions
4. Review Laravel logs: `storage/logs/laravel.log`

```bash
# Test connection
php artisan tinker
Storage::disk('r2')->put('test.txt', 'Hello R2');
Storage::disk('r2')->exists('test.txt'); // Should return true
```

### Issue: PDF Download Fails

**Symptoms**: 404 error or blank page when downloading

**Solutions**:
1. Verify file exists in R2:
   ```bash
   php artisan tinker
   Storage::disk('r2')->exists('prophecy_pdfs/your_file.pdf');
   ```

2. Check logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. Verify `PDF_STORAGE_DISK=r2` in `.env`

### Issue: Migration Fails

**Symptoms**: Command errors or files not transferred

**Solutions**:
1. Check local files exist:
   ```bash
   ls -la storage/app/public/prophecy_pdfs/
   ```

2. Run with verbose output:
   ```bash
   php artisan pdfs:migrate-to-cloud -vvv
   ```

3. Check disk space and permissions

### Issue: Mixed Storage (Some local, some cloud)

**Symptoms**: Some PDFs download, others don't

**Solution**: The system automatically checks both locations:
1. R2 (cloud) - checked first
2. Local storage - fallback

Run migration to sync all to cloud:
```bash
php artisan pdfs:migrate-to-cloud
```

---

## 💰 Cost Estimation

Cloudflare R2 Pricing (as of 2024):

| Item | Cost |
|------|------|
| Storage | $0.015/GB/month |
| Class A Operations (uploads) | $4.50/million |
| Class B Operations (downloads) | $0.36/million |
| **Egress** | **FREE** ✅ |

**Example**: 1000 PDFs @ 5MB each = 5GB storage
- Storage cost: $0.075/month
- ~10,000 downloads/month: $3.60
- **Total**: ~$3.68/month

Compare to AWS S3: Same usage + egress fees would be $50-100/month!

---

## 🔄 Switching Back to Local Storage

If needed, you can switch back to local storage:

1. **Update `.env`**:
   ```env
   PDF_STORAGE_DISK=public
   ```

2. **Copy files from R2 to local** (if needed):
   ```bash
   # Custom command needed for reverse migration
   # Or download manually from R2 dashboard
   ```

3. **Restart services**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

---

## 📚 Related Files

### Service Layer
- `app/Services/PdfStorageService.php` - Main storage service
- `app/Console/Commands/MigratePdfsToCloud.php` - Migration command

### Controllers
- `app/Http/Controllers/Admin/ProphecyController.php` - PDF uploads
- `app/Http/Controllers/PublicController.php` - PDF downloads

### Configuration
- `config/filesystems.php` - Filesystem configuration
- `.env.cloudflare` - Example configuration

---

## ✅ Post-Setup Checklist

- [ ] Environment variables added to `.env`
- [ ] R2 credentials tested and working
- [ ] Existing PDFs migrated to cloud
- [ ] PDF upload tested in admin panel
- [ ] PDF download tested on public site
- [ ] Migration command completes successfully
- [ ] Storage metrics monitored in Cloudflare
- [ ] Backup strategy in place
- [ ] Team members notified of change
- [ ] Documentation reviewed

---

## 🎉 Benefits Achieved

After setup, you'll have:

✅ **Scalable storage** - No more "disk full" errors  
✅ **Fast downloads** - Global CDN distribution  
✅ **Cost savings** - No egress fees  
✅ **Reliability** - Cloud redundancy  
✅ **Easy management** - Cloudflare dashboard  
✅ **Future-proof** - Ready for growth  

---

## 📞 Support

### Cloudflare R2 Resources
- [R2 Documentation](https://developers.cloudflare.com/r2/)
- [R2 Dashboard](https://dash.cloudflare.com/)
- [API Reference](https://developers.cloudflare.com/r2/api/)

### Internal Support
- Email: vojmedia@gmail.com
- Check Laravel logs: `storage/logs/laravel.log`
- Run diagnostics: `php artisan pdfs:migrate-to-cloud --dry-run`

---

**Setup Date**: October 19, 2025  
**Status**: ✅ Ready for Production  
**Version**: 1.0

