# 🚀 Quick Deployment Guide - Year Tabs & Week Numbering

## ✅ Changes Ready to Deploy

### What's New
1. **Year tabs** on home page (defaults to current year)
2. **Month dropdown** for selected year (defaults to current month)
3. **Week numbering** field in admin (continuous series)
4. **Database field** `week_number` added to prophecies table

---

## 📋 Deployment Steps

### 1. Push to GitHub
```bash
git push origin main
```

### 2. Deploy on Production Server

```bash
# Navigate to project
cd /var/www/html

# Pull latest changes
git pull origin main

# Run migration
php artisan migrate
# When prompted, type: yes

# Clear caches
php artisan view:clear
php artisan cache:clear
php artisan config:clear

# Restart services (if needed)
sudo systemctl restart nginx
sudo systemctl restart php-fpm
```

### 3. Set Week Numbers for Existing Prophecies

**Option A: Using SQL (via phpMyAdmin or MySQL CLI)**

```sql
-- Assign week numbers based on creation order
SET @week = 0;
UPDATE prophecies 
SET week_number = (@week := @week + 1)
WHERE week_number IS NULL
ORDER BY created_at ASC;

-- Verify
SELECT id, title, week_number, created_at 
FROM prophecies 
ORDER BY week_number ASC;
```

**Option B: Using Laravel Tinker (on server)**

```bash
php artisan tinker
```

Then paste this:

```php
$prophecies = App\Models\Prophecy::whereNull('week_number')
    ->orderBy('created_at', 'asc')
    ->get();

$week = 1;
foreach ($prophecies as $prophecy) {
    $prophecy->week_number = $week++;
    $prophecy->save();
    echo "Prophecy {$prophecy->id}: Week {$prophecy->week_number}\n";
}

echo "\nTotal prophecies updated: " . ($week - 1);
exit
```

---

## 🧪 Testing After Deployment

### 1. Check Home Page
- Visit: `https://jvprophecy.vincentselvakumar.org/home`
- ✅ Year tabs visible at top
- ✅ Current year selected by default
- ✅ Month dropdown shows current month selected
- ✅ Week cards show database week numbers

### 2. Test Year Switching
- Click different year tabs
- ✅ Content switches
- ✅ Month dropdown updates

### 3. Test Month Selection
- Select different months from dropdown
- ✅ Week cards update
- ✅ Smooth scroll to content

### 4. Test Week Cards
- Click a week card
- ✅ Opens prophecy detail page
- ✅ Week number displayed correctly

### 5. Check Admin Forms
- Go to: `https://jvprophecy.vincentselvakumar.org/admin/prophecies/create`
- ✅ Week Number field is visible
- ✅ Field is required
- ✅ Validation works (min: 1)
- Try creating a prophecy
- ✅ Week number saves to database

- Go to any existing prophecy edit page
- ✅ Week number shows if set
- ✅ Can update week number

---

## 📱 Mobile Testing

### On Your Phone/Tablet
1. Visit home page
2. ✅ Year tabs are responsive (smaller size)
3. ✅ Month dropdown fits screen
4. ✅ Grid stacks vertically
5. ✅ Week cards tap-friendly
6. ✅ Smooth animations

---

## 🔧 Troubleshooting

### Issue: Migration fails
**Solution:**
```bash
php artisan migrate:status
# Check if migration already ran
# If needed, rollback last migration:
php artisan migrate:rollback --step=1
# Then re-run:
php artisan migrate
```

### Issue: Week numbers not showing
**Solution:**
- Run the SQL script or Tinker command to set week numbers
- Clear cache: `php artisan cache:clear`
- Refresh page

### Issue: Year tabs don't show
**Solution:**
- Check if any prophecies exist in database
- Clear view cache: `php artisan view:clear`
- Check browser console for JavaScript errors

### Issue: Current year/month not selected
**Solution:**
- This uses server time, check server timezone
- Verify in `.env`: `APP_TIMEZONE=Asia/Kolkata`
- Restart PHP-FPM: `sudo systemctl restart php-fpm`

---

## 📊 Expected Result

### Home Page Structure

```
┌──────────────────────────────────────────┐
│  Select Jebikalaam Vanga Prophecy        │
└──────────────────────────────────────────┘

┌──────────┬──────────┬──────────┐
│   2023   │   2024   │   2025   │  ← Year Tabs
└──────────┴──────────┴──────────┘
      ↑ Current year selected

┌──────────────────────────────────────────┐
│  October ▼  (3 prophecies)               │  ← Month Dropdown
└──────────────────────────────────────────┘
      ↑ Current month selected

┌──────────────┬────────────────────────────┐
│ October 2025 │ [Week 5]   [Week 6]        │
│              │ 19th Oct   26th Oct        │
└──────────────┴────────────────────────────┘
      ↑ Month card        ↑ Week numbers from DB
```

---

## ✅ Deployment Checklist

- [ ] Pushed code to GitHub
- [ ] Pulled latest code on production server
- [ ] Ran `php artisan migrate` successfully
- [ ] Set week numbers for existing prophecies (SQL or Tinker)
- [ ] Cleared all caches
- [ ] Tested home page (year tabs visible)
- [ ] Tested year switching
- [ ] Tested month selection
- [ ] Tested week cards (clickable)
- [ ] Tested on mobile device
- [ ] Checked admin create form (week number field)
- [ ] Checked admin edit form (week number field)
- [ ] Created test prophecy with week number
- [ ] Verified week number displays on home page

---

## 🎉 Success!

If all checks pass:
- ✅ Year tabs working
- ✅ Month selection working
- ✅ Week numbering system active
- ✅ Admin can set week numbers
- ✅ Responsive on all devices

**You're all set!** 🚀

---

## 📞 Need Help?

If something doesn't work:
1. Check the terminal output for errors
2. Check browser console (F12)
3. Check Laravel log: `tail -f storage/logs/laravel.log`
4. Verify database has `week_number` column: 
   ```sql
   DESCRIBE prophecies;
   ```

---

**Deployment Time:** ~5 minutes
**Next Prophecy:** When creating, just enter next week number in sequence!

