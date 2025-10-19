# AWS Elastic Beanstalk Deployment Package
## JV Prophecy Manager

---

## 📦 Creating Deployment Package

### Windows (PowerShell):
```powershell
.\create-ebs-package.ps1
```

### Linux/Mac (Bash):
```bash
chmod +x create-ebs-package.sh
./create-ebs-package.sh
```

---

## 📋 What's Included in Package

### ✅ Included Files:

**Application Core:**
- `app/` - Application code
- `bootstrap/` - Laravel bootstrap
- `config/` - Configuration files
- `database/` - Migrations & seeders (production only)
- `public/` - Web root & assets
- `resources/` - Views, CSS, JS
- `routes/` - Route definitions
- `storage/` - File storage (cleaned)
- `vendor/` - Composer dependencies (production)

**Configuration:**
- `.ebextensions/` - EB extensions
- `.platform/` - Platform hooks
- `.elasticbeanstalk/` - EB CLI config
- `.ebignore` - Deployment exclusions

**Root Files:**
- `artisan` - Laravel CLI
- `composer.json` - Dependency manifest
- `composer.lock` - Dependency lock
- `README.md` - Basic documentation

---

## ❌ Excluded from Package

**Development Files:**
- `node_modules/` - 150+ MB saved
- `tests/` - Test files
- `.git/` - Git repository
- `.github/` - GitHub workflows
- `*.md` - Documentation (except README)

**IDE Files:**
- `.vscode/`, `.idea/`, `.vs/`
- Editor swap files

**Build Artifacts:**
- `*.zip`, `*.tar.gz`
- Old deployment packages
- Temporary build directories

**Environment:**
- `.env` files (configure on server)
- Local configuration

**Logs & Cache:**
- `storage/logs/*.log`
- Laravel caches
- Session files

---

## 📊 Package Size Comparison

| Content | Before Cleaning | After Cleaning |
|---------|----------------|----------------|
| node_modules | ~150 MB | 0 MB (excluded) |
| vendor | ~80 MB | ~50 MB (prod only) |
| storage | ~20 MB | ~1 MB (cleaned) |
| docs/tests | ~10 MB | 0 MB (excluded) |
| **.git** | ~50 MB | 0 MB (excluded) |
| **Total** | **~310 MB** | **~70-90 MB** ✅ |

**Savings**: ~70% reduction in package size!

---

## 🚀 Deployment Options

### Option 1: EB CLI (Recommended)

```bash
# Create package
./create-ebs-package.sh

# Deploy directly
eb deploy jv-prophecy-prod

# Or deploy with specific label
eb deploy jv-prophecy-prod --label v1.0.0
```

### Option 2: AWS Console

1. Create package: `./create-ebs-package.sh`
2. Go to AWS Console → Elastic Beanstalk
3. Select your application
4. Click "Upload and Deploy"
5. Upload the ZIP file
6. Enter version label
7. Click "Deploy"

### Option 3: AWS CLI

```bash
# Create package
./create-ebs-package.sh

# Upload to S3
aws s3 cp jv-prophecy-ebs-*.zip s3://elasticbeanstalk-ap-south-1-YOUR-ACCOUNT-ID/

# Create application version
aws elasticbeanstalk create-application-version \
    --application-name jv-prophecy-manager \
    --version-label v1.0.0 \
    --source-bundle S3Bucket="elasticbeanstalk-ap-south-1-YOUR-ACCOUNT-ID",S3Key="jv-prophecy-ebs-20251011-120000.zip"

# Deploy
aws elasticbeanstalk update-environment \
    --environment-name jv-prophecy-prod \
    --version-label v1.0.0
```

---

## ✅ Pre-Deployment Checklist

Before creating the package:

- [ ] All code committed to git
- [ ] Tests passing locally
- [ ] `.env.example` updated with all required variables
- [ ] Database migrations ready
- [ ] Production dependencies installed
- [ ] Composer autoloader optimized
- [ ] Laravel caches cleared
- [ ] `.ebextensions` configured
- [ ] `.platform` hooks executable

---

## 🔧 Package Creation Process

### Step-by-Step:

1. **Clean Old Packages**
   - Remove previous ZIP files

2. **Install Dependencies**
   - `composer install --no-dev --optimize-autoloader`
   - Production-only packages

3. **Clear Caches**
   - Config, routes, views, application cache

4. **Copy Files**
   - Copy necessary application files
   - Copy EB configurations

5. **Clean Storage**
   - Remove logs
   - Clear cache/sessions/views
   - Keep directory structure

6. **Remove Test Files**
   - Factories, demo seeders
   - Test databases

7. **Create ZIP**
   - Compress all files
   - Optimal compression

8. **Cleanup**
   - Remove temporary build directory

---

## 📝 Post-Deployment Tasks

After deploying the package:

### 1. SSH into Instance
```bash
eb ssh jv-prophecy-prod
```

### 2. Run Migrations (if needed)
```bash
cd /var/app/current
php artisan migrate --force
```

### 3. Clear & Cache
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4. Verify Permissions
```bash
sudo chown -R webapp:webapp storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 5. Check Logs
```bash
tail -f storage/logs/laravel.log
```

---

## 🔍 Troubleshooting

### Package Too Large (>500 MB)

```bash
# Check what's taking space
du -sh * | sort -hr

# Common culprits:
# - node_modules (should be excluded)
# - old logs in storage/logs
# - uploaded files in storage/app
# - vendor directory (optimize with --no-dev)
```

### Composer Install Fails on Server

```bash
# Check .ebextensions/02-laravel.config
# Verify composer install command is correct
# Check PHP version compatibility
```

### Permissions Errors

```bash
# SSH into instance
eb ssh

# Fix permissions
cd /var/app/current
sudo chown -R webapp:webapp storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Missing .env Variables

```bash
# Set via EB CLI
eb setenv KEY=VALUE

# Or via console
# Environment → Configuration → Software → Environment Properties
```

---

## 📊 Deployment Verification

After deployment, verify:

```bash
# Check EB status
eb status

# Check health
eb health

# View logs
eb logs

# Test application
curl https://your-app.elasticbeanstalk.com

# SSH and check
eb ssh
cd /var/app/current
php artisan --version
php artisan config:show
```

---

## 🎯 Best Practices

### 1. Version Your Deployments
```bash
# Tag your code
git tag -a v1.0.0 -m "Release v1.0.0"
git push origin v1.0.0

# Use same version in EB
eb deploy --label v1.0.0
```

### 2. Test Before Deploy
```bash
# Run tests
php artisan test

# Check for errors
php artisan config:clear
php artisan route:cache
```

### 3. Keep Packages Small
- Exclude development dependencies
- Remove old logs
- Exclude test files
- Use `.ebignore` effectively

### 4. Monitor Deployments
- Watch EB logs during deployment
- Check CloudWatch for errors
- Verify application health
- Test critical features

### 5. Rollback Plan
```bash
# Keep previous package
cp jv-prophecy-ebs-20251011-120000.zip backups/

# Rollback if needed
eb deploy --version PREVIOUS_VERSION_LABEL
```

---

## 📞 Support

### Documentation:
- **Deployment Manual**: `AWS_DEPLOYMENT_MANUAL.md`
- **Quick Start**: `AWS_QUICKSTART.md`
- **Checklist**: `DEPLOYMENT_CHECKLIST.md`

### Issues:
- **Package creation errors**: Check script permissions and dependencies
- **Deployment failures**: Check EB logs with `eb logs`
- **Application errors**: SSH in and check Laravel logs

### Contact:
- **Email**: vojmedia@gmail.com
- **AWS Support**: [Support Console](https://console.aws.amazon.com/support/)

---

## 📈 Package Stats

**Typical Package Size**: 70-90 MB  
**Compression Ratio**: ~30%  
**Upload Time**: 2-5 minutes (depends on internet speed)  
**Deployment Time**: 5-10 minutes  
**Total Time to Production**: 10-15 minutes

---

**Created By**: VSK Development Team  
**Last Updated**: October 11, 2025  
**Version**: 1.0

