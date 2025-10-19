# 🚀 UAT QUICK REFERENCE GUIDE

**Project:** Jebikalam Vaanga Prophecy System  
**Quick Testing Guide for User Acceptance Testing**

---

## ⚡ **CRITICAL TESTS (Must Pass)**

### **🔥 Priority 1: Core Functionality**

| Test | URL/Action | Expected Result | ✅/❌ |
|------|------------|----------------|-------|
| **Login** | `/login` → Enter credentials | Successful login, redirect to home | ⬜ |
| **Home Page** | `/home` | "Jebikalam Vaanga Prophecy", praying hands icon, month selector | ⬜ |
| **Telugu PDF** | `/test-pdf/11?language=te` | Telugu text as images (NO boxes □□□) | ⬜ |
| **419 Error** | Expire session → submit form | Custom 419 page with countdown → redirect home | ⬜ |
| **Video Button** | Prophecy with video → "Watch Video" | YouTube red button, modal opens, video plays | ⬜ |

---

## 🧪 **QUICK TEST SCENARIOS**

### **Scenario 1: New User Journey (5 minutes)**
```
1. Go to /register → Create account
2. Login → Verify redirect to home
3. Click month selector → Choose different month
4. Click date card → View prophecy
5. Click "Download PDF" → Verify download
```

### **Scenario 2: Telugu PDF Test (2 minutes)**
```
1. Go to: http://127.0.0.1:8000/test-pdf/11?language=te
2. Download PDF
3. Open PDF → Check for:
   ✅ Telugu title as clear image
   ✅ Telugu content as clear image  
   ❌ NO boxes (□□□) anywhere
```

### **Scenario 3: Session Expiry Test (3 minutes)**
```
1. Login to application
2. Wait 2+ hours OR manually expire session
3. Try to submit a form or navigate
4. Verify: Custom 419 page → 5-second countdown → redirect home
```

### **Scenario 4: Video Functionality (2 minutes)**
```
1. Find prophecy with video URL
2. Click "Watch Video" button (should be YouTube red)
3. Verify: Modal opens → Video plays automatically
4. Close modal → Verify it closes properly
```

---

## 📱 **DEVICE TESTING MATRIX**

| Device Type | Screen Size | Browser | Status |
|-------------|-------------|---------|--------|
| Desktop | 1920x1080 | Chrome | ⬜ |
| Desktop | 1920x1080 | Firefox | ⬜ |
| Tablet | 768x1024 | Safari | ⬜ |
| Mobile | 375x667 | Chrome Mobile | ⬜ |

---

## 🎯 **PASS/FAIL CRITERIA**

### **✅ PASS Requirements:**
- All critical tests pass
- Telugu PDF shows images (no boxes)
- 419 error shows custom page
- Home page has correct branding
- Video functionality works

### **❌ FAIL Conditions:**
- Telugu PDF shows boxes (□□□)
- 419 error shows generic Laravel page
- Login/registration broken
- Home page missing key elements
- Critical JavaScript errors

---

## 🔧 **COMMON ISSUES & FIXES**

### **Issue: Telugu PDF Shows Boxes**
**Fix:** Check text-to-image service, verify base64 encoding

### **Issue: 419 Shows Generic Error**
**Fix:** Verify custom exception handler and 419.blade.php

### **Issue: Video Button Missing**
**Fix:** Check prophecy has video_url in database

### **Issue: Home Page Wrong Branding**
**Fix:** Verify "Jebikalam Vaanga Prophecy" in layouts

---

## 📋 **QUICK CHECKLIST**

**Before Testing:**
- [ ] Server running on `http://127.0.0.1:8000`
- [ ] Database has test data
- [ ] Telugu content exists for prophecy ID 11
- [ ] Browser developer tools ready

**During Testing:**
- [ ] Test on multiple browsers
- [ ] Check mobile responsiveness
- [ ] Verify all downloads work
- [ ] Test session expiry scenarios
- [ ] Check for JavaScript errors

**After Testing:**
- [ ] Document all issues found
- [ ] Categorize by priority (Critical/Medium/Minor)
- [ ] Provide screenshots for visual issues
- [ ] Sign off on test completion

---

## 🚨 **EMERGENCY CONTACTS**

**If Critical Issues Found:**
- Stop testing immediately
- Document the exact steps to reproduce
- Take screenshots/screen recordings
- Report to development team

**Test Environment Issues:**
- Check server is running
- Verify database connection
- Clear browser cache
- Try different browser

---

**Quick Reference Version:** 1.0  
**Last Updated:** 09/10/2025
