# AWS Elastic Beanstalk - Quick Start Guide
## JV Prophecy Manager - 30 Minutes to Production

---

## 🚀 Super Quick Deployment (if you have everything ready)

```bash
# 1. Initialize EB
eb init -p "PHP 8.2" jv-prophecy-manager --region ap-south-1

# 2. Create environment
eb create jv-prophecy-prod --instance-type t3.small

# 3. Set environment variables (prepare these first!)
eb setenv $(cat .env.aws | xargs)

# 4. Deploy
eb deploy

# 5. Run migrations
eb ssh -c "cd /var/app/current && php artisan migrate --force"

# Done!
eb open
```

---

## 📝 Step-by-Step Guide (First Time)

### Step 1: Prepare Credentials (5 minutes)

Create a file `.env.aws` with your configuration:

```bash
APP_NAME="JV Prophecy Manager"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=false
APP_URL=https://jvprophecy.vincentselvakumar.org
DB_HOST=your-rds-endpoint.us-east-1.rds.amazonaws.com
DB_DATABASE=jvprophecy_db
DB_USERNAME=jvprophecyadmin
DB_PASSWORD=YourStrongPassword
AWS_ACCESS_KEY_ID=AKIA...
AWS_SECRET_ACCESS_KEY=...
AWS_BUCKET=jv-prophecy-storage
```

### Step 2: Create RDS Database (10 minutes)

**Via AWS Console:**
1. RDS → Create Database → MySQL 8.0
2. Instance: `jv-prophecy-db`, t3.small
3. Storage: 20GB, gp3
4. Username: `jvprophecyadmin`
5. Create database: `jvprophecy_db`
6. ✅ Public access (for setup)
7. Wait 5-10 minutes for creation

**Quick CLI:**
```bash
aws rds create-db-instance \
    --db-instance-identifier jv-prophecy-db \
    --db-instance-class db.t3.small \
    --engine mysql \
    --master-username jvprophecyadmin \
    --master-user-password 'YourPassword123!' \
    --allocated-storage 20 \
    --db-name jvprophecy_db
```

### Step 3: Create S3 Bucket (2 minutes)

```bash
aws s3 mb s3://jv-prophecy-storage
```

### Step 4: Deploy Application (10 minutes)

```bash
# Install dependencies
composer install --no-dev --optimize-autoloader

# Initialize EB
eb init

# Create environment and deploy
eb create jv-prophecy-prod

# Set environment variables
eb setenv $(cat .env.aws | xargs)

# Deploy
eb deploy
```

### Step 5: Setup Database (3 minutes)

```bash
# SSH into instance
eb ssh

# Run migrations
cd /var/app/current
php artisan migrate --force

# Done!
exit
```

---

## ✅ Verify Deployment

```bash
# Check status
eb status

# View logs
eb logs

# Open in browser
eb open
```

---

## 🔧 Common Commands

```bash
# Deploy updates
eb deploy

# View logs in real-time
eb logs --stream

# SSH into instance
eb ssh

# Restart application
eb restart

# Set environment variable
eb setenv KEY=VALUE

# Check health
eb health

# Terminate environment
eb terminate jv-prophecy-staging
```

---

## 📊 Cost Estimate

| Service | Configuration | Monthly Cost |
|---------|--------------|--------------|
| RDS (t3.small) | 1 instance | ~$25 |
| EB (t3.small) | 1 instance | ~$15 |
| S3 Storage | 10GB | ~$2 |
| Data Transfer | 100GB | ~$9 |
| **Total** | | **~$51** |

---

## 🆘 Troubleshooting

### Application won't start
```bash
eb logs
# Check for errors in deployment
```

### Database connection failed
```bash
# Verify RDS endpoint in environment
eb printenv | grep DB_

# Test connection
eb ssh
mysql -h YOUR_RDS_ENDPOINT -u jvprophecyadmin -p
```

### 500 Error
```bash
eb ssh
tail -f /var/app/current/storage/logs/laravel.log
```

---

## 📚 Full Documentation

- **Complete Manual**: `AWS_DEPLOYMENT_MANUAL.md`
- **Deployment Checklist**: `DEPLOYMENT_CHECKLIST.md`
- **Configuration Files**: `.ebextensions/` directory

---

## 🎯 Next Steps After Deployment

1. **SSL**: Configure HTTPS with ACM certificate
2. **Domain**: Point DNS to EB endpoint
3. **Monitoring**: Set up CloudWatch alarms
4. **Backups**: Verify automated backups
5. **Scale**: Add load balancer for high traffic

---

**Need Help?** Contact: vojmedia@gmail.com

**Estimated Total Time**: 30-45 minutes (first deployment)

