# 🗄️ UAT TEST DATA SETUP GUIDE

**Project:** Jebikalam Vaanga Prophecy System  
**Purpose:** Ensure proper test data for UAT execution

---

## 📋 **PRE-UAT SETUP CHECKLIST**

### **✅ 1. Server Environment**
```bash
# Start Laravel development server
php artisan serve

# Verify server running at:
http://127.0.0.1:8000
```

### **✅ 2. Database Setup**
```bash
# Run migrations
php artisan migrate

# Seed basic data
php artisan db:seed
```

### **✅ 3. Test Users Setup**

**Admin User:**
- **Email:** admin@jebikalamvaanga.com
- **Password:** Admin123!
- **Role:** Super Admin

**Regular Test User:**
- **Email:** testuser@example.com
- **Password:** TestPass123!
- **Role:** User

**Create via registration or database seeding**

---

## 🎯 **CRITICAL TEST DATA REQUIREMENTS**

### **✅ Telugu Prophecy (ID: 11)**
**Required for PDF text-to-image testing**

```sql
-- Verify Telugu content exists
SELECT * FROM prophecy_translations 
WHERE prophecy_id = 11 AND language = 'te';

-- Expected result should show:
-- Title: దేవుని వాక్యం మరియు ప్రవచనం
-- Content: Telugu content with proper Unicode
```

**If missing, run:**
```bash
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Prophecy;
use App\Models\ProphecyTranslation;

\$prophecy = Prophecy::find(11);
if (!\$prophecy) {
    echo 'Prophecy 11 not found - create basic prophecy first\n';
    exit;
}

ProphecyTranslation::updateOrCreate([
    'prophecy_id' => 11,
    'language' => 'te'
], [
    'title' => 'దేవుని వాక్యం మరియు ప్రవచనం',
    'content' => '<p>ఇది తెలుగు భాషలో ఉన్న ప్రవచనం. దేవుడు మనతో మాట్లాడుతున్నాడు మరియు మనకు దిశను చూపిస్తున్నాడు.</p><p>ప్రార్థనలో ఉండండి మరియు దేవుని వాక్యాన్ని వినండి. ఆయన మీకు మార్గదర్శకత్వం అందిస్తాడు.</p>',
    'description' => 'తెలుగు ప్రవచనం యొక్క వివరణ'
]);

echo 'Telugu content created for prophecy 11\n';
"
```

### **✅ Video URL Test Data**
**Required for video functionality testing**

```sql
-- Add video URLs to test prophecies
UPDATE prophecies 
SET video_url = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' 
WHERE id IN (1, 2, 3);

-- Verify video URLs exist
SELECT id, title, video_url FROM prophecies 
WHERE video_url IS NOT NULL;
```

### **✅ Multi-Language Content**
**Required for language switching tests**

```sql
-- Check available languages for prophecies
SELECT prophecy_id, language, title 
FROM prophecy_translations 
ORDER BY prophecy_id, language;

-- Should include: en, te, ta, kn, ml, hi
```

---

## 🧪 **TEST SCENARIOS DATA**

### **Scenario 1: Session Expiry Testing**
**Setup:**
```bash
# Reduce session lifetime for testing (optional)
# Edit config/session.php temporarily:
'lifetime' => 1, // 1 minute instead of 120

# Or manually expire session in browser dev tools
```

### **Scenario 2: 419 Error Testing**
**Setup:**
```javascript
// In browser console, expire CSRF token:
document.querySelector('meta[name="csrf-token"]').setAttribute('content', 'expired-token');

// Then try to submit any form
```

### **Scenario 3: PDF Generation Testing**
**Test URLs:**
```
English PDF: /prophecies/1/download?language=en
Telugu PDF: /test-pdf/11?language=te
Tamil PDF: /test-pdf/11?language=ta (if Tamil content exists)
```

---

## 📊 **DATA VERIFICATION COMMANDS**

### **Check Prophecies Count**
```sql
SELECT COUNT(*) as total_prophecies FROM prophecies;
-- Should have at least 10-15 prophecies
```

### **Check Translations Count**
```sql
SELECT language, COUNT(*) as count 
FROM prophecy_translations 
GROUP BY language;
-- Should show multiple languages
```

### **Check Users Count**
```sql
SELECT COUNT(*) as total_users FROM users;
-- Should have at least 2-3 test users
```

### **Check Categories**
```sql
SELECT * FROM categories;
-- Should have various prophecy categories
```

---

## 🔧 **TROUBLESHOOTING SETUP ISSUES**

### **Issue: No Telugu Content**
```bash
# Run the Telugu content creation script above
# Or manually insert via admin panel
```

### **Issue: No Video URLs**
```sql
-- Add test video URLs
UPDATE prophecies SET video_url = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' WHERE id <= 5;
```

### **Issue: No Test Users**
```bash
# Create via registration page or tinker:
php artisan tinker
User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('TestPass123!')
]);
```

### **Issue: Missing Categories**
```sql
-- Insert basic categories
INSERT INTO categories (name, description) VALUES 
('General Prophecies', 'General prophecy content'),
('End Times', 'End times prophecies'),
('Revival', 'Revival and awakening prophecies');
```

---

## ✅ **PRE-TEST VERIFICATION**

**Before starting UAT, verify:**

1. **Server Status:**
   - [ ] Laravel server running on port 8000
   - [ ] No error messages in console
   - [ ] Database connection working

2. **Test Data:**
   - [ ] Prophecy 11 has Telugu content
   - [ ] At least 3 prophecies have video URLs
   - [ ] Multiple languages available
   - [ ] Test users can login

3. **File Permissions:**
   - [ ] `storage/app/public/text_images/` writable
   - [ ] PDF generation working
   - [ ] Image generation working

4. **Browser Setup:**
   - [ ] Developer tools accessible (F12)
   - [ ] JavaScript enabled
   - [ ] Cookies enabled
   - [ ] Cache cleared

---

## 🚀 **QUICK SETUP SCRIPT**

**Run this to set up everything:**

```bash
#!/bin/bash
echo "Setting up UAT test environment..."

# Start server
php artisan serve &

# Wait for server to start
sleep 3

# Create Telugu content
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use App\Models\ProphecyTranslation;
ProphecyTranslation::updateOrCreate(['prophecy_id' => 11, 'language' => 'te'], [
    'title' => 'దేవుని వాక్యం మరియు ప్రవచనం',
    'content' => '<p>ఇది తెలుగు భాషలో ఉన్న ప్రవచనం. దేవుడు మనతో మాట్లాడుతున్నాడు మరియు మనకు దిశను చూపిస్తున్నాడు.</p>'
]);
echo 'Telugu content ready';
"

# Add video URLs
php artisan tinker --execute="
use App\Models\Prophecy;
Prophecy::whereIn('id', [1,2,3])->update(['video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ']);
echo 'Video URLs added';
"

echo "UAT environment ready!"
echo "Access at: http://127.0.0.1:8000"
```

---

**Setup Guide Version:** 1.0  
**Last Updated:** 09/10/2025
