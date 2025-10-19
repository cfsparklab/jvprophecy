<?php

namespace App\Console\Commands;

use App\Models\Prophecy;
use App\Models\ProphecyTranslation;
use Illuminate\Console\Command;

class CleanProphecyContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prophecy:clean-content {--dry-run : Show what would be cleaned without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean Microsoft Word formatting from existing prophecy content';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('DRY RUN MODE - No changes will be made');
        }

        $this->info('Starting prophecy content cleaning...');

        // Clean main prophecy descriptions
        $prophecies = Prophecy::whereNotNull('description')->get();
        $cleanedProphecies = 0;

        foreach ($prophecies as $prophecy) {
            $originalDescription = $prophecy->description;
            $cleanedDescription = $this->cleanHtmlContent($originalDescription);
            
            if ($originalDescription !== $cleanedDescription) {
                $this->line("Prophecy ID {$prophecy->id}: Cleaning description");
                
                if (!$dryRun) {
                    $prophecy->description = $cleanedDescription;
                    $prophecy->save();
                }
                $cleanedProphecies++;
            }
        }

        // Clean prophecy translations
        $translations = ProphecyTranslation::whereNotNull('content')->get();
        $cleanedTranslations = 0;

        foreach ($translations as $translation) {
            $originalContent = $translation->content;
            $cleanedContent = $this->cleanHtmlContent($originalContent);
            
            if ($originalContent !== $cleanedContent) {
                $this->line("Translation ID {$translation->id} ({$translation->language}): Cleaning content");
                
                if (!$dryRun) {
                    $translation->content = $cleanedContent;
                    $translation->save();
                }
                $cleanedTranslations++;
            }
        }

        $this->info("Cleaning completed!");
        $this->info("Prophecies cleaned: {$cleanedProphecies}");
        $this->info("Translations cleaned: {$cleanedTranslations}");
        
        if ($dryRun) {
            $this->warn('This was a dry run. Run without --dry-run to apply changes.');
        }
    }

    /**
     * Clean HTML content while preserving original formatting.
     */
    private function cleanHtmlContent($html)
    {
        if (empty($html)) {
            return $html;
        }
        
        // Remove Word-specific attributes and classes
        $html = preg_replace('/\s*(class|lang|mso-[^=]*)\s*=\s*"[^"]*"/i', '', $html);
        
        // Clean style attributes - be more permissive, only remove truly problematic styles
        $html = preg_replace_callback('/style\s*=\s*"([^"]*)"/i', function($matches) {
            $styles = $matches[1];
            
            // Split styles by semicolon
            $styleArray = explode(';', $styles);
            $cleanStyles = [];
            
            foreach ($styleArray as $style) {
                $style = trim($style);
                if (empty($style)) continue;
                
                // Remove only truly problematic styles, keep most formatting
                if (preg_match('/^(mso-|font-family|margin|padding|width|height|position|top|left|right|bottom|line-height|font-size)\s*:/i', $style)) {
                    // Skip problematic styles that can break layout or are Word-specific
                    continue;
                }
                
                // Keep all other styles including colors, font-weight, font-style, etc.
                $cleanStyles[] = $style;
            }
            
            // Return cleaned style attribute or remove if empty
            if (!empty($cleanStyles)) {
                return 'style="' . implode('; ', $cleanStyles) . '"';
            }
            return '';
        }, $html);
        
        // Remove empty spans and unnecessary tags
        $html = preg_replace('/<span[^>]*>\s*<\/span>/i', '', $html);
        
        // Only remove spans that have no attributes left after cleaning
        $html = preg_replace('/<span\s*>([^<]+)<\/span>/i', '$1', $html);
        
        // Clean up multiple spaces and line breaks
        $html = preg_replace('/\s+/', ' ', $html);
        $html = preg_replace('/>\s+</', '><', $html);
        
        // Remove empty paragraphs
        $html = preg_replace('/<p[^>]*>\s*<\/p>/i', '', $html);
        
        // Clean up Word-specific paragraph classes
        $html = preg_replace('/<p\s+class="[^"]*"([^>]*)>/i', '<p$1>', $html);
        
        return trim($html);
    }
}