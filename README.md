# JV Prophecy Manager

A comprehensive web application for managing and distributing daily prophecy content with multilingual support, user management, and PDF generation capabilities.

[![PHP](https://img.shields.io/badge/PHP-8.2-blue.svg)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![License](https://img.shields.io/badge/License-Proprietary-yellow.svg)]()

---

## 📋 Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Deployment](#deployment)
- [Usage](#usage)
- [Security](#security)
- [Documentation](#documentation)
- [Support](#support)

---

## ✨ Features

### Core Functionality
- **Prophecy Management**: Create, edit, and manage daily prophecy content
- **Multilingual Support**: Tamil and English translations
- **PDF Generation**: Automatic PDF generation with custom styling
- **User Authentication**: Secure login and registration system
- **Role-Based Access Control**: Admin, Editor, and User roles
- **Search Functionality**: Full-text search across prophecies
- **Date-Based Navigation**: Browse prophecies by date

### Advanced Features
- **Book-Style Reading Experience**: Beautiful, Kindle-like reading interface with multiple color themes
- **PDF Download**: Direct download of prophecy PDFs
- **Print Optimization**: Printer-friendly layouts
- **Security Logging**: Comprehensive security audit trail
- **Email Verification**: User email verification system
- **Password Management**: Secure password reset and change functionality

### Admin Features
- **Dashboard**: Real-time statistics and analytics
- **Category Management**: Organize prophecies by category
- **User Management**: Manage users and roles
- **Content Moderation**: Review and approve content
- **Security Monitoring**: View security logs and alerts
- **System Status**: Monitor application health

---

## 🔧 Requirements

### Server Requirements
- PHP 8.2 or higher
- MySQL 8.0 or higher
- Composer 2.x
- Node.js 18.x (for asset compilation)
- Nginx or Apache web server

### PHP Extensions
- BCMath
- Ctype
- cURL
- DOM
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML
- GD or Imagick (for image processing)
- Intl (for internationalization)

---

## 🚀 Installation

### Local Development Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/YOUR_USERNAME/VSK-JV-Prophecy.git
   cd VSK-JV-Prophecy
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   
   Edit `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=jvprophecy_db
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations**
   ```bash
   php artisan migrate --seed
   ```

7. **Create storage symlink**
   ```bash
   php artisan storage:link
   ```

8. **Compile assets**
   ```bash
   npm run build
   ```

9. **Start development server**
   ```bash
   php artisan serve
   ```

10. **Access the application**
    - URL: http://localhost:8000
    - Admin Login: superadmin@jvprophecy.com / SuperAdmin@2025

---

## ⚙️ Configuration

### Environment Variables

Key environment variables to configure:

```env
# Application
APP_NAME="JV Prophecy Manager"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=jvprophecy_db
DB_USERNAME=your-username
DB_PASSWORD=your-password

# Mail (for email verification)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@jvprophecy.com
MAIL_FROM_NAME="${APP_NAME}"

# AWS S3 (optional, for file storage)
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=ap-south-1
AWS_BUCKET=your-bucket-name

# reCAPTCHA (optional, for registration)
RECAPTCHA_SITE_KEY=your-site-key
RECAPTCHA_SECRET_KEY=your-secret-key
```

### File Permissions

```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

---

## 🌐 Deployment

### AWS Elastic Beanstalk

Complete AWS deployment guides are provided:

1. **Quick Start**: See `AWS_QUICKSTART.md` (30 minutes)
2. **Complete Manual**: See `AWS_DEPLOYMENT_MANUAL.md`
3. **Deployment Checklist**: See `DEPLOYMENT_CHECKLIST.md`

**Quick Deploy**:
```bash
# Create deployment package
powershell -ExecutionPolicy Bypass -File create-package.ps1

# Deploy to AWS
eb deploy jv-prophecy-prod
```

### Traditional Hosting

1. Upload files to server
2. Point document root to `/public`
3. Configure `.env` file
4. Run migrations: `php artisan migrate --force`
5. Optimize: `php artisan optimize`

---

## 📖 Usage

### Admin Access

1. Navigate to `/login`
2. Login with superadmin credentials
3. Access admin dashboard at `/admin/dashboard`

### Creating Prophecies

1. Go to **Admin** → **Prophecies** → **Create**
2. Enter prophecy details
3. Add translations (Tamil/English)
4. Upload PDF file (optional)
5. Set category and date
6. Save and publish

### User Management

1. Go to **Admin** → **Users**
2. Create/edit users
3. Assign roles: Admin, Editor, or User
4. Manage permissions

### Categories

1. Go to **Admin** → **Categories**
2. Create hierarchical categories
3. Organize prophecies

---

## 🔒 Security

### Security Features

- **Password Hashing**: Bcrypt with high cost factor
- **CSRF Protection**: Laravel's built-in CSRF protection
- **SQL Injection Prevention**: Eloquent ORM with prepared statements
- **XSS Protection**: Blade templating automatic escaping
- **Security Logging**: Comprehensive audit trail
- **Session Management**: Secure session handling
- **Email Verification**: Required for new users
- **Role-Based Access Control**: Fine-grained permissions

### Security Best Practices

1. **Never commit `.env` file** to version control
2. **Use strong passwords** for all accounts
3. **Enable HTTPS** in production
4. **Regular backups** of database
5. **Keep dependencies updated**: `composer update`
6. **Monitor security logs** regularly
7. **Enable OPcache** in production
8. **Disable debug mode** in production

### Updating Passwords

**Superadmin password** (via SQL):
```sql
-- See update_superadmin_password.sql
UPDATE users 
SET password = '$2y$12$YOUR_HASHED_PASSWORD'
WHERE email = 'superadmin@jvprophecy.com';
```

**User password** (via web interface):
- Navigate to `/change-password`
- Enter current and new password
- Submit form

---

## 📚 Documentation

Comprehensive documentation is available:

### General
- `README.md` - This file
- `CHANGELOG.md` - Version history (if available)

### Deployment
- `AWS_DEPLOYMENT_MANUAL.md` - Complete AWS guide (665 lines)
- `AWS_QUICKSTART.md` - Quick start guide (30 minutes)
- `DEPLOYMENT_CHECKLIST.md` - Pre-deployment checklist
- `DEPLOYMENT_ISSUES_RESOLVED.md` - Common issues and fixes
- `SSL_SETUP_GUIDE.md` - SSL certificate setup

### Development
- `PORTAL_AUDIT_REPORT.md` - Portal audit findings
- `PASSWORD_MANAGEMENT_README.md` - Password management features
- `DEPLOYMENT_PACKAGE_README.md` - Package creation guide

### Quick References
- `QUICK_DEPLOY_GUIDE.md` - Fast deployment reference
- `DEPLOY_NOW.md` - Immediate deployment steps

---

## 🏗️ Tech Stack

### Backend
- **Framework**: Laravel 11.x
- **PHP**: 8.2
- **Database**: MySQL 8.0
- **ORM**: Eloquent
- **Authentication**: Laravel Breeze

### Frontend
- **Template Engine**: Blade
- **CSS**: Tailwind CSS + Custom Intel Corporate theme
- **JavaScript**: Vanilla JS + Alpine.js
- **Icons**: Font Awesome 6

### Tools & Libraries
- **PDF Generation**: DomPDF
- **Image Processing**: Intervention Image
- **Text-to-Image**: Custom TextToImageService
- **Security**: Custom SecurityLog system
- **Queue**: Laravel Queue

---

## 📁 Project Structure

```
VSK-JV-Prophecy/
├── app/
│   ├── Console/          # Artisan commands
│   ├── Http/
│   │   ├── Controllers/  # MVC controllers
│   │   └── Middleware/   # Custom middleware
│   ├── Models/           # Eloquent models
│   ├── Notifications/    # Email notifications
│   ├── Providers/        # Service providers
│   └── Services/         # Business logic services
├── bootstrap/            # Bootstrap files
├── config/              # Configuration files
├── database/
│   ├── migrations/      # Database migrations
│   └── seeders/         # Database seeders
├── public/              # Web root
│   ├── assets/          # Public assets
│   └── index.php        # Entry point
├── resources/
│   ├── css/             # Stylesheets
│   ├── js/              # JavaScript
│   └── views/           # Blade templates
├── routes/              # Route definitions
├── storage/             # File storage
│   ├── app/             # Application storage
│   ├── framework/       # Framework storage
│   └── logs/            # Log files
├── .ebextensions/       # AWS EB configuration
├── .platform/           # AWS platform hooks
└── vendor/              # Composer dependencies
```

---

## 🧪 Testing

```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter=TestName

# Generate coverage report
php artisan test --coverage
```

---

## 🤝 Contributing

This is a private project. For internal contributions:

1. Create a feature branch
2. Make your changes
3. Test thoroughly
4. Submit for review
5. Merge to main after approval

---

## 📝 Changelog

### Version 1.0.2 (October 2025)
- ✅ Fixed AWS deployment configuration for AL2023
- ✅ Disabled HTTPS for initial deployment
- ✅ Optimized deployment package (76% size reduction)
- ✅ Added comprehensive documentation
- ✅ Fixed PHP configuration for modern platform

### Version 1.0.1 (October 2025)
- ✅ Added change password functionality
- ✅ Enhanced admin dashboard with real-time data
- ✅ Implemented book-style reading interface
- ✅ Added multiple color themes
- ✅ Fixed PDF download issues
- ✅ Comprehensive portal audit completed

### Version 1.0.0 (September 2025)
- 🎉 Initial release
- ✅ Core prophecy management
- ✅ User authentication
- ✅ Admin dashboard
- ✅ PDF generation

---

## 📞 Support

### Getting Help
- **Documentation**: Check the docs in the repository
- **Email**: vojmedia@gmail.com
- **Issues**: For bugs, create detailed issue reports

### Reporting Issues
Include:
1. Environment details (PHP version, OS, etc.)
2. Steps to reproduce
3. Expected vs actual behavior
4. Error messages/logs
5. Screenshots (if applicable)

---

## 📜 License

This project is proprietary software. All rights reserved.

**Developed by**: VSK Development Team  
**Contact**: vojmedia@gmail.com  
**Website**: https://jvprophecy.vincentselvakumar.org

---

## 🙏 Acknowledgments

- Laravel Framework
- Tailwind CSS
- Font Awesome
- DomPDF
- All open-source contributors

---

## 🔗 Links

- **Production**: https://jvprophecy.vincentselvakumar.org
- **Staging**: (Configure as needed)
- **Repository**: (This repository)

---

**Last Updated**: October 19, 2025  
**Version**: 1.0.2-stable  
**Status**: Production Ready ✅
