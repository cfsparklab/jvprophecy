<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Prophecy;
use App\Models\ProphecyTranslation;
use App\Services\PdfStorageService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class MigratePdfsToCloud extends Command
{
    protected $signature = 'pdfs:migrate-to-cloud 
                            {--dry-run : Run without actually migrating files}
                            {--force : Force migration even if file exists in cloud}';
    
    protected $description = 'Migrate all PDF files from local storage to cloud storage (Cloudflare R2)';

    protected $pdfService;

    public function __construct(PdfStorageService $pdfService)
    {
        parent::__construct();
        $this->pdfService = $pdfService;
    }

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');
        
        $this->info('🚀 Starting PDF migration to cloud storage...');
        $this->newLine();
        
        if ($dryRun) {
            $this->warn('⚠️  DRY RUN MODE - No files will actually be migrated');
            $this->newLine();
        }
        
        $stats = [
            'total' => 0,
            'migrated' => 0,
            'skipped' => 0,
            'failed' => 0,
        ];
        
        // Migrate main prophecy PDFs
        $this->info('📚 Migrating main prophecy PDFs...');
        $prophecies = Prophecy::whereNotNull('pdf_file')->get();
        
        foreach ($prophecies as $prophecy) {
            $stats['total']++;
            $this->line("Processing: {$prophecy->title} (ID: {$prophecy->id})");
            
            $result = $this->migratePdf($prophecy->pdf_file, $dryRun, $force);
            $stats[$result]++;
        }
        
        $this->newLine();
        
        // Migrate translation PDFs
        $this->info('🌍 Migrating translation PDFs...');
        $translations = ProphecyTranslation::whereNotNull('pdf_file')->get();
        
        foreach ($translations as $translation) {
            $stats['total']++;
            $this->line("Processing: {$translation->title} ({$translation->language}) (ID: {$translation->id})");
            
            $result = $this->migratePdf($translation->pdf_file, $dryRun, $force);
            $stats[$result]++;
        }
        
        $this->newLine();
        $this->displaySummary($stats, $dryRun);
        
        return Command::SUCCESS;
    }

    protected function migratePdf(string $pdfPath, bool $dryRun, bool $force): string
    {
        // Check if exists in local storage
        if (!Storage::disk('public')->exists($pdfPath)) {
            $this->error("  ❌ File not found in local storage: {$pdfPath}");
            return 'failed';
        }
        
        // Check if already exists in cloud (skip unless force)
        $cloudDisk = env('PDF_STORAGE_DISK', 'r2');
        if (!$force && Storage::disk($cloudDisk)->exists($pdfPath)) {
            $this->warn("  ⏭️  Already exists in cloud, skipping");
            return 'skipped';
        }
        
        if ($dryRun) {
            $this->info("  ✓ Would migrate: {$pdfPath}");
            return 'migrated';
        }
        
        // Perform migration
        $result = $this->pdfService->migratePdfToCloud($pdfPath);
        
        if ($result['success']) {
            $this->info("  ✅ Migrated successfully");
            $this->line("     URL: {$result['url']}");
            return 'migrated';
        } else {
            $this->error("  ❌ Migration failed: {$result['message']}");
            return 'failed';
        }
    }

    protected function displaySummary(array $stats, bool $dryRun): void
    {
        $this->info('📊 Migration Summary');
        $this->info('═══════════════════');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total PDFs', $stats['total']],
                ['Migrated', $stats['migrated']],
                ['Skipped', $stats['skipped']],
                ['Failed', $stats['failed']],
            ]
        );
        
        if ($dryRun) {
            $this->newLine();
            $this->info('💡 To actually perform the migration, run without --dry-run flag');
        } elseif ($stats['migrated'] > 0) {
            $this->newLine();
            $this->info('✅ Migration completed successfully!');
            $this->info('📝 Remember to update your .env file to use cloud storage:');
            $this->line('   PDF_STORAGE_DISK=r2');
        }
    }
}

