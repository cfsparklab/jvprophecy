# AWS Elastic Beanstalk Deployment Manual
## JV Prophecy Manager - Complete Deployment Guide

**Version**: 1.0  
**Date**: October 11, 2025  
**Target**: AWS Elastic Beanstalk + RDS MySQL

---

## 📋 Table of Contents

1. [Prerequisites](#prerequisites)
2. [AWS Services Setup](#aws-services-setup)
3. [Database Configuration (RDS)](#database-configuration-rds)
4. [Elastic Beanstalk Setup](#elastic-beanstalk-setup)
5. [Application Deployment](#application-deployment)
6. [Post-Deployment Configuration](#post-deployment-configuration)
7. [SSL/HTTPS Setup](#sslhttps-setup)
8. [Monitoring & Maintenance](#monitoring--maintenance)
9. [Troubleshooting](#troubleshooting)

---

## 1. Prerequisites

### Required Tools:
- ✅ AWS Account with billing enabled
- ✅ AWS CLI installed and configured
- ✅ EB CLI (Elastic Beanstalk CLI) installed
- ✅ Composer installed locally
- ✅ Git installed
- ✅ SSH key pair for EC2 access

### AWS CLI Installation:
```bash
# macOS
brew install awscli

# Windows
# Download from: https://aws.amazon.com/cli/

# Linux
curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"
unzip awscliv2.zip
sudo ./aws/install
```

### EB CLI Installation:
```bash
pip install awsebcli --upgrade
```

### Configure AWS CLI:
```bash
aws configure
# Enter:
# - AWS Access Key ID
# - AWS Secret Access Key
# - Default region: us-east-1 (or your preferred region)
# - Default output format: json
```

---

## 2. AWS Services Setup

### 2.1 Create IAM User (if needed)

1. Go to AWS Console → IAM → Users
2. Click "Add users"
3. User name: `jv-prophecy-deployer`
4. Access type: ✅ Programmatic access, ✅ AWS Management Console access
5. Attach policies:
   - `AWSElasticBeanstalkFullAccess`
   - `AmazonRDSFullAccess`
   - `AmazonS3FullAccess`
   - `CloudWatchFullAccess`
6. Download credentials (save securely!)

### 2.2 Create S3 Bucket for Storage

```bash
# Create S3 bucket for file storage
aws s3 mb s3://jv-prophecy-storage --region us-east-1

# Set bucket policy for public read (for public assets only)
aws s3api put-bucket-policy --bucket jv-prophecy-storage --policy file://aws-configs/s3-bucket-policy.json
```

---

## 3. Database Configuration (RDS)

### 3.1 Create RDS MySQL Database

**Via AWS Console:**

1. Go to AWS Console → RDS → Create Database
2. Choose a database creation method: **Standard create**
3. Engine options:
   - Engine type: **MySQL**
   - Version: **MySQL 8.0.35** (latest stable)
4. Templates: **Production** (or Dev/Test for staging)
5. Settings:
   - DB instance identifier: `jv-prophecy-db`
   - Master username: `jvprophecyadmin`
   - Master password: `[Generate strong password]`
6. DB instance class:
   - **db.t3.micro** (Free tier) - For development
   - **db.t3.small** - For production (recommended)
7. Storage:
   - Storage type: **General Purpose SSD (gp3)**
   - Allocated storage: **20 GB**
   - ✅ Enable storage autoscaling (Max: 100 GB)
8. Connectivity:
   - VPC: Default VPC
   - Public access: **Yes** (for initial setup, can restrict later)
   - VPC security group: Create new `jv-prophecy-db-sg`
   - Availability Zone: No preference
9. Database authentication: **Password authentication**
10. Additional configuration:
    - Initial database name: `jvprophecy_db`
    - ✅ Enable automated backups (7 days retention)
    - Backup window: 03:00 - 04:00 UTC
    - ✅ Enable encryption
11. Click **Create Database**

**Via AWS CLI:**

```bash
aws rds create-db-instance \
    --db-instance-identifier jv-prophecy-db \
    --db-instance-class db.t3.small \
    --engine mysql \
    --engine-version 8.0.35 \
    --master-username jvprophecyadmin \
    --master-user-password 'YourStrongPassword123!' \
    --allocated-storage 20 \
    --storage-type gp3 \
    --storage-encrypted \
    --backup-retention-period 7 \
    --vpc-security-group-ids sg-xxxxxxxxx \
    --db-name jvprophecy_db \
    --publicly-accessible \
    --region us-east-1
```

### 3.2 Configure Security Group

```bash
# Get your current IP
MY_IP=$(curl -s https://checkip.amazonaws.com)

# Add inbound rule to RDS security group (allow MySQL from EB)
aws ec2 authorize-security-group-ingress \
    --group-id sg-xxxxxxxxx \
    --protocol tcp \
    --port 3306 \
    --source-group sg-yyyyyyyy  # EB instance security group

# Temporarily allow your IP for initial setup
aws ec2 authorize-security-group-ingress \
    --group-id sg-xxxxxxxxx \
    --protocol tcp \
    --port 3306 \
    --cidr $MY_IP/32
```

### 3.3 Initialize Database

**Connect to RDS:**

```bash
# Get RDS endpoint
aws rds describe-db-instances \
    --db-instance-identifier jv-prophecy-db \
    --query 'DBInstances[0].Endpoint.Address' \
    --output text

# Connect via MySQL client
mysql -h jv-prophecy-db.xxxxxxxxx.us-east-1.rds.amazonaws.com \
      -u jvprophecyadmin \
      -p \
      jvprophecy_db
```

**Run Initial Setup:**

```sql
-- Verify connection
SELECT VERSION();

-- Create additional user for application (optional)
CREATE USER 'jvprophecy'@'%' IDENTIFIED BY 'AnotherStrongPassword123!';
GRANT ALL PRIVILEGES ON jvprophecy_db.* TO 'jvprophecy'@'%';
FLUSH PRIVILEGES;

-- Show databases
SHOW DATABASES;
```

---

## 4. Elastic Beanstalk Setup

### 4.1 Initialize EB Application

```bash
# Navigate to project directory
cd /path/to/VSK-JV-Prophecy

# Initialize Elastic Beanstalk
eb init

# Answer prompts:
# - Region: 10 (us-east-1) or your preferred region
# - Application name: jv-prophecy-manager
# - Platform: PHP
# - Platform version: PHP 8.2 running on 64bit Amazon Linux 2023
# - CodeCommit: No
# - SSH: Yes
# - Key pair: Select existing or create new
```

### 4.2 Create Environment

```bash
# Create production environment
eb create jv-prophecy-prod \
    --instance-type t3.small \
    --platform "PHP 8.2 running on 64bit Amazon Linux 2023" \
    --region us-east-1 \
    --single \
    --timeout 30

# Or for staging environment
eb create jv-prophecy-staging \
    --instance-type t3.micro \
    --platform "PHP 8.2 running on 64bit Amazon Linux 2023" \
    --region us-east-1 \
    --single
```

**Environment Options:**
- `--single`: Single instance (no load balancer) - cheaper for development
- For production with load balancer, remove `--single` and add:
  ```bash
  --elb-type application \
  --min-instances 2 \
  --max-instances 4
  ```

### 4.3 Configure Environment Variables

```bash
# Set environment variables
eb setenv \
    APP_NAME="JV Prophecy Manager" \
    APP_ENV=production \
    APP_DEBUG=false \
    APP_URL=https://jvprophecy.vincentselvakumar.org \
    DB_CONNECTION=mysql \
    DB_HOST=jv-prophecy-db.xxxxxxxxx.us-east-1.rds.amazonaws.com \
    DB_PORT=3306 \
    DB_DATABASE=jvprophecy_db \
    DB_USERNAME=jvprophecyadmin \
    DB_PASSWORD='YourStrongPassword123!' \
    AWS_ACCESS_KEY_ID=AKIA... \
    AWS_SECRET_ACCESS_KEY=... \
    AWS_DEFAULT_REGION=us-east-1 \
    AWS_BUCKET=jv-prophecy-storage \
    MAIL_MAILER=smtp \
    MAIL_HOST=smtp.gmail.com \
    MAIL_PORT=587 \
    MAIL_USERNAME=your-email@gmail.com \
    MAIL_PASSWORD='your-app-password' \
    MAIL_ENCRYPTION=tls \
    MAIL_FROM_ADDRESS=noreply@jvprophecy.vincentselvakumar.org \
    MAIL_FROM_NAME="JV Prophecy Manager" \
    SESSION_DRIVER=database \
    QUEUE_CONNECTION=database \
    CACHE_DRIVER=file \
    FILESYSTEM_DISK=s3

# Generate and set APP_KEY
php artisan key:generate --show
eb setenv APP_KEY='base64:...'
```

---

## 5. Application Deployment

### 5.1 Prepare Application for Deployment

**Update `.ebextensions` configurations** (see configuration files section)

**Create deployment archive:**

```bash
# Install dependencies
composer install --no-dev --optimize-autoloader

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create build directory
rm -rf .ebignore
echo "node_modules/" > .ebignore
echo ".git/" >> .ebignore
echo ".env.example" >> .ebignore
echo "tests/" >> .ebignore
echo "*.zip" >> .ebignore
```

### 5.2 Deploy Application

```bash
# Deploy to Elastic Beanstalk
eb deploy

# Or deploy specific environment
eb deploy jv-prophecy-prod

# Monitor deployment
eb logs --stream
```

### 5.3 Run Database Migrations

**Option A: Via EB SSH**

```bash
# SSH into EB instance
eb ssh jv-prophecy-prod

# Navigate to app directory
cd /var/app/current

# Run migrations
php artisan migrate --force

# Seed database (if needed)
php artisan db:seed --force

# Exit SSH
exit
```

**Option B: Via .ebextensions (Automatic on deploy)**

This is configured in `.ebextensions/02-laravel.config`

---

## 6. Post-Deployment Configuration

### 6.1 Set Up Storage

```bash
# SSH into instance
eb ssh jv-prophecy-prod

# Create storage symlink
cd /var/app/current
php artisan storage:link

# Set permissions
sudo chown -R webapp:webapp storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

exit
```

### 6.2 Configure Scheduled Tasks (Cron)

Edit `.ebextensions/03-cron.config` to add Laravel scheduler:

```yaml
files:
  "/etc/cron.d/laravel-scheduler":
    mode: "000644"
    owner: root
    group: root
    content: |
      * * * * * webapp cd /var/app/current && php artisan schedule:run >> /dev/null 2>&1
```

### 6.3 Set Up Queue Workers

For background jobs, configure Supervisor in `.ebextensions/04-supervisor.config`

### 6.4 Update DNS Records

**Point your domain to EBS:**

1. Get EBS endpoint:
   ```bash
   eb status | grep "CNAME"
   ```

2. Create DNS records:
   ```
   Type: CNAME
   Name: jvprophecy (or @)
   Value: jv-prophecy-prod.xxxxxxxx.us-east-1.elasticbeanstalk.com
   TTL: 300
   ```

---

## 7. SSL/HTTPS Setup

### 7.1 Request SSL Certificate (AWS Certificate Manager)

```bash
# Request certificate
aws acm request-certificate \
    --domain-name jvprophecy.vincentselvakumar.org \
    --subject-alternative-names "*.jvprophecy.vincentselvakumar.org" \
    --validation-method DNS \
    --region us-east-1

# Note the CertificateArn from output
```

### 7.2 Validate Certificate

1. Go to AWS Console → Certificate Manager
2. Click on certificate → "Create records in Route 53"
3. Or manually add CNAME records to your DNS

### 7.3 Configure Load Balancer with SSL

**Update `.ebextensions/05-https.config`**

Or via console:
1. EC2 → Load Balancers → Select your ELB
2. Listeners → Add listener
3. Protocol: HTTPS, Port: 443
4. SSL Certificate: Select from ACM
5. Default actions: Forward to target group

---

## 8. Monitoring & Maintenance

### 8.1 Enable CloudWatch Monitoring

```bash
# Enable enhanced health reporting
eb config

# In the editor, set:
# HealthCheckPath: /
# HealthReporting: enhanced
```

### 8.2 Set Up Alarms

```bash
# CPU Utilization alarm
aws cloudwatch put-metric-alarm \
    --alarm-name jv-prophecy-high-cpu \
    --alarm-description "Alert when CPU exceeds 80%" \
    --metric-name CPUUtilization \
    --namespace AWS/EC2 \
    --statistic Average \
    --period 300 \
    --threshold 80 \
    --comparison-operator GreaterThanThreshold \
    --evaluation-periods 2

# Database connections alarm
aws cloudwatch put-metric-alarm \
    --alarm-name jv-prophecy-db-connections \
    --alarm-description "Alert when DB connections are high" \
    --metric-name DatabaseConnections \
    --namespace AWS/RDS \
    --statistic Average \
    --period 300 \
    --threshold 40 \
    --comparison-operator GreaterThanThreshold \
    --evaluation-periods 2
```

### 8.3 Backup Strategy

**RDS Automated Backups**: Already configured (7 days)

**Manual Snapshots:**
```bash
# Create manual snapshot
aws rds create-db-snapshot \
    --db-instance-identifier jv-prophecy-db \
    --db-snapshot-identifier jv-prophecy-manual-$(date +%Y%m%d)
```

**Application Code Backups:**
```bash
# S3 bucket for backups
aws s3 mb s3://jv-prophecy-backups

# Automated backup script (add to cron)
eb deploy --staged --label "backup-$(date +%Y%m%d-%H%M%S)"
```

---

## 9. Troubleshooting

### Common Issues

#### 1. Database Connection Failed

```bash
# Check RDS security group
aws rds describe-db-instances --db-instance-identifier jv-prophecy-db

# Test connection from EB instance
eb ssh
mysql -h YOUR_RDS_ENDPOINT -u jvprophecyadmin -p
```

#### 2. 500 Internal Server Error

```bash
# Check logs
eb logs

# Or SSH and check Laravel logs
eb ssh
tail -f /var/app/current/storage/logs/laravel.log
```

#### 3. Storage Permission Issues

```bash
eb ssh
cd /var/app/current
sudo chown -R webapp:webapp storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

#### 4. Environment Variables Not Working

```bash
# Verify environment variables
eb printenv

# Re-set if needed
eb setenv KEY=VALUE
```

### Useful Commands

```bash
# View environment status
eb status

# View logs
eb logs

# SSH into instance
eb ssh

# Restart application
eb restart

# View health
eb health

# Open app in browser
eb open

# Terminate environment
eb terminate jv-prophecy-staging
```

---

## 10. Cost Optimization

### Estimated Monthly Costs:

| Service | Configuration | Cost (USD) |
|---------|--------------|------------|
| RDS (t3.small) | 1 instance, 20GB | ~$25/month |
| EB (t3.small) | 1 instance | ~$15/month |
| S3 Storage | 10GB + requests | ~$2/month |
| Data Transfer | 100GB/month | ~$9/month |
| **Total** | | **~$51/month** |

### Cost-Saving Tips:

1. **Use Reserved Instances** for production (save up to 40%)
2. **Enable auto-scaling** to scale down during low traffic
3. **Use S3 Lifecycle policies** to archive old files
4. **Implement CloudFront CDN** for static assets (reduces data transfer)
5. **Schedule non-prod environments** to stop after hours

---

## 11. Deployment Checklist

### Pre-Deployment:
- [ ] AWS account configured
- [ ] RDS database created and accessible
- [ ] S3 bucket created
- [ ] SSL certificate requested and validated
- [ ] Environment variables configured
- [ ] `.ebextensions` files created
- [ ] Dependencies installed (`composer install`)
- [ ] Application tested locally

### Deployment:
- [ ] Run `eb init`
- [ ] Run `eb create`
- [ ] Deploy application: `eb deploy`
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Set up storage symlink
- [ ] Configure cron jobs
- [ ] Test application functionality

### Post-Deployment:
- [ ] Verify database connection
- [ ] Test file uploads (S3)
- [ ] Check email functionality
- [ ] Verify SSL/HTTPS working
- [ ] Configure monitoring alerts
- [ ] Set up backups
- [ ] Update DNS records
- [ ] Test on multiple devices

---

## 12. Support & Resources

### AWS Documentation:
- [Elastic Beanstalk PHP](https://docs.aws.amazon.com/elasticbeanstalk/latest/dg/php-laravel-tutorial.html)
- [RDS MySQL](https://docs.aws.amazon.com/AmazonRDS/latest/UserGuide/CHAP_MySQL.html)
- [S3 Documentation](https://docs.aws.amazon.com/s3/)

### Laravel Deployment:
- [Laravel Deployment](https://laravel.com/docs/10.x/deployment)
- [Laravel on AWS](https://laravel.com/docs/10.x/envoy)

### Contact:
- **Technical Support**: vojmedia@gmail.com
- **AWS Support**: [AWS Support Console](https://console.aws.amazon.com/support/)

---

**Document Version**: 1.0  
**Last Updated**: October 11, 2025  
**Maintained By**: VSK Development Team

