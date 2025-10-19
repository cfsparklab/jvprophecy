# 🔒 SSL Certificate Setup Guide

**Status**: HTTPS currently **disabled** for initial deployment  
**Reason**: Avoiding certificate configuration errors  
**Result**: Application will run on HTTP first, HTTPS can be added later

---

## 🎯 Current Configuration

**Initial Deployment**: HTTP only (port 80)  
**After SSL Setup**: HTTPS (port 443) with HTTP redirect

This approach ensures successful initial deployment without certificate issues.

---

## 📋 Steps to Enable HTTPS

### Step 1: Create SSL Certificate in ACM

#### Option A: Request a Certificate (Recommended for new domains)

```bash
# Request certificate via AWS CLI
aws acm request-certificate \
    --domain-name jvprophecy.vincentselvakumar.org \
    --subject-alternative-names '*.jvprophecy.vincentselvakumar.org' \
    --validation-method DNS \
    --region ap-south-1
```

**Or via AWS Console**:
1. Go to **AWS Certificate Manager (ACM)** in **ap-south-1 (Mumbai)**
2. Click **"Request a certificate"**
3. **Domain name**: `jvprophecy.vincentselvakumar.org`
4. **Add another name**: `*.jvprophecy.vincentselvakumar.org` (optional, for subdomains)
5. **Validation method**: DNS validation (recommended)
6. Click **"Request"**
7. **Add DNS records** to your domain (CNAME records provided by ACM)
8. Wait for validation (5-30 minutes)

#### Option B: Import Existing Certificate

If you already have an SSL certificate:

```bash
aws acm import-certificate \
    --certificate fileb://certificate.crt \
    --private-key fileb://private.key \
    --certificate-chain fileb://certificate-chain.crt \
    --region ap-south-1
```

**Or via AWS Console**:
1. Go to **ACM** → **Import a certificate**
2. Paste certificate body, private key, and chain
3. Click **"Import"**

---

### Step 2: Get Certificate ARN

#### Via Console:
1. Go to **ACM** in **ap-south-1**
2. Find your certificate
3. Click on it
4. Copy the **ARN** (looks like: `arn:aws:acm:ap-south-1:123456789012:certificate/abc-123-def`)

#### Via CLI:
```bash
aws acm list-certificates --region ap-south-1
```

---

### Step 3: Update Configuration

1. **Open**: `.ebextensions/05-https.config.disabled`
2. **Replace**: `CERTIFICATE_ARN_HERE` with your actual certificate ARN
3. **Example**:
   ```yaml
   SSLCertificateArns: arn:aws:acm:ap-south-1:123456789012:certificate/abc-123-def
   ```
4. **Rename**: `05-https.config.disabled` → `05-https.config`
   ```bash
   # Windows
   ren .ebextensions\05-https.config.disabled .ebextensions\05-https.config
   
   # Linux/Mac
   mv .ebextensions/05-https.config.disabled .ebextensions/05-https.config
   ```

---

### Step 4: Enable HTTPS Redirect

In `.ebextensions/05-https.config`, uncomment the redirect:

```nginx
# Before:
# if ($http_x_forwarded_proto = "http") {
#   return 301 https://$host$request_uri;
# }

# After:
if ($http_x_forwarded_proto = "http") {
  return 301 https://$host$request_uri;
}
```

---

### Step 5: Recreate Package and Deploy

```bash
# Create new package
powershell -ExecutionPolicy Bypass -File create-package.ps1

# Deploy
eb deploy jv-prophecy-prod
```

---

### Step 6: Update DNS

Point your domain to the Elastic Beanstalk environment:

#### Get ELB DNS:
```bash
eb status
# Look for: CNAME: jv-prophecy-prod.ap-south-1.elasticbeanstalk.com
```

#### Create DNS Records:

**Option A: CNAME (Subdomain)**
```
Type: CNAME
Name: jvprophecy (or your subdomain)
Value: jv-prophecy-prod.ap-south-1.elasticbeanstalk.com
TTL: 300
```

**Option B: ALIAS (Root Domain - Route 53 only)**
```
Type: A (Alias)
Name: @ (root)
Alias Target: Select your ELB
TTL: 300
```

---

### Step 7: Verify HTTPS

```bash
# Test HTTPS
curl -I https://jvprophecy.vincentselvakumar.org

# Should return:
# HTTP/2 200
# ... SSL certificate valid ...

# Test HTTP redirect
curl -I http://jvprophecy.vincentselvakumar.org

# Should return:
# HTTP/1.1 301 Moved Permanently
# Location: https://jvprophecy.vincentselvakumar.org
```

---

## 🔍 Troubleshooting

### Certificate Not Valid Error

**Error**: "Certificate ARN is not valid"

**Causes & Solutions**:

1. **Wrong Region**
   - ✅ Certificate must be in **ap-south-1** (same as EB environment)
   - ❌ Certificate in us-east-1 won't work
   - **Fix**: Create certificate in ap-south-1

2. **Certificate Not Validated**
   - Check ACM console
   - Ensure DNS validation records are added
   - Wait for status: "Issued"

3. **Wrong ARN Format**
   - ✅ Correct: `arn:aws:acm:ap-south-1:123456789012:certificate/abc-123`
   - ❌ Wrong: Partial ARN or placeholder

4. **Expired Certificate**
   - Check expiration date in ACM
   - Renew or create new certificate

### Load Balancer Issues

**Issue**: Environment doesn't have load balancer

**Solution**: Change environment type to load balanced:

```bash
eb scale 2
# This converts to load balanced environment
```

Or via console:
1. Configuration → Capacity
2. Environment type: Load balanced
3. Min instances: 1, Max instances: 4

### DNS Validation Taking Long

**Normal**: 5-30 minutes  
**Extended**: Up to 72 hours in rare cases

**Check**:
```bash
# Verify DNS records are correct
dig CNAME _abc123.jvprophecy.vincentselvakumar.org

# Should show the ACM validation CNAME
```

---

## 📊 Deployment Timeline

### Phase 1: Initial Deployment (No HTTPS)
- ✅ Deploy application on HTTP
- ✅ Test all features
- ✅ Ensure stability
- **Time**: 10-15 minutes

### Phase 2: SSL Setup (After initial deployment)
1. Request/import certificate: 5 minutes
2. DNS validation: 5-30 minutes
3. Update configuration: 5 minutes
4. Redeploy with HTTPS: 10 minutes
5. Update DNS records: 5 minutes
6. DNS propagation: 5-60 minutes
- **Total Time**: 30-120 minutes

---

## 🎯 Quick Commands

### Check Current Configuration:
```bash
eb status
eb printenv | grep -i ssl
```

### After SSL is Enabled:
```bash
# Test SSL
openssl s_client -connect jvprophecy.vincentselvakumar.org:443

# Check certificate details
echo | openssl s_client -servername jvprophecy.vincentselvakumar.org -connect jvprophecy.vincentselvakumar.org:443 2>/dev/null | openssl x509 -noout -dates

# Force SSL renewal (Let's Encrypt)
eb ssh
sudo certbot renew
```

---

## ✅ Best Practices

### 1. Use DNS Validation
- Easier than email validation
- Automatic renewal support
- No inbox access needed

### 2. Request Wildcard Certificates
- Covers subdomain variations
- `*.yourdomain.com` + `yourdomain.com`

### 3. Set Up Auto-Renewal Monitoring
- ACM handles renewal automatically for validated certificates
- Set CloudWatch alarm for expiring imported certificates

### 4. Test HTTP to HTTPS Redirect
```bash
# Should redirect to HTTPS
curl -L http://yourdomain.com

# Should show HTTPS
curl -I https://yourdomain.com
```

### 5. Use HSTS Headers (After SSL is working)

Add to Nginx config:
```nginx
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;
```

---

## 💰 Cost Implications

### Free:
- ✅ ACM certificates (AWS managed)
- ✅ Certificate renewals
- ✅ Unlimited domains/subdomains

### Paid:
- Load Balancer: ~$16/month (required for HTTPS)
- Additional EC2 instances if scaling: ~$15/instance/month

**Note**: Single instance environments can't use ACM certificates. You'll need:
- Load balanced environment (2+ instances), OR
- Single instance with self-signed cert (not recommended for production)

---

## 🆘 Need Help?

### AWS Documentation:
- [ACM User Guide](https://docs.aws.amazon.com/acm/)
- [ELB HTTPS Listeners](https://docs.aws.amazon.com/elasticloadbalancing/latest/application/create-https-listener.html)

### Common Issues:
- Certificate validation: Check DNS records
- Wrong region: Use ap-south-1
- Load balancer required: Scale to 2+ instances

### Contact:
- **Email**: vojmedia@gmail.com
- **AWS Support**: [Support Console](https://console.aws.amazon.com/support/)

---

## 📝 Current Status

**HTTPS**: ❌ Disabled (for initial deployment)  
**HTTP**: ✅ Enabled (port 80)  
**Next Step**: Deploy application, then configure SSL

**To enable HTTPS**: Follow steps 1-7 above after initial deployment succeeds.

---

**Last Updated**: October 19, 2025  
**Version**: 1.0

