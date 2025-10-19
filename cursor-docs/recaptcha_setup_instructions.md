# reCAPTCHA v3 Setup Instructions

## Quick Setup Guide

### Step 1: Get reCAPTCHA Keys from Google

1. Visit [Google reCAPTCHA Console](https://www.google.com/recaptcha/admin)
2. Click "Create" to add a new site
3. Fill in the form:
   - **Label**: Your application name (e.g., "Jebikalam Vaanga Prophecy")
   - **reCAPTCHA type**: Select "reCAPTCHA v3"
   - **Domains**: Add your domain(s):
     - For development: `localhost`, `127.0.0.1`
     - For production: `yourdomain.com`, `www.yourdomain.com`
   - **Accept the reCAPTCHA Terms of Service**
4. Click "Submit"
5. Copy the **Site Key** and **Secret Key**

### Step 2: Configure Environment Variables

Add the following variables to your `.env` file:

```env
# reCAPTCHA v3 Configuration
RECAPTCHA_SITE_KEY=your_site_key_from_google_console
RECAPTCHA_SECRET_KEY=your_secret_key_from_google_console
RECAPTCHA_ENABLED=true
RECAPTCHA_MIN_SCORE=0.5
```

**Important Notes:**
- Replace `your_site_key_from_google_console` with the actual Site Key from Google
- Replace `your_secret_key_from_google_console` with the actual Secret Key from Google
- Keep the Secret Key confidential and never expose it in frontend code

### Step 3: Test the Implementation

1. **Clear application cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Test login form:**
   - Navigate to `/login`
   - Fill in credentials and submit
   - Check that reCAPTCHA verification happens seamlessly

3. **Test registration form:**
   - Navigate to `/register`
   - Fill in registration details and submit
   - Verify reCAPTCHA protection is active

### Step 4: Monitor and Adjust

1. **Check logs for verification attempts:**
   ```bash
   tail -f storage/logs/laravel.log | grep reCAPTCHA
   ```

2. **Monitor reCAPTCHA console:**
   - Visit Google reCAPTCHA console to see usage statistics
   - Review score distributions
   - Check for any errors or issues

## Configuration Options

### Minimum Score Adjustment

The `RECAPTCHA_MIN_SCORE` setting determines how strict the bot detection is:

- **0.9**: Very strict (may block some legitimate users)
- **0.7**: Strict (good for high-security applications)
- **0.5**: Balanced (recommended default)
- **0.3**: Lenient (allows more users through)
- **0.1**: Very lenient (minimal bot protection)

### Disabling reCAPTCHA

For development or testing, you can disable reCAPTCHA:

```env
RECAPTCHA_ENABLED=false
```

When disabled:
- Forms will work normally without reCAPTCHA verification
- No external API calls to Google
- Useful for local development and testing

## Troubleshooting

### Common Issues

1. **"Please complete the reCAPTCHA verification" error:**
   - Check that Site Key is correctly set in `.env`
   - Verify domain is added to reCAPTCHA console
   - Check browser console for JavaScript errors

2. **"Security verification failed" error:**
   - Verify Secret Key is correctly set in `.env`
   - Check that domain matches reCAPTCHA console settings
   - Review application logs for detailed error information

3. **reCAPTCHA not loading:**
   - Check internet connection
   - Verify Site Key is valid
   - Check browser console for network errors

### Debug Steps

1. **Enable debug logging:**
   ```env
   LOG_LEVEL=debug
   ```

2. **Check configuration:**
   ```bash
   php artisan config:show services.recaptcha
   ```

3. **Test with reCAPTCHA disabled:**
   ```env
   RECAPTCHA_ENABLED=false
   ```

4. **Review logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

## Security Best Practices

### Production Deployment

1. **Use production keys:**
   - Never use test keys in production
   - Generate separate keys for each environment

2. **Secure secret key:**
   - Store in environment variables only
   - Never commit to version control
   - Restrict access to production servers

3. **Monitor usage:**
   - Set up alerts for unusual patterns
   - Review reCAPTCHA console regularly
   - Monitor application logs for failed verifications

### Domain Security

1. **Restrict domains:**
   - Only add necessary domains to reCAPTCHA console
   - Remove development domains from production keys
   - Use separate keys for different environments

2. **HTTPS enforcement:**
   - Always use HTTPS in production
   - reCAPTCHA works better with secure connections

## Support Resources

- [Google reCAPTCHA Documentation](https://developers.google.com/recaptcha/docs/v3)
- [reCAPTCHA Console](https://www.google.com/recaptcha/admin)
- Application logs: `storage/logs/laravel.log`
- Implementation documentation: `cursor-docs/recaptcha_v3_implementation.md`

## Version Information

- **Setup Date**: September 13, 2025
- **reCAPTCHA Version**: v3
- **Implementation Version**: 1.0.0.0 Build 00004
