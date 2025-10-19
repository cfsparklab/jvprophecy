<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProphecyTranslation;
use App\Services\UnicodeService;

class FixMalformedHtml extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prophecy:fix-malformed-html {--translation-id= : Specific translation ID to fix}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix malformed HTML in prophecy translations content';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $translationId = $this->option('translation-id');
        
        if ($translationId) {
            $translations = ProphecyTranslation::where('id', $translationId)->get();
            if ($translations->isEmpty()) {
                $this->error("Translation with ID {$translationId} not found.");
                return 1;
            }
        } else {
            $translations = ProphecyTranslation::whereNotNull('content')
                ->where('content', '!=', '')
                ->get();
        }

        $this->info("Found {$translations->count()} translations to process...");

        $fixed = 0;
        $errors = 0;

        foreach ($translations as $translation) {
            try {
                $originalContent = $translation->content;
                
                // Skip if content is empty
                if (empty($originalContent)) {
                    continue;
                }
                
                // Apply the improved HTML cleaning
                $cleanedContent = UnicodeService::cleanHtmlForMultiLanguage($originalContent);
                
                // Only update if content actually changed
                if ($cleanedContent !== $originalContent) {
                    // Temporarily disable the model event to avoid double processing
                    ProphecyTranslation::withoutEvents(function () use ($translation, $cleanedContent) {
                        $translation->update(['content' => $cleanedContent]);
                    });
                    
                    $this->line("Fixed translation ID {$translation->id} (Language: {$translation->language})");
                    $fixed++;
                } else {
                    $this->line("Translation ID {$translation->id} (Language: {$translation->language}) - No changes needed");
                }
                
            } catch (\Exception $e) {
                $this->error("Error processing translation ID {$translation->id}: " . $e->getMessage());
                $errors++;
            }
        }

        $this->info("\nProcessing complete!");
        $this->info("Fixed: {$fixed} translations");
        
        if ($errors > 0) {
            $this->warn("Errors: {$errors} translations");
        }

        return 0;
    }
}
