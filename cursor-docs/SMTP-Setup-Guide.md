# 📧 SMTP SETUP GUIDE - Email Verification

**Project:** Jebikalam Vaanga Prophecy System  
**Purpose:** Configure SMTP for email verification system

---

## ⚡ **QUICK SETUP**

### **✅ 1. Environment Configuration**
Add these settings to your `.env` file:

```env
# Email Configuration - Mailtrap SMTP
MAIL_MAILER=smtp
MAIL_HOST=live.smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=api
MAIL_PASSWORD=1d8853fd1016dc9e8c5af50295250a1f
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@jebikalamvaanga.com"
MAIL_FROM_NAME="Jebikalam Vaanga Prophecy"

# Application Settings
APP_NAME="Jebikalam Vaanga Prophecy"
APP_URL=http://127.0.0.1:8000
```

### **✅ 2. Test Email Configuration**
```bash
# Test SMTP connection
php artisan tinker
Mail::raw('Test email', function($msg) { 
    $msg->to('test@example.com')->subject('Test'); 
});
```

---

## 🔧 **DETAILED CONFIGURATION**

### **✅ Mailtrap Settings Explained**

| Setting | Value | Purpose |
|---------|-------|---------|
| **MAIL_MAILER** | `smtp` | Use SMTP protocol |
| **MAIL_HOST** | `live.smtp.mailtrap.io` | Mailtrap live server |
| **MAIL_PORT** | `587` | Standard SMTP port with TLS |
| **MAIL_USERNAME** | `api` | Mailtrap API username |
| **MAIL_PASSWORD** | `1d8853fd1016dc9e8c5af50295250a1f` | Your API token |
| **MAIL_ENCRYPTION** | `tls` | Secure connection |
| **MAIL_FROM_ADDRESS** | `noreply@jebikalamvaanga.com` | Professional sender |
| **MAIL_FROM_NAME** | `Jebikalam Vaanga Prophecy` | Branded sender name |

### **✅ Email Templates**
The system sends professional emails with:
- **Subject:** "Verify Your Email Address - Jebikalam Vaanga Prophecy"
- **Content:** Welcome message + 6-digit code + verification link
- **Branding:** Intel corporate styling
- **Security:** Signed URLs with expiration

---

## 🧪 **TESTING EMAIL SYSTEM**

### **✅ Test Registration Flow**
1. **Register New User:**
   ```
   URL: http://127.0.0.1:8000/register
   Fill form and submit
   ```

2. **Check Email Sent:**
   - Check Mailtrap inbox
   - Verify email received
   - Check 6-digit code present
   - Verify link works

3. **Test Verification:**
   ```
   Method 1: Click email link
   Method 2: Enter 6-digit code at verification page
   ```

### **✅ Test Commands**
```bash
# Test basic email sending
php artisan tinker --execute="
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
\$user = User::first();
if(\$user) {
    \$user->generateEmailVerification();
    \$user->notify(new EmailVerificationNotification(\$user));
    echo 'Verification email sent to: ' . \$user->email;
}
"

# Check email configuration
php artisan tinker --execute="
echo 'Mail Driver: ' . config('mail.default') . PHP_EOL;
echo 'SMTP Host: ' . config('mail.mailers.smtp.host') . PHP_EOL;
echo 'From Address: ' . config('mail.from.address') . PHP_EOL;
"
```

---

## 🚨 **TROUBLESHOOTING**

### **❌ Common Issues & Solutions**

**Issue: "Connection refused"**
```
Solution: Check SMTP credentials and host
- Verify MAIL_HOST=live.smtp.mailtrap.io
- Verify MAIL_PORT=587
- Check API token is correct
```

**Issue: "Authentication failed"**
```
Solution: Check credentials
- Verify MAIL_USERNAME=api
- Verify MAIL_PASSWORD matches your Mailtrap token
- Check token hasn't expired
```

**Issue: "Email not received"**
```
Solution: Check Mailtrap inbox
- Login to Mailtrap dashboard
- Check inbox for test emails
- Verify sending domain
```

**Issue: "Verification link not working"**
```
Solution: Check APP_URL
- Ensure APP_URL matches your domain
- Check signed URL generation
- Verify routes are registered
```

### **✅ Debug Commands**
```bash
# Check mail configuration
php artisan config:show mail

# Test queue processing (if using queues)
php artisan queue:work --verbose

# Check logs for email errors
tail -f storage/logs/laravel.log | grep -i mail
```

---

## 🔒 **SECURITY CONSIDERATIONS**

### **✅ Production Setup**
1. **Use Environment Variables:**
   - Never commit SMTP credentials to code
   - Use secure .env file management
   - Rotate API tokens regularly

2. **Email Security:**
   - Use TLS encryption (already configured)
   - Implement rate limiting for verification emails
   - Monitor for abuse

3. **Domain Configuration:**
   - Set up proper SPF records
   - Configure DKIM if using custom domain
   - Use professional from address

### **✅ Rate Limiting**
```php
// Add to RouteServiceProvider or middleware
Route::middleware(['throttle:5,1'])->group(function () {
    Route::post('/email/resend', [EmailVerificationController::class, 'resend']);
});
```

---

## 📊 **MONITORING & ANALYTICS**

### **✅ Email Metrics to Track**
- **Delivery Rate:** Emails successfully sent
- **Open Rate:** Users opening verification emails
- **Click Rate:** Users clicking verification links
- **Verification Rate:** Users completing verification
- **Resend Frequency:** How often users request resends

### **✅ Mailtrap Dashboard**
Access your Mailtrap dashboard to monitor:
- **Email Volume:** Daily/monthly sending stats
- **Delivery Status:** Success/failure rates
- **Bounce Handling:** Invalid email addresses
- **API Usage:** Token usage and limits

---

## ✅ **VERIFICATION CHECKLIST**

**Before Going Live:**
- [ ] SMTP credentials configured correctly
- [ ] Test email sending works
- [ ] Registration flow sends verification email
- [ ] Email links work correctly
- [ ] 6-digit codes work correctly
- [ ] Resend functionality works
- [ ] Login protection active for unverified users
- [ ] Professional email template displays correctly
- [ ] Queue processing configured (if using queues)
- [ ] Rate limiting configured
- [ ] Monitoring set up

**Production Readiness:**
- [ ] Environment variables secured
- [ ] API token access restricted
- [ ] Email delivery monitoring active
- [ ] Backup SMTP provider configured (optional)
- [ ] Documentation updated
- [ ] Team trained on email system

---

**Setup Guide Version:** 1.0  
**Last Updated:** 09/10/2025  
**SMTP Provider:** Mailtrap Live
