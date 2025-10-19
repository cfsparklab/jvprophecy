<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfMergeService
{
    /**
     * Add cover page and footer to an uploaded PDF
     */
    public function addCoverAndFooterToPdf($uploadedPdfPath, $prophecy, $translation, $language)
    {
        try {
            // First, generate the cover page as a separate PDF
            $coverPdf = $this->generateCoverPage($prophecy, $translation, $language);
            
            // Save cover page temporarily
            $tempCoverPath = storage_path('app/temp/cover_' . time() . '.pdf');
            if (!file_exists(dirname($tempCoverPath))) {
                mkdir(dirname($tempCoverPath), 0755, true);
            }
            file_put_contents($tempCoverPath, $coverPdf);
            
            // Get the full path of the uploaded PDF
            $uploadedFullPath = Storage::disk('public')->path($uploadedPdfPath);
            
            // Create new FPDI instance
            $pdf = new Fpdi();
            
            // Import and add cover page
            $coverPageCount = $pdf->setSourceFile($tempCoverPath);
            for ($pageNo = 1; $pageNo <= $coverPageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);
                $pdf->AddPage($size['orientation'] ?? 'P', $size);
                $pdf->useTemplate($templateId);
            }
            
            // Import the uploaded PDF
            $uploadedPageCount = $pdf->setSourceFile($uploadedFullPath);
            
            for ($pageNo = 1; $pageNo <= $uploadedPageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);
                $pdf->AddPage($size['orientation'] ?? 'P', $size);
                $pdf->useTemplate($templateId);
                
                // No watermarks on content pages - only on cover page
            }
            
            // Clean up temporary cover file
            if (file_exists($tempCoverPath)) {
                unlink($tempCoverPath);
            }
            
            // Return the merged PDF content
            return $pdf->Output('S'); // Return as string
            
        } catch (\Exception $e) {
            // If PDF merging fails, return the original uploaded PDF
            return Storage::disk('public')->get($uploadedPdfPath);
        }
    }
    
    /**
     * Generate cover page PDF
     */
    private function generateCoverPage($prophecy, $translation, $language)
    {
        $pdf = Pdf::loadView('pdf.cover-page-only', compact('prophecy', 'translation', 'language'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
                'defaultFont' => 'DejaVu Sans',
                'enable_php' => false,
                'enable_javascript' => false,
                'enable_remote' => false,
                'dpi' => 96,
                'defaultMediaType' => 'print',
                'isFontSubsettingEnabled' => false,
            ]);
            
        return $pdf->output();
    }
}
