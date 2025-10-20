<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class PdfStorageService
{
    /**
     * Get the disk to use for PDF storage
     * Defaults to 'r2' (Cloudflare R2), falls back to 'public' for local dev
     */
    protected function getPdfDisk(): string
    {
        return config('filesystems.pdf_disk', env('PDF_STORAGE_DISK', 'r2'));
    }

    /**
     * Store a PDF file
     */
    public function storePdf(UploadedFile $file, string $directory, string $filename): array
    {
        $disk = $this->getPdfDisk();
        
        try {
            $path = $file->storeAs($directory, $filename, $disk);
            
            Log::info('PDF stored successfully', [
                'disk' => $disk,
                'path' => $path,
                'filename' => $filename
            ]);
            
            return [
                'success' => true,
                'path' => $path,
                'disk' => $disk,
                'url' => $this->getPdfUrl($path),
                'size' => $file->getSize(),
                'original_name' => $file->getClientOriginalName(),
            ];
        } catch (\Exception $e) {
            Log::error('Failed to store PDF', [
                'disk' => $disk,
                'filename' => $filename,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Delete a PDF file
     */
    public function deletePdf(string $path): bool
    {
        $disk = $this->getPdfDisk();
        
        try {
            if (Storage::disk($disk)->exists($path)) {
                Storage::disk($disk)->delete($path);
                Log::info('PDF deleted successfully', ['disk' => $disk, 'path' => $path]);
                return true;
            }
            
            // Also try to delete from public disk (for migration)
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                Log::info('PDF deleted from public disk', ['path' => $path]);
                return true;
            }
            
            return false;
        } catch (\Exception $e) {
            Log::error('Failed to delete PDF', [
                'disk' => $disk,
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Check if PDF exists
     */
    public function pdfExists(string $path): bool
    {
        $disk = $this->getPdfDisk();
        
        // Check in cloud storage first
        if (Storage::disk($disk)->exists($path)) {
            return true;
        }
        
        // Fall back to public disk (for migration period)
        if (Storage::disk('public')->exists($path)) {
            return true;
        }
        
        return false;
    }

    /**
     * Get PDF URL for downloading
     * Returns authenticated route URL instead of direct storage URL
     */
    public function getPdfUrl(string $path): string
    {
        // Extract prophecy ID and language from path
        // Path format: prophecy_pdfs/prophecy_20_ta_1760886120.pdf
        if (preg_match('/prophecy_(\d+)_([a-z]{2})_/', $path, $matches)) {
            $prophecyId = $matches[1];
            $language = $matches[2];
            
            // Return route URL that will be proxied through the application
            return route('prophecies.download.pdf', [
                'id' => $prophecyId,
                'language' => $language
            ]);
        }
        
        // Fallback for main English PDFs (prophecy_main_*.pdf)
        if (preg_match('/prophecy_main_(\d+)_/', $path, $matches)) {
            $prophecyId = $matches[1];
            
            return route('prophecies.download.pdf', [
                'id' => $prophecyId,
                'language' => 'en'
            ]);
        }
        
        // If we can't parse the ID, log error and return empty
        Log::error('Could not parse prophecy ID from PDF path', ['path' => $path]);
        return '';
    }
    
    /**
     * Get PDF URL for a specific prophecy and language
     * Preferred method - more explicit
     */
    public function getPdfUrlForProphecy(int $prophecyId, string $language = 'en'): string
    {
        return route('prophecies.download.pdf', [
            'id' => $prophecyId,
            'language' => $language
        ]);
    }

    /**
     * Get PDF path for direct file access
     * Returns null for cloud storage as files should be accessed via URL
     */
    public function getPdfPath(string $path): ?string
    {
        $disk = $this->getPdfDisk();
        
        // For cloud storage, we don't provide local paths
        if ($disk === 'r2' || $disk === 's3') {
            return null;
        }
        
        // For local storage, return the full path
        if (Storage::disk('public')->exists($path)) {
            return storage_path('app/public/' . $path);
        }
        
        return null;
    }

    /**
     * Download PDF content (for cloud storage)
     */
    public function getPdfContent(string $path): ?string
    {
        $disk = $this->getPdfDisk();
        
        try {
            if (Storage::disk($disk)->exists($path)) {
                return Storage::disk($disk)->get($path);
            }
            
            // Fall back to public disk
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->get($path);
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Failed to get PDF content', [
                'disk' => $disk,
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Copy PDF from local to cloud storage
     */
    public function migratePdfToCloud(string $localPath): array
    {
        $cloudDisk = $this->getPdfDisk();
        
        if ($cloudDisk === 'public') {
            return [
                'success' => false,
                'message' => 'Cloud storage not configured'
            ];
        }
        
        try {
            // Check if file exists locally
            if (!Storage::disk('public')->exists($localPath)) {
                return [
                    'success' => false,
                    'message' => 'Local file not found'
                ];
            }
            
            // Get the file content
            $content = Storage::disk('public')->get($localPath);
            
            // Upload to cloud
            Storage::disk($cloudDisk)->put($localPath, $content, 'public');
            
            Log::info('PDF migrated to cloud', [
                'path' => $localPath,
                'disk' => $cloudDisk
            ]);
            
            return [
                'success' => true,
                'path' => $localPath,
                'url' => $this->getPdfUrl($localPath)
            ];
        } catch (\Exception $e) {
            Log::error('Failed to migrate PDF to cloud', [
                'path' => $localPath,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}

