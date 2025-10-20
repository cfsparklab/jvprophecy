<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\UnicodeService;

class Prophecy extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'jebikalam_vanga_date',
        'week_number',
        'category_id',
        'created_by',
        'status',
        'visibility',
        'tags',
        'featured_image',
        'excerpt',
        'video_url',
        'pdf_file',
        'pdf_uploaded_at',
        'pdf_original_name',
        'pdf_file_size',
        'prayer_points',
        'view_count',
        'download_count',
        'print_count',
        'published_at',
    ];

    protected $casts = [
        'jebikalam_vanga_date' => 'date',
        'tags' => 'array',
        'published_at' => 'datetime',
        'pdf_uploaded_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($prophecy) {
            // Clean and normalize title for Unicode
            if ($prophecy->isDirty('title') && !empty($prophecy->title)) {
                $prophecy->title = UnicodeService::normalizeForDatabase($prophecy->title);
            }
            
            // Clean and normalize description for Unicode
            if ($prophecy->isDirty('description') && !empty($prophecy->description)) {
                $prophecy->description = UnicodeService::cleanHtmlForMultiLanguage($prophecy->description);
                $prophecy->description = UnicodeService::normalizeForDatabase($prophecy->description);
            }
            
            // Normalize other text fields
            if ($prophecy->isDirty('excerpt') && !empty($prophecy->excerpt)) {
                $prophecy->excerpt = UnicodeService::normalizeForDatabase($prophecy->excerpt);
            }
        });
    }

    /**
     * Get the category that owns the prophecy.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user who created the prophecy.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the translations for the prophecy.
     */
    public function translations()
    {
        return $this->hasMany(ProphecyTranslation::class);
    }

    /**
     * Get translation for a specific language.
     */
    public function getTranslation($language = 'en')
    {
        return $this->translations()->where('language', $language)->first();
    }

    /**
     * Scope a query to only include published prophecies.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include public prophecies.
     */
    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    /**
     * Scope a query to filter by date.
     */
    public function scopeByDate($query, $date)
    {
        return $query->where('jebikalam_vanga_date', $date);
    }

    /**
     * Increment view count.
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    /**
     * Increment download count.
     */
    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }

    /**
     * Increment print count.
     */
    public function incrementPrintCount()
    {
        $this->increment('print_count');
    }

    /**
     * Clean HTML content while preserving essential formatting.
     */
    public static function cleanHtmlContent($html)
    {
        if (empty($html)) {
            return $html;
        }
        
        // Only remove Word-specific attributes, preserve essential formatting
        $html = preg_replace('/\s*(class|lang|mso-[^=]*)\s*=\s*"[^"]*"/i', '', $html);
        
        // Clean style attributes - preserve colors and essential formatting
        $html = preg_replace_callback('/style\s*=\s*"([^"]*)"/i', function($matches) {
            $styles = $matches[1];
            
            // Split styles by semicolon
            $styleArray = explode(';', $styles);
            $cleanStyles = [];
            
            foreach ($styleArray as $style) {
                $style = trim($style);
                if (empty($style)) continue;
                
                // Keep essential formatting styles
                if (preg_match('/^(color|background-color|font-weight|font-style|text-decoration)\s*:/i', $style)) {
                    $cleanStyles[] = $style;
                }
                // Remove only truly problematic Word-specific styles
                elseif (preg_match('/^(mso-|font-family|margin|padding|width|height|position|top|left|right|bottom|line-height|font-size)\s*:/i', $style)) {
                    // Skip problematic styles
                    continue;
                }
                // Keep other safe styles
                else {
                    $cleanStyles[] = $style;
                }
            }
            
            // Return cleaned style attribute or remove if empty
            if (!empty($cleanStyles)) {
                return 'style="' . implode('; ', $cleanStyles) . '"';
            }
            return '';
        }, $html);
        
        // Only remove truly empty spans
        $html = preg_replace('/<span[^>]*>\s*<\/span>/i', '', $html);
        
        // Don't remove spans with style attributes
        $html = preg_replace('/<span\s*>([^<]+)<\/span>/i', '$1', $html);
        
        // Minimal cleanup - preserve structure
        $html = preg_replace('/\s+/', ' ', $html);
        
        // Remove empty paragraphs
        $html = preg_replace('/<p[^>]*>\s*<\/p>/i', '', $html);
        
        // Clean up Word-specific paragraph classes but preserve other attributes
        $html = preg_replace('/<p\s+class="[^"]*"([^>]*)>/i', '<p$1>', $html);
        
        return trim($html);
    }
}
