# AWS Deployment Checklist
## JV Prophecy Manager

---

## Pre-Deployment Checklist

### AWS Account Setup
- [ ] AWS account created and billing configured
- [ ] IAM user created with necessary permissions
- [ ] AWS CLI installed and configured locally
- [ ] EB CLI installed locally
- [ ] SSH key pair created for EC2 access

### Database (RDS) Setup
- [ ] RDS MySQL instance created (t3.small recommended)
- [ ] Database name: `jvprophecy_db`
- [ ] Master username and password saved securely
- [ ] Security group configured (port 3306)
- [ ] Database endpoint noted
- [ ] Backup retention configured (7 days)
- [ ] Encryption enabled
- [ ] Initial SQL script run (`aws-rds-init.sql`)
- [ ] Test connection successful

### Storage (S3) Setup
- [ ] S3 bucket created: `jv-prophecy-storage`
- [ ] Bucket policy configured (`s3-bucket-policy.json`)
- [ ] CORS configuration set
- [ ] Versioning enabled (optional)
- [ ] Lifecycle policies configured (optional)
- [ ] IAM policy for S3 access created

### SSL/TLS Certificate
- [ ] Domain name registered
- [ ] SSL certificate requested in AWS Certificate Manager
- [ ] Certificate validated (DNS or email)
- [ ] Certificate ARN noted

### Application Configuration
- [ ] `.ebextensions` directory created with all config files
- [ ] `.platform` directory created with custom configurations
- [ ] `.ebignore` file configured
- [ ] `.elasticbeanstalk/config.yml` created
- [ ] Environment variables prepared (`.env.aws.example`)
- [ ] APP_KEY generated
- [ ] Database credentials ready
- [ ] AWS credentials (Access Key ID & Secret) ready
- [ ] Mail configuration ready
- [ ] reCAPTCHA keys ready (if using)

---

## Deployment Checklist

### Initial Setup
- [ ] Run `eb init` to initialize EB application
- [ ] Application name: `jv-prophecy-manager`
- [ ] Platform: PHP 8.2 on Amazon Linux 2023
- [ ] Region selected (e.g., us-east-1)

### Environment Creation
- [ ] Run `eb create jv-prophecy-prod`
- [ ] Instance type: t3.small (production) or t3.micro (staging)
- [ ] Load balancer configured (for production)
- [ ] Health check path set to `/`
- [ ] Environment variables set via `eb setenv`

### Database Migration
- [ ] SSH into EB instance
- [ ] Run `php artisan migrate --force`
- [ ] Run `php artisan db:seed --force` (if needed)
- [ ] Verify tables created

### Storage Configuration
- [ ] Run `php artisan storage:link`
- [ ] Test file upload to S3
- [ ] Verify public files accessible

### SSL Configuration
- [ ] Load balancer listener added for port 443
- [ ] SSL certificate attached to listener
- [ ] HTTP to HTTPS redirect configured
- [ ] Test HTTPS access

---

## Post-Deployment Checklist

### Functional Testing
- [ ] Homepage loads successfully
- [ ] User registration works
- [ ] User login works
- [ ] Email verification works
- [ ] Password reset works
- [ ] Prophecy viewing works
- [ ] PDF download works
- [ ] PDF print works
- [ ] Translation switching works
- [ ] Search functionality works
- [ ] Admin dashboard accessible
- [ ] File uploads work (S3)
- [ ] Images display correctly

### Admin Testing
- [ ] Admin login works
- [ ] Create prophecy works
- [ ] Edit prophecy works
- [ ] Delete prophecy works
- [ ] Manage categories works
- [ ] Manage users works
- [ ] Security logs visible
- [ ] System status shows correctly
- [ ] Recent activities display

### Security Testing
- [ ] HTTPS enforced (HTTP redirects to HTTPS)
- [ ] Security headers configured
- [ ] Database credentials not exposed
- [ ] `.env` file not accessible
- [ ] Admin routes protected
- [ ] SQL injection protection working
- [ ] XSS protection working
- [ ] CSRF protection working

### Performance Testing
- [ ] Page load time < 3 seconds
- [ ] Database queries optimized
- [ ] Static assets cached
- [ ] Gzip compression enabled
- [ ] Images optimized
- [ ] CDN configured (optional)

### Monitoring Setup
- [ ] CloudWatch logs enabled
- [ ] Health reporting set to enhanced
- [ ] CPU utilization alarm configured
- [ ] Database connections alarm configured
- [ ] Disk space alarm configured
- [ ] Error rate alarm configured
- [ ] Email notifications configured

### Backup Configuration
- [ ] RDS automated backups enabled
- [ ] Manual snapshot created
- [ ] S3 versioning enabled
- [ ] Application code backup strategy defined
- [ ] Backup restore tested

### DNS Configuration
- [ ] DNS CNAME record created
- [ ] Points to EBS endpoint
- [ ] TTL set appropriately
- [ ] DNS propagation verified
- [ ] Domain accessible via custom domain

---

## Post-Launch Checklist

### Week 1
- [ ] Monitor error logs daily
- [ ] Check CloudWatch metrics
- [ ] Verify backups running
- [ ] Test disaster recovery
- [ ] Review security logs
- [ ] Monitor costs

### Week 2
- [ ] Performance optimization review
- [ ] User feedback collected
- [ ] Security audit
- [ ] Backup verification
- [ ] Cost optimization review

### Monthly
- [ ] Security patches applied
- [ ] Dependencies updated
- [ ] Backup retention reviewed
- [ ] Cost analysis
- [ ] Performance metrics reviewed
- [ ] User growth analysis

---

## Rollback Plan

### If Deployment Fails:
1. [ ] Check EB logs: `eb logs`
2. [ ] Review CloudWatch logs
3. [ ] SSH into instance: `eb ssh`
4. [ ] Check Laravel logs: `/var/app/current/storage/logs/laravel.log`
5. [ ] Rollback to previous version: `eb deploy --version VERSION_LABEL`

### If Critical Issue After Deployment:
1. [ ] Immediately rollback: `eb deploy --version PREVIOUS_VERSION`
2. [ ] Notify users of maintenance
3. [ ] Investigate issue in staging
4. [ ] Fix and test thoroughly
5. [ ] Re-deploy when fixed

---

## Emergency Contacts

- **Technical Lead**: [Name] - [Email] - [Phone]
- **AWS Support**: [Support Plan] - [Support Portal URL]
- **Database Admin**: [Name] - [Email] - [Phone]
- **DevOps**: [Name] - [Email] - [Phone]

---

## Important URLs

- **Application**: https://jvprophecy.vincentselvakumar.org
- **AWS Console**: https://console.aws.amazon.com
- **RDS Endpoint**: jv-prophecy-db.xxxxxx.us-east-1.rds.amazonaws.com
- **S3 Bucket**: https://s3.console.aws.amazon.com/s3/buckets/jv-prophecy-storage
- **CloudWatch**: https://console.aws.amazon.com/cloudwatch

---

## Notes

- **Deployment Time**: Approximately 10-15 minutes
- **Downtime**: Zero downtime if using load balancer
- **Cost**: ~$51/month (estimated)
- **Scaling**: Auto-scaling configured for 2-4 instances
- **Backup**: Daily automated, 7 days retention

---

**Last Updated**: October 11, 2025  
**Version**: 1.0  
**Maintained By**: VSK Development Team
