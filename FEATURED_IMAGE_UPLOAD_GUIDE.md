# 📷 Featured Image Upload Feature

## ✅ Complete Feature Implementation

Added the ability for admins to upload and manage featured images (thumbnails) for prophecies, which are displayed on the prophecy detail page.

---

## 🎯 Feature Overview

### What It Does
- Admins can upload custom thumbnail images for each prophecy
- Images are displayed on the prophecy detail page (both Tamil and English versions use the same image by default)
- Easy to update/replace images anytime
- Live preview before saving
- Professional validation and error handling

### Where It's Used
- **Prophecy Detail Page**: Featured image displayed above language labels
- **Admin Create Form**: Upload image when creating new prophecy
- **Admin Edit Form**: View current image and upload new one to replace

---

## 🎨 User Interface

### Admin Create Prophecy Form

```
┌────────────────────────────────────────┐
│ Featured Image / Thumbnail             │
│────────────────────────────────────────│
│                                        │
│ Featured Image                         │
│ [Choose File]  No file chosen          │
│                                        │
│ Upload a featured image (JPEG, PNG,    │
│ WEBP - Max: 5MB). Recommended size:    │
│ 800x1200px (portrait). This will be    │
│ displayed on the prophecy detail page. │
│                                        │
│ [After selecting file:]                │
│                                        │
│ New Image Preview:                     │
│ ┌────────────┐                         │
│ │  [Image]   │                         │
│ │  Preview   │                         │
│ └────────────┘                         │
│ [X Remove Image]                       │
└────────────────────────────────────────┘
```

### Admin Edit Prophecy Form

```
┌────────────────────────────────────────┐
│ Featured Image / Thumbnail             │
│────────────────────────────────────────│
│                                        │
│ Current Featured Image:                │
│ ┌────────────┐                         │
│ │  Current   │                         │
│ │  Image     │                         │
│ └────────────┘                         │
│                                        │
│ Replace Featured Image                 │
│ [Choose File]  No file chosen          │
│                                        │
│ Upload a featured image...             │
│                                        │
│ [After selecting new file:]            │
│                                        │
│ New Image Preview:                     │
│ ┌────────────┐                         │
│ │  New       │                         │
│ │  Image     │                         │
│ └────────────┘                         │
│ [X Remove New Image]                   │
└────────────────────────────────────────┘
```

---

## 📝 How To Use

### Creating New Prophecy with Featured Image

1. **Go to Admin Panel** → Prophecies → Create New
2. **Fill in basic information** (Title, Date, etc.)
3. **Scroll to "Featured Image / Thumbnail" section**
4. **Click "Choose File"** button
5. **Select an image** from your computer
   - Supported: JPEG, JPG, PNG, WEBP
   - Max size: 5MB
   - Recommended: 800x1200px (portrait)
6. **Preview appears** automatically
7. **Continue filling** other fields (content, PDF, etc.)
8. **Click "Save & Continue"** or "Save Prophecy"
9. **Image is uploaded** and saved with the prophecy

### Updating Existing Prophecy Image

1. **Go to Admin Panel** → Prophecies → Select prophecy
2. **Click Edit** button
3. **Scroll to "Featured Image / Thumbnail" section**
4. **View current image** (if already uploaded)
5. **Click "Choose File"** to select new image
6. **Preview new image** appears below current
7. **Click "Save & Continue"** or "Update Prophecy"
8. **Old image is replaced** with new one

### Removing Preview (Before Saving)

- If you change your mind before saving
- Click **"Remove Image"** or **"Remove New Image"** button
- Clears the file selection
- Hides the preview
- Can select a different image

---

## ⚙️ Technical Specifications

### File Requirements
| Specification | Value |
|---------------|-------|
| **File Types** | JPEG, JPG, PNG, WEBP |
| **Max Size** | 5MB |
| **Recommended Dimensions** | 800x1200px (portrait) |
| **Aspect Ratio** | 2:3 (portrait) |
| **Storage Location** | `storage/app/public/prophecy_images/` |
| **Filename Format** | `featured_{id}_{timestamp}.{ext}` |

### Validation Rules
```php
'featured_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120'
```

- **nullable**: Optional field
- **image**: Must be an image file
- **mimes**: JPEG, JPG, PNG, or WEBP only
- **max:5120**: Maximum 5MB (5120 KB)

---

## 💾 Backend Implementation

### Controller (ProphecyController.php)

#### Store Method (Create)
```php
// Handle featured image upload
$imageData = [];
if ($request->hasFile('featured_image')) {
    $file = $request->file('featured_image');
    $filename = 'featured_' . time() . '.' . $file->getClientOriginalExtension();
    $path = $file->storeAs('prophecy_images', $filename, 'public');
    $imageData = ['featured_image' => $path];
}

$prophecy = Prophecy::create(array_merge([...], $imageData, $pdfData));
```

#### Update Method (Edit)
```php
// Handle featured image upload
$imageData = [];
if ($request->hasFile('featured_image')) {
    // Delete old image if exists
    if ($prophecy->featured_image && Storage::disk('public')->exists($prophecy->featured_image)) {
        Storage::disk('public')->delete($prophecy->featured_image);
    }
    
    $file = $request->file('featured_image');
    $filename = 'featured_' . $prophecy->id . '_' . time() . '.' . $file->getClientOriginalExtension();
    $path = $file->storeAs('prophecy_images', $filename, 'public');
    $imageData = ['featured_image' => $path];
}

$prophecy->update(array_merge([...], $imageData, $pdfData));
```

### Database
- **Column**: `featured_image` (string, nullable)
- **Table**: `prophecies`
- **Stores**: Relative path like `prophecy_images/featured_123_1234567890.jpg`

---

## 🎨 Frontend Implementation

### JavaScript Validation & Preview

```javascript
function handleImageUpload(input) {
    const file = input.files[0];
    const preview = document.getElementById('image_preview');
    const img = document.getElementById('image_preview_img');
    
    if (file) {
        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            alert('Please select a valid image file (JPEG, PNG, or WEBP).');
            input.value = '';
            preview.style.display = 'none';
            return;
        }
        
        // Validate file size (5MB max)
        if (file.size > 5 * 1024 * 1024) {
            alert('Image size must be less than 5MB.');
            input.value = '';
            preview.style.display = 'none';
            return;
        }
        
        // Show preview using FileReader
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function removeImageFile() {
    const input = document.getElementById('featured_image');
    const preview = document.getElementById('image_preview');
    
    input.value = '';
    preview.style.display = 'none';
}
```

### Usage in Prophecy Detail Page

```php
@if($prophecy->featured_image)
    <img src="{{ Storage::url($prophecy->featured_image) }}" 
         alt="{{ $prophecy->title }}"
         style="width: 100%; height: auto; border-radius: 12px;">
@else
    <!-- Fallback placeholder -->
    <div style="background: #3b82f6; display: flex; align-items: center; justify-content: center;">
        <i class="fas fa-scroll" style="font-size: 3rem; color: white;"></i>
    </div>
@endif
```

---

## 🔄 Workflow

### New Prophecy
```
Admin fills form
    ↓
Selects featured image
    ↓
JavaScript validates (type, size)
    ↓
Preview shows instantly
    ↓
Admin clicks Save
    ↓
Image uploaded to storage/app/public/prophecy_images/
    ↓
Path saved in database
    ↓
Success message
```

### Update Prophecy
```
Admin opens edit form
    ↓
Sees current featured image (if exists)
    ↓
Selects new image (optional)
    ↓
Preview of new image shows
    ↓
Admin clicks Update
    ↓
Old image deleted from storage
    ↓
New image uploaded
    ↓
Database updated with new path
    ↓
Success message
```

---

## 📂 File Structure

```
storage/
  app/
    public/
      prophecy_images/          ← Images stored here
        featured_1_1634567890.jpg
        featured_2_1634567891.png
        featured_3_1634567892.webp
        ...

public/
  storage/                      ← Symlink to storage/app/public
    prophecy_images/            ← Accessible via web
```

### Storage Symlink
```bash
php artisan storage:link
```
Creates symlink from `public/storage` → `storage/app/public`

---

## 🎯 Use Cases

### 1. **Custom Branding**
- Upload images with specific branding
- Different images for different prophecy themes
- Consistent visual identity

### 2. **Topic Visualization**
- "Revival" → Fire/worship image
- "Persecution" → Persecution scene
- "Israel" → Jerusalem/Flag image
- "Family" → Family image

### 3. **Language Variations**
- Currently: Same image for all languages
- Future: Can add translation-specific images
- Easy to extend

---

## 🚀 Deployment

### Files Changed
1. `app/Http/Controllers/Admin/ProphecyController.php`
   - Added image upload in store()
   - Added image upload with deletion in update()

2. `resources/views/admin/prophecies/create.blade.php`
   - Added Featured Image section
   - Added JavaScript handlers

3. `resources/views/admin/prophecies/edit.blade.php`
   - Added Featured Image section
   - Shows current image
   - Added JavaScript handlers

### Already Committed
```bash
git commit -m "feat: Add featured image upload functionality for prophecies"
git commit -m "feat: Add featured image upload to prophecy edit form"
```

### Deploy Steps

1. **Push to GitHub**
```bash
git push origin main
```

2. **Deploy on Production**
```bash
cd /var/www/html
git pull origin main

# Ensure storage link exists
php artisan storage:link

# Create directory if doesn't exist
mkdir -p storage/app/public/prophecy_images
chmod -R 775 storage/app/public/prophecy_images

# Clear caches
php artisan view:clear
php artisan cache:clear

# Restart services
sudo systemctl restart nginx
sudo systemctl restart php-fpm
```

---

## 🧪 Testing Checklist

### Create Form
- [ ] Featured Image section displays
- [ ] Choose File button works
- [ ] File type validation works (reject .txt, .pdf)
- [ ] File size validation works (reject >5MB)
- [ ] Preview shows immediately after selection
- [ ] Preview image displays correctly
- [ ] Remove Image button clears selection
- [ ] Form submits successfully with image
- [ ] Image saves to storage/app/public/prophecy_images/
- [ ] Database stores correct path
- [ ] Success message shows "Featured image uploaded"

### Edit Form
- [ ] Featured Image section displays
- [ ] Current image shows (if exists)
- [ ] Choose File button works
- [ ] File validation works
- [ ] New image preview shows below current
- [ ] Remove button clears new selection only
- [ ] Form updates successfully
- [ ] Old image deleted from storage
- [ ] New image uploaded
- [ ] Database path updated
- [ ] Success message shows "Featured image updated"

### Prophecy Detail Page
- [ ] Featured image displays for Tamil version
- [ ] Featured image displays for English version
- [ ] Image loads correctly
- [ ] Fallback placeholder shows if no image
- [ ] Image is properly sized and styled
- [ ] Works on mobile devices

### Edge Cases
- [ ] Works without selecting image (optional)
- [ ] Works if image already exists and no new upload
- [ ] Handles special characters in filename
- [ ] Handles very large filenames
- [ ] Multiple rapid uploads (shouldn't cause issues)

---

## 🎨 Recommended Image Specs

### For Best Results

| Property | Recommended | Why |
|----------|-------------|-----|
| **Dimensions** | 800x1200px | Standard portrait |
| **Aspect Ratio** | 2:3 | Portrait orientation |
| **File Format** | JPEG or WebP | Best compression |
| **Quality** | 80-85% | Balance size/quality |
| **Color Space** | sRGB | Web standard |
| **File Size** | 200-500KB | Fast loading |
| **Content** | Centered subject | Looks good cropped |

### Sample Image Sizes
- The sample images you provided appear to be ~800-1000px width
- Portrait orientation (taller than wide)
- Feature the speaker (Prophet Vincent) and theme graphics
- Include text overlay with prophecy title

---

## 🔮 Future Enhancements

### Possible Improvements
1. **Translation-Specific Images**
   - Different images for Tamil vs English
   - Store in `prophecy_translations` table
   - Fallback to main image if not set

2. **Image Optimization**
   - Auto-resize to recommended dimensions
   - Compress images automatically
   - Generate multiple sizes (thumbnail, medium, large)
   - Use WebP format for better compression

3. **Image Cropping**
   - In-browser crop tool
   - Ensure consistent aspect ratios
   - Preview different crop options

4. **Gallery/Library**
   - Reuse images across prophecies
   - Media library browser
   - Search and filter images

5. **Cloudflare R2 Integration**
   - Store images in R2 (like PDFs)
   - CDN delivery for faster loading
   - Offload storage from server

---

## 📊 Storage Estimates

### Per Image
- Average size: ~300-500KB
- Storage: Local disk initially
- Can move to R2/S3 later

### 100 Prophecies
- Total: ~30-50MB of images
- Negligible storage impact
- Fast local serving

### 1000 Prophecies
- Total: ~300-500MB of images
- Still manageable locally
- Consider R2 for optimization

---

## ✅ Summary

**What's Implemented:**
- ✅ Featured image upload in admin create form
- ✅ Featured image upload in admin edit form
- ✅ Live preview before saving
- ✅ File type and size validation
- ✅ Old image deletion when updating
- ✅ Image display on prophecy detail page
- ✅ Professional error handling
- ✅ Clear user instructions

**How It Works:**
1. Admin uploads image via form
2. JavaScript validates and previews
3. Image saves to storage on submit
4. Path stored in database
5. Image displayed on public page
6. Can be updated anytime

**Files Affected:**
- Controller: ProphecyController.php
- Forms: create.blade.php, edit.blade.php
- Storage: prophecy_images/ directory
- Database: prophecies.featured_image column

---

**Ready to use!** Admins can now upload custom thumbnails for all prophecies! 🎉📷

