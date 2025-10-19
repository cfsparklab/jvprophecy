<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\UnicodeService;

class ProphecyTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'prophecy_id',
        'language',
        'title',
        'description',
        'content',
        'excerpt',
        'prayer_points',
        'pdf_file',
        'pdf_uploaded_at',
        'pdf_original_name',
        'pdf_file_size',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'pdf_uploaded_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($translation) {
            // Clean and normalize title for Unicode
            if ($translation->isDirty('title') && !empty($translation->title)) {
                $translation->title = UnicodeService::normalizeForDatabase($translation->title);
            }
            
            // Clean and normalize content for Unicode
            if ($translation->isDirty('content') && !empty($translation->content)) {
                $translation->content = UnicodeService::cleanHtmlForMultiLanguage($translation->content);
                $translation->content = UnicodeService::normalizeForDatabase($translation->content);
            }
            
            // Clean and normalize description for Unicode
            if ($translation->isDirty('description') && !empty($translation->description)) {
                $translation->description = UnicodeService::cleanHtmlForMultiLanguage($translation->description);
                $translation->description = UnicodeService::normalizeForDatabase($translation->description);
            }
            
            // Clean and normalize prayer_points for Unicode
            if ($translation->isDirty('prayer_points') && !empty($translation->prayer_points)) {
                $translation->prayer_points = UnicodeService::cleanHtmlForMultiLanguage($translation->prayer_points);
                $translation->prayer_points = UnicodeService::normalizeForDatabase($translation->prayer_points);
            }
            
            // Normalize excerpt for Unicode
            if ($translation->isDirty('excerpt') && !empty($translation->excerpt)) {
                $translation->excerpt = UnicodeService::normalizeForDatabase($translation->excerpt);
            }
        });
    }

    /**
     * Get the prophecy that owns the translation.
     */
    public function prophecy()
    {
        return $this->belongsTo(Prophecy::class);
    }
}
