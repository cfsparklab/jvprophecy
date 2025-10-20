# 📅 Year Tabs, Month Selection & Week Numbering System

## ✅ Complete Implementation

### 🎯 Features Implemented

1. **Year Tabs**
   - Display available years as tabs
   - Default to current year
   - Click to switch between years
   - Active state styling (blue-gray background)
   - Hover effects for non-active tabs
   - Keyboard navigation support

2. **Month Selection**
   - Dropdown selector for months within selected year
   - Default to current month
   - Shows prophecy count per month
   - Smooth scroll to selected month content
   - Responsive styling

3. **Continuous Week Numbering**
   - Week numbers stored in database (`week_number` field)
   - **Global sequence across all prophecies**
   - 1st prophecy = Week 1, 2nd = Week 2, etc.
   - Not unique per month (continuous series)
   - Displayed in week cards

4. **Admin Forms**
   - Week Number field in create form
   - Week Number field in edit form
   - Required validation (min: 1)
   - Help text for guidance
   - Number input with increment/decrement

---

## 🗄️ Database Changes

### Migration: `add_week_number_to_prophecies_table`

```php
Schema::table('prophecies', function (Blueprint $table) {
    $table->integer('week_number')
          ->nullable()
          ->after('jebikalam_vanga_date')
          ->comment('Continuous week number (global sequence)');
    $table->index('week_number');
});
```

### Updated Model: `app/Models/Prophecy.php`

```php
protected $fillable = [
    'title',
    'description',
    'jebikalam_vanga_date',
    'week_number',  // ← Added this field
    // ... other fields
];
```

---

## 🎨 Home Page Design

### Layout Structure

```
┌─────────────────────────────────────────────────┐
│           Select Jebikalaam Vanga Prophecy      │
└─────────────────────────────────────────────────┘

┌─────────┬─────────┬─────────┐
│  2023   │  2024   │  2025   │  ← Year Tabs
└─────────┴─────────┴─────────┘

┌─────────────────────────────────────────────────┐
│  Select a Month ▼  (July - 3 prophecies)        │  ← Month Dropdown
└─────────────────────────────────────────────────┘

┌─────────────────┬─────────────────────────────┐
│   July 2025     │  [Week 1]    [Week 2]       │
│                 │  20th Jul    27th Jul       │
└─────────────────┴─────────────────────────────┘
```

### Color Scheme

| Element | Color | Usage |
|---------|-------|-------|
| **Active Year Tab** | `#456983` | Blue-gray background |
| **Inactive Year Tab** | `#64748b` | Gray text |
| **Month Card** | `#456983` | Blue-gray background |
| **Week Card** | `#cd7f32` | Orange/copper background |
| **Week Card (Hover)** | `#b36b28` | Darker orange |
| **Locked Week** | `#9ca3af` | Gray for non-auth users |

---

## 📝 Admin Forms

### Create Prophecy Form

```html
<!-- Week Number Field -->
<div class="intel-form-group">
    <label for="week_number" class="intel-form-label">
        Week Number <span style="color: var(--error-color);">*</span>
    </label>
    <input type="number" 
           id="week_number" 
           name="week_number" 
           required
           min="1"
           value="{{ old('week_number') }}"
           class="intel-form-input"
           placeholder="Enter week number (e.g., 1, 2, 3...)">
    <p class="intel-form-help">
        Continuous week number across all prophecies 
        (1st prophecy = Week 1, 2nd = Week 2, etc.)
    </p>
</div>
```

### Edit Prophecy Form

Same as create form, but uses `$prophecy->week_number` for the value.

---

## 🔧 Controller Logic

### `app/Http/Controllers/PublicController.php`

**Updated `index()` method:**

```php
public function index()
{
    // Fetch prophecies with week_number
    $prophecies = Prophecy::published()
        ->public()
        ->select('id', 'jebikalam_vanga_date', 'week_number')
        ->whereNotNull('jebikalam_vanga_date')
        ->orderBy('jebikalam_vanga_date', 'desc')
        ->get();

    // Group by year, then by month
    $groupedByYear = $prophecies->groupBy(function($prophecy) {
        return $prophecy->jebikalam_vanga_date->format('Y');
    })->map(function($yearProphecies, $year) {
        $groupedByMonth = $yearProphecies->groupBy(function($prophecy) {
            return $prophecy->jebikalam_vanga_date->format('Y-m');
        })->map(function($monthProphecies, $monthKey) {
            return [
                'month_key' => $monthKey,
                'month_name' => $firstDate->format('F Y'),
                'month_short' => $firstDate->format('F'),
                'dates' => $monthProphecies->map(function($prophecy) {
                    return [
                        'prophecy_id' => $prophecy->id,
                        'week_number' => $prophecy->week_number ?? 0,
                        'jebikalam_vanga_date' => $prophecy->jebikalam_vanga_date->format('Y-m-d'),
                        // ...
                    ];
                })
            ];
        });
        
        return [
            'year' => $year,
            'months' => $groupedByMonth->values()
        ];
    })->values();

    $currentYear = now()->format('Y');
    $currentMonth = now()->format('Y-m');
    
    return view('public.index', compact('groupedByYear', 'currentYear', 'currentMonth'));
}
```

---

## 💻 JavaScript Functions

### Year Tab Switching

```javascript
function showYear(year, element) {
    // Hide all year contents
    document.querySelectorAll('.year-content').forEach(content => {
        content.style.display = 'none';
    });
    
    // Show selected year content
    document.getElementById('year-' + year).style.display = 'block';
    
    // Update active tab styling
    document.querySelectorAll('.year-tab').forEach(tab => {
        tab.style.background = 'transparent';
        tab.style.color = '#64748b';
    });
    
    element.style.background = '#456983';
    element.style.color = 'white';
}
```

### Month Selection

```javascript
function showMonth(year, monthKey) {
    const yearContainer = document.getElementById('year-' + year);
    
    // Hide all month contents
    yearContainer.querySelectorAll('.month-content').forEach(content => {
        content.style.display = 'none';
    });
    
    // Show selected month
    if (monthKey) {
        const selectedMonth = document.getElementById('month-' + monthKey);
        selectedMonth.style.display = 'block';
        
        // Smooth scroll
        selectedMonth.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}
```

---

## 📱 Responsive Design

### Tablet (≤768px)
- Year tabs: Smaller padding (0.625rem 1.5rem)
- Month selector: Min-width 250px
- Grid stacks vertically
- Week cards: 140px min-width

### Mobile (≤480px)
- Year tabs: Even smaller (0.5rem 1.25rem)
- Month selector: Min-width 200px
- Week cards: 120px min-width
- Title font size: 1.5rem

---

## 🚀 Deployment Steps

### 1. Push to GitHub

```bash
git push origin main
```

### 2. Deploy on Production

```bash
cd /var/www/html
git pull origin main

# Run migration
php artisan migrate

# Clear caches
php artisan view:clear
php artisan cache:clear
php artisan config:clear

# Restart services (if needed)
sudo systemctl restart nginx
sudo systemctl restart php-fpm
```

### 3. Update Existing Prophecies

**Run this SQL to set week numbers for existing prophecies:**

```sql
-- Assign week numbers based on creation order
SET @week = 0;
UPDATE prophecies 
SET week_number = (@week := @week + 1)
WHERE week_number IS NULL
ORDER BY created_at ASC;
```

Or use Laravel Tinker:

```php
php artisan tinker

$prophecies = App\Models\Prophecy::whereNull('week_number')
    ->orderBy('created_at', 'asc')
    ->get();

$week = 1;
foreach ($prophecies as $prophecy) {
    $prophecy->week_number = $week++;
    $prophecy->save();
}
```

---

## ✅ Testing Checklist

### Desktop Testing
- [ ] Year tabs display correctly
- [ ] Current year is selected by default
- [ ] Clicking year tab switches content
- [ ] Month dropdown shows correct months
- [ ] Current month is selected by default
- [ ] Selecting month shows correct prophecies
- [ ] Week numbers display from database
- [ ] Week cards are clickable
- [ ] Hover effects work

### Mobile Testing
- [ ] Year tabs responsive (smaller size)
- [ ] Month dropdown fits screen
- [ ] Grid layout stacks vertically
- [ ] Week cards sized appropriately
- [ ] Touch interactions work smoothly

### Admin Testing
- [ ] Create form has week number field
- [ ] Field is required
- [ ] Min value validation works (>= 1)
- [ ] Help text is visible
- [ ] Edit form shows existing week number
- [ ] Can update week number
- [ ] Validation errors display correctly

---

## 📊 Week Numbering Logic

### Concept
- **Continuous series** across ALL prophecies
- **NOT** unique per month
- **NOT** reset per year
- Based on creation order or admin input

### Example

| Prophecy ID | Date | Week Number |
|-------------|------|-------------|
| 1 | 2025-07-20 | 1 |
| 2 | 2025-07-27 | 2 |
| 3 | 2025-08-17 | 3 |
| 4 | 2025-09-14 | 4 |
| 5 | 2025-09-21 | 5 |
| 6 | 2025-10-19 | 6 |

**Note:** Week 3 is in August (not July), showing continuous numbering.

---

## 🎯 Benefits

| Feature | Benefit |
|---------|---------|
| **Year Tabs** | Quick navigation across years |
| **Month Selection** | Easy month browsing within year |
| **Continuous Weeks** | Clear global sequence of prophecies |
| **Database-Driven** | Admin controls week numbers |
| **Responsive Design** | Works on all devices |
| **Keyboard Navigation** | Accessible for all users |
| **Default Selection** | Opens to current year/month |

---

## 📝 Admin Workflow

### Adding New Prophecy

1. Go to **Admin > Prophecies > Create New**
2. Fill in Title, Date, etc.
3. **Enter Week Number** (next in sequence)
   - Check last prophecy's week number
   - Increment by 1
   - Example: If last was Week 5, enter 6
4. Save

### Editing Week Numbers

1. If sequence needs adjustment
2. Go to **Edit** prophecy
3. Update week number
4. Save

### Bulk Update (if needed)

Run SQL query or Tinker command to resequence all prophecies.

---

## ✨ Summary

✅ **Year tabs** added with current year default
✅ **Month selection** with current month default
✅ **Continuous week numbering** (global sequence)
✅ **Database field** `week_number` added
✅ **Admin forms** updated with week number field
✅ **Controller** restructured for year/month grouping
✅ **Responsive design** for mobile/tablet
✅ **Keyboard accessible** navigation
✅ **Smooth animations** and transitions

The home page now provides an intuitive year/month/week navigation system with continuous week numbering across all prophecies! 🎉

