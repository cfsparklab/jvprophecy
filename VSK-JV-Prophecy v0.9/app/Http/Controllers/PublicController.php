<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Prophecy;
use App\Models\SecurityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\UnicodeService;
use App\Services\TextToImageService;
use App\Services\Web2PdfService;
use App\Services\PdfMergeService;

class PublicController extends Controller
{
    /**
     * Show the home page with date selection.
     */
    public function index()
    {
        try {
            // Get available prophecy dates grouped by month
            $prophecies = Prophecy::published()
                ->public()
                ->select('id', 'jebikalam_vanga_date')
                ->with(['translations' => function($query) {
                    $query->select('prophecy_id', 'language');
                }])
                ->whereNotNull('jebikalam_vanga_date')
                ->orderBy('jebikalam_vanga_date', 'desc')
                ->get()
                ->filter(function($prophecy) {
                    return $prophecy->jebikalam_vanga_date !== null;
                });

            // Group prophecies by month-year
            $groupedDates = $prophecies->groupBy(function($prophecy) {
                return $prophecy->jebikalam_vanga_date->format('Y-m');
            })->map(function($monthProphecies, $monthKey) {
                $firstDate = $monthProphecies->first()->jebikalam_vanga_date;
                
                return [
                    'month_key' => $monthKey,
                    'month_name' => $firstDate->format('F Y'),
                    'prophecy_count' => $monthProphecies->count(),
                    'dates' => $monthProphecies->map(function($prophecy) {
                        $availableLanguages = $prophecy->translations->pluck('language')->toArray();
                        
                        return [
                            'prophecy_id' => $prophecy->id,
                            'jebikalam_vanga_date' => $prophecy->jebikalam_vanga_date->format('Y-m-d'),
                            'formatted_date' => $prophecy->jebikalam_vanga_date->format('d/m/Y'),
                            'available_languages' => $availableLanguages ?: ['en'],
                            'prophecy_count' => 1
                        ];
                    })->values()
                ];
            });
            
            $categories = Category::active()->root()->orderBy('sort_order')->get();
            
        } catch (\Exception $e) {
            // Log the error and provide fallback data
            \Log::error('Error loading home page data: ' . $e->getMessage());
            
            $groupedDates = collect([]);
            $categories = collect([]);
        }
        
        return view('public.index', compact('categories', 'groupedDates'));
    }

    /**
     * Show prophecies for a specific date.
     */
    public function showPropheciesByDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'language' => 'nullable|string|in:en,ta,kn,te,ml,hi',
        ]);

        $date = Carbon::parse($request->date)->format('Y-m-d');
        $language = $request->language ?? (Auth::user()->preferred_language ?? 'en');

        $prophecies = Prophecy::published()
            ->public()
            ->byDate($date)
            ->with(['category', 'translations' => function($query) use ($language) {
                $query->where('language', $language);
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Log view event
        $this->logSecurityEvent('prophecy_date_view', Auth::id(), [
            'date' => $date,
            'language' => $language,
            'prophecy_count' => $prophecies->count()
        ]);

        return view('public.prophecies-by-date', compact('prophecies', 'date', 'language'));
    }

    /**
     * Show a specific prophecy.
     */
public function showProphecy(Request $request, $id)
    {
        $language = $request->language ?? (Auth::user()->preferred_language ?? 'en');

        $prophecy = Prophecy::published()
            ->public()
            ->with(['category', 'creator', 'translations'])
            ->findOrFail($id);

        // Increment view count
        $prophecy->incrementViewCount();

        // Log view event
        $this->logSecurityEvent('prophecy_view', Auth::id(), [
            'prophecy_id' => $prophecy->id,
            'prophecy_title' => $prophecy->title,
            'language' => $language,
            'jebikalam_vanga_date' => $prophecy->jebikalam_vanga_date->format('d/m/Y')
        ]);

        // Get translation for the selected language
        $translation = $prophecy->translations->where('language', $language)->first();
        
        // If no translation found, try to find it directly from database
        if (!$translation && $language !== 'en') {
            $translation = $prophecy->translations()->where('language', $language)->first();
        }
        
        // If still no translation found, fall back to English
        if (!$translation) {
            $translation = $prophecy->translations->where('language', 'en')->first();
            
            // If no English translation either, try to get any available translation
            if (!$translation) {
                $translation = $prophecy->translations->first();
            }
            
            // Log missing translation event
            if ($language !== 'en') {
                $this->logSecurityEvent('translation_missing', Auth::id(), [
                    'prophecy_id' => $prophecy->id,
                    'requested_language' => $language,
                    'fallback_language' => $translation ? $translation->language : 'none',
                    'available_languages' => $prophecy->translations->pluck('language')->toArray()
                ]);
            }
        }
        
        // If requesting English but no English translation exists, create fallback from main prophecy
        if ($language === 'en' && (!$translation || $translation->language !== 'en')) {
            $translation = (object) [
                'language' => 'en',
                'title' => $prophecy->title,
                'content' => $prophecy->description ?? 'English content not available.',
                'description' => $prophecy->excerpt ?? 'English description not available.',
                'prophecy_id' => $prophecy->id,
                'excerpt' => $prophecy->excerpt,
                'prayer_points' => $prophecy->prayer_points
            ];
        }
        
        // If we still don't have any translation, create a basic fallback
        if (!$translation) {
            $translation = (object) [
                'language' => $language,
                'title' => $prophecy->title,
                'content' => $prophecy->description ?? 'Content not available in the requested language.',
                'description' => $prophecy->excerpt ?? 'Description not available.',
                'prophecy_id' => $prophecy->id,
                'prayer_points' => $prophecy->prayer_points
            ];
            
            // Log critical missing translation event
            $this->logSecurityEvent('translation_critical_missing', Auth::id(), [
                'prophecy_id' => $prophecy->id,
                'requested_language' => $language,
                'fallback_created' => true
            ]);
        }

        return view('public.prophecy-detail', compact('prophecy', 'translation', 'language'));
    }

    /**
     * Download prophecy as enhanced PDF with cover page and footer.
     */
    public function downloadProphecyPdf(Request $request, $id)
    {
        $language = $request->language ?? (Auth::user()->preferred_language ?? 'en');

        $prophecy = Prophecy::published()
            ->public()
            ->with(['category', 'creator', 'translations'])
            ->findOrFail($id);

        // Increment download count
        $prophecy->increment('download_count');

        // Log download event
        $this->logSecurityEvent('prophecy_pdf_download', Auth::id(), [
            'prophecy_id' => $prophecy->id,
            'prophecy_title' => $prophecy->title,
            'language' => $language,
            'jebikalam_vanga_date' => $prophecy->jebikalam_vanga_date->format('d/m/Y')
        ]);

        // Get translation for the selected language
        $translation = $prophecy->translations->where('language', $language)->first();
        
        // If no translation found, try to find it directly from database
        if (!$translation && $language !== 'en') {
            $translation = $prophecy->translations()->where('language', $language)->first();
        }
        
        // If still no translation found, fall back to English
        if (!$translation) {
            $translation = $prophecy->translations->where('language', 'en')->first();
            
            // If no English translation either, try to get any available translation
            if (!$translation) {
                $translation = $prophecy->translations->first();
            }
        }
        
        // If requesting English but no English translation exists, create fallback from main prophecy
        if ($language === 'en' && (!$translation || $translation->language !== 'en')) {
            $translation = (object) [
                'language' => 'en',
                'title' => $prophecy->title,
                'content' => $prophecy->description ?? 'English content not available.',
                'description' => $prophecy->excerpt ?? 'English description not available.',
                'prophecy_id' => $prophecy->id,
                'excerpt' => $prophecy->excerpt,
                'prayer_points' => $prophecy->prayer_points
            ];
        }
        
        // If we still don't have any translation, create a basic fallback
        if (!$translation) {
            $translation = (object) [
                'language' => $language,
                'title' => $prophecy->title,
                'content' => $prophecy->description ?? 'Content not available in the requested language.',
                'description' => $prophecy->excerpt ?? 'Description not available.',
                'prophecy_id' => $prophecy->id,
                'prayer_points' => $prophecy->prayer_points
            ];
        }

        // Generate PDF
        $pdf = Pdf::loadView('pdf.prophecy-enhanced', compact('prophecy', 'translation', 'language'))
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
                'debugKeepTemp' => false,
                'debugCss' => false,
                'debugLayout' => false,
                'debugLayoutLines' => false,
                'debugLayoutBlocks' => false,
                'debugLayoutInline' => false,
                'debugLayoutPaddingBox' => false,
            ]);

        // Generate filename
        $title = $translation && $translation->title ? $translation->title : $prophecy->title;
        $cleanTitle = preg_replace('/[^A-Za-z0-9\-_]/', '_', $title);
        $filename = 'prophecy_' . $cleanTitle . '_' . $language . '_' . $prophecy->jebikalam_vanga_date->format('Y-m-d') . '.pdf';

        // Return with comprehensive headers to prevent .pdf.html issue (mobile-optimized)
        $pdfContent = $pdf->output();
        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Length', strlen($pdfContent))
            ->header('Content-Transfer-Encoding', 'binary')
            ->header('Content-Description', 'File Transfer')
            ->header('Accept-Ranges', 'bytes')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate, post-check=0, pre-check=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0')
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('X-Download-Options', 'noopen');
    }

    /**
     * Download prophecy PDF using uploaded files only
     */
    public function downloadUploadedProphecyPdf(Request $request, $id)
    {
        $language = $request->language ?? (Auth::user()->preferred_language ?? 'en');

        $prophecy = Prophecy::published()
            ->public()
            ->with(['category', 'creator', 'translations'])
            ->findOrFail($id);

        // Check for uploaded PDF file based on language
        $pdfPath = null;
        $originalName = null;
        
        if ($language === 'en') {
            // For English, check main prophecy PDF
            if ($prophecy->pdf_file && Storage::disk('public')->exists($prophecy->pdf_file)) {
                $pdfPath = $prophecy->pdf_file;
                $originalName = $prophecy->pdf_original_name;
            }
        } else {
            // For other languages, check translation PDF
            $translation = $prophecy->translations->where('language', $language)->first();
            if ($translation && $translation->pdf_file && Storage::disk('public')->exists($translation->pdf_file)) {
                $pdfPath = $translation->pdf_file;
                $originalName = $translation->pdf_original_name;
            }
        }

        // If no PDF found, return "Coming Soon" message
        if (!$pdfPath) {
            $languageName = match($language) {
                'ta' => 'Tamil (தமிழ்)',
                'kn' => 'Kannada (ಕನ್ನಡ)',
                'te' => 'Telugu (తెలుగు)',
                'ml' => 'Malayalam (മലയാളം)',
                'hi' => 'Hindi (हिंदी)',
                default => 'English'
            };

            return response()->json([
                'error' => 'PDF not available',
                'message' => "PDF for this prophecy in {$languageName} is coming soon. Please check back later.",
                'prophecy_title' => $prophecy->title,
                'language' => $language
            ], 404);
        }

        // Increment download count
        $prophecy->increment('download_count');

        // Log download event
        $this->logSecurityEvent('prophecy_pdf_download', Auth::id(), [
            'prophecy_id' => $prophecy->id,
            'prophecy_title' => $prophecy->title,
            'language' => $language,
            'jebikalam_vanga_date' => $prophecy->jebikalam_vanga_date->format('d/m/Y'),
            'pdf_source' => 'uploaded_file'
        ]);

        // Get translation data for cover page
        $translation = null;
        if ($language !== 'en') {
            $translation = $prophecy->translations->where('language', $language)->first();
        }
        
        // If requesting English but no English translation exists, create fallback from main prophecy
        if ($language === 'en' && !$translation) {
            $translation = (object) [
                'language' => 'en',
                'title' => $prophecy->title,
                'content' => $prophecy->description ?? 'English content not available.',
                'description' => $prophecy->excerpt ?? 'English description not available.',
                'prophecy_id' => $prophecy->id,
                'excerpt' => $prophecy->excerpt,
                'prayer_points' => $prophecy->prayer_points
            ];
        }
        
        // Use PDF merge service to add cover page and footer
        $pdfMergeService = new PdfMergeService();
        $mergedPdfContent = $pdfMergeService->addCoverAndFooterToPdf($pdfPath, $prophecy, $translation, $language);
        
        // Generate filename for download
        $title = $prophecy->title;
        $cleanTitle = preg_replace('/[^A-Za-z0-9\-_]/', '_', $title);
        $filename = 'prophecy_' . $cleanTitle . '_' . $language . '_' . $prophecy->jebikalam_vanga_date->format('Y-m-d') . '.pdf';

        // Return the merged PDF with comprehensive headers (mobile-optimized)
        return response($mergedPdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Length', strlen($mergedPdfContent))
            ->header('Content-Transfer-Encoding', 'binary')
            ->header('Content-Description', 'File Transfer')
            ->header('Accept-Ranges', 'bytes')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate, post-check=0, pre-check=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0')
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('X-Download-Options', 'noopen');
    }

    /**
     * Test method for PDF generation (bypasses auth for testing)
     */
    public function testPdfGeneration(Request $request, $id)
    {
        $language = $request->language ?? 'te';
        
        $prophecy = Prophecy::published()
            ->public()
            ->with(['category', 'creator', 'translations' => function($query) use ($language) {
                $query->where('language', $language);
            }])
            ->findOrFail($id);
            
        return $this->generateSecurePDF($prophecy, $language);
    }

    /**
     * Download prophecy as PDF.
     */
    public function downloadProphecy(Request $request, $id)
    {
        $language = $request->language ?? (Auth::user()->preferred_language ?? 'en');

        $prophecy = Prophecy::published()
            ->public()
            ->with(['category', 'creator', 'translations' => function($query) use ($language) {
                $query->where('language', $language);
            }])
            ->findOrFail($id);

        // Increment download count
        $prophecy->incrementDownloadCount();

        // Log download event
        $this->logSecurityEvent('prophecy_download', Auth::id(), [
            'prophecy_id' => $prophecy->id,
            'prophecy_title' => $prophecy->title,
            'language' => $language,
            'format' => 'pdf'
        ], 'medium');

        // Generate PDF with security features
        return $this->generateSecurePDF($prophecy, $language);
    }

    /**
     * Print prophecy.
     */
    public function printProphecy(Request $request, $id)
    {
        $language = $request->language ?? (Auth::user()->preferred_language ?? 'en');

        $prophecy = Prophecy::published()
            ->public()
            ->with(['category', 'creator', 'translations' => function($query) use ($language) {
                $query->where('language', $language);
            }])
            ->findOrFail($id);

        // Increment print count
        $prophecy->incrementPrintCount();

        // Log print event
        $this->logSecurityEvent('prophecy_print', Auth::id(), [
            'prophecy_id' => $prophecy->id,
            'prophecy_title' => $prophecy->title,
            'language' => $language
        ], 'medium');

        return view('public.prophecy-print', compact('prophecy', 'language'));
    }

    /**
     * Search prophecies.
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:3',
            'category' => 'nullable|exists:categories,id',
            'language' => 'nullable|string|in:en,ta,kn,te,ml,hi',
        ]);

        $query = $request->q;
        $categoryId = $request->category;
        $language = $request->language ?? (Auth::user()->preferred_language ?? 'en');

        $prophecies = Prophecy::published()
            ->public()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->when($categoryId, function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->with(['category', 'translations' => function($q) use ($language) {
                $q->where('language', $language);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = Category::active()->root()->orderBy('sort_order')->get();

        // Log search event
        $this->logSecurityEvent('prophecy_search', Auth::id(), [
            'query' => $query,
            'category_id' => $categoryId,
            'language' => $language,
            'results_count' => $prophecies->total()
        ]);

        return view('public.search-results', compact('prophecies', 'categories', 'query', 'categoryId', 'language'));
    }

    /**
     * Generate secure PDF with watermarks.
     */
    private function generateSecurePDF($prophecy, $language)
    {
        // Get the translation for the specified language
        $translation = $prophecy->translations->where('language', $language)->first();
        
        // If no translation found for the requested language, try to get it from database
        if (!$translation && $language !== 'en') {
            $translation = $prophecy->translations()->where('language', $language)->first();
        }
        
        // If still no translation, fall back to English
        if (!$translation) {
            $translation = $prophecy->translations->where('language', 'en')->first();
        }
        
        // If no English translation either, get any available translation
        if (!$translation) {
            $translation = $prophecy->translations->first();
        }
        
        // Prepare data for PDF with proper encoding
        $data = [
            'prophecy' => $prophecy,
            'translation' => $translation,
            'language' => $language,
            'user' => Auth::user(),
            'generated_at' => now(),
            'download_id' => uniqid('pdf_', true),
            'security_level' => 'PROTECTED'
        ];
        
        // Prepare content for PDF with text-to-image conversion for Indian languages
        $indianLanguages = ['ta', 'te', 'kn', 'ml', 'hi'];
        
        // Debug logging
        \Log::info("PDF Generation Debug", [
            'prophecy_id' => $prophecy->id,
            'language' => $language,
            'translation_exists' => $translation ? 'yes' : 'no',
            'translation_title' => $translation ? $translation->title : 'none',
            'translation_content_length' => $translation && $translation->content ? strlen($translation->content) : 0,
            'is_indian_language' => in_array($language, $indianLanguages) ? 'yes' : 'no'
        ]);
        
        if ($translation && $translation->content) {
            if (in_array($language, $indianLanguages)) {
                // Convert Indian language content to images
                $data['use_text_images'] = true;
                $data['original_content'] = $translation->content;
                
                \Log::info("Converting content to image", [
                    'content_preview' => substr(strip_tags($translation->content), 0, 100),
                    'language' => $language
                ]);
                
                // Convert content to image (smaller size for PDF compatibility)
                $contentImage = TextToImageService::convertTextToImage(
                    strip_tags($translation->content), 
                    $language,
                    ['font_size' => 16, 'width' => 500]
                );
                
                if ($contentImage) {
                    $data['content_image'] = $contentImage;
                    \Log::info("Content image created", ['path' => $contentImage['path']]);
                } else {
                    \Log::error("Failed to create content image");
                }
                
                $languageNames = [
                    'ta' => 'Tamil',
                    'te' => 'Telugu', 
                    'kn' => 'Kannada',
                    'ml' => 'Malayalam',
                    'hi' => 'Hindi'
                ];
                $langName = $languageNames[$language] ?? 'Indian language';
                $data['language_fallback_message'] = "This PDF contains {$langName} text rendered as images for proper display.";
            } else {
                $translation->content = UnicodeService::prepareForPdf($translation->content);
            }
        }
        if ($translation && $translation->title) {
            if (in_array($language, $indianLanguages)) {
                $data['original_title'] = $translation->title;
                
                \Log::info("Converting title to image", [
                    'title' => $translation->title,
                    'language' => $language
                ]);
                
                // Convert title to image (smaller size for PDF compatibility)
                $titleImage = TextToImageService::convertTextToImage(
                    $translation->title, 
                    $language,
                    ['font_size' => 24, 'width' => 500]
                );
                
                if ($titleImage) {
                    $data['title_image'] = $titleImage;
                    \Log::info("Title image created", ['path' => $titleImage['path']]);
                } else {
                    \Log::error("Failed to create title image");
                }
            } else {
                $translation->title = UnicodeService::prepareForPdf($translation->title);
            }
        }
        
        // Also prepare main prophecy content for Unicode
        if ($prophecy->title) {
            $prophecy->title = UnicodeService::prepareForPdf($prophecy->title);
        }
        if ($prophecy->description) {
            $prophecy->description = UnicodeService::prepareForPdf($prophecy->description);
        }
        
        // Generate PDF using DomPDF
        $pdf = Pdf::loadView('pdf.prophecy', $data);
        
        // Configure PDF settings for multilingual support
        $pdf->setPaper('A4', 'portrait');
        
        // Use Arial Unicode MS for all Indian languages as it has the best Unicode support
        $defaultFont = in_array($language, ['ta', 'te', 'kn', 'ml', 'hi']) ? 'Arial Unicode MS' : 'DejaVu Sans';
        
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'isRemoteEnabled' => false,
            'defaultFont' => $defaultFont,
            'dpi' => 150,
            'defaultPaperSize' => 'A4',
            'chroot' => public_path(),
            'fontSubsetting' => true,
            'isFontSubsettingEnabled' => true,
            'isUnicode' => true,
            'fontHeightRatio' => 1.1,
            'enable_font_subsetting' => true,
            'pdf_backend' => 'CPDF',
            'debugKeepTemp' => false,
            'debugCss' => false,
            'debugLayout' => false,
            'debugLayoutLines' => false,
            'debugLayoutBlocks' => false,
            'debugLayoutInline' => false,
            'debugLayoutPaddingBox' => false,
        ]);
        
        // Set additional Unicode options for better multi-language support
        $domPdf = $pdf->getDomPDF();
        $domPdf->getOptions()->set('isUnicode', true);
        $domPdf->getOptions()->set('defaultFont', $defaultFont);
        $domPdf->getOptions()->set('isFontSubsettingEnabled', true);
        
        // Set the DomPDF instance to handle UTF-8 properly
        $domPdf = $pdf->getDomPDF();
        $domPdf->set_option('isPhpEnabled', true);
        $domPdf->set_option('isHtml5ParserEnabled', true);

        // Get the underlying DomPDF instance to set metadata
        $domPdf = $pdf->getDomPDF();
        
        // Set PDF metadata with security information using the correct method
        $domPdf->add_info('Title', ($translation?->title ?? $prophecy->title) . ' - Jebikalam Vaanga Prophecy');
        $domPdf->add_info('Author', 'Jebikalam Vaanga Prophecy System');
        $domPdf->add_info('Subject', 'Divine Prophecy - ' . ($prophecy->category?->name ?? 'General'));
        $domPdf->add_info('Creator', 'Jebikalam Vaanga Prophecy v1.0.0.0');
        $domPdf->add_info('Producer', 'DomPDF with Security Features');
        $domPdf->add_info('Keywords', implode(', ', array_merge(
            ['prophecy', 'christian', 'spiritual', 'revelation'],
            $prophecy->tags ?? []
        )));
        $domPdf->add_info('CreationDate', 'D:' . now()->format('YmdHis') . '+05\'30\''); // IST timezone
        $domPdf->add_info('ModDate', 'D:' . now()->format('YmdHis') . '+05\'30\'');
        
        // Add custom security metadata as a comment in the PDF
        $securityMetadata = json_encode([
            'prophecy_id' => $prophecy->id,
            'jebikalam_vanga_date' => $prophecy->jebikalam_vanga_date?->format('Y-m-d'),
            'language' => $language,
            'download_id' => $data['download_id'],
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email,
            'security_level' => $data['security_level'],
            'generated_at' => now()->toISOString(),
            'system_version' => '1.0.0.0 Build 00027',
            'document_type' => 'prophecy_pdf',
            'access_level' => 'public'
        ]);
        
        // Add security metadata as custom info
        $domPdf->add_info('Security_Metadata', $securityMetadata);
        
        // Generate filename with security elements
        $filename = $this->generateSecureFilename($prophecy, $language);
        
        // Return PDF download response with comprehensive headers (mobile-optimized)
        $pdfContent = $pdf->output();
        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Length', strlen($pdfContent))
            ->header('Content-Transfer-Encoding', 'binary')
            ->header('Content-Description', 'File Transfer')
            ->header('Accept-Ranges', 'bytes')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate, post-check=0, pre-check=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0')
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('X-Download-Options', 'noopen');
    }

    /**
     * Generate secure filename for PDF download.
     */
    private function generateSecureFilename($prophecy, $language)
    {
        $translation = $prophecy->translations->first();
        $title = $translation?->title ?? $prophecy->title;
        
        // Clean title for filename
        $cleanTitle = preg_replace('/[^a-zA-Z0-9\s]/', '', $title);
        $cleanTitle = preg_replace('/\s+/', '_', trim($cleanTitle));
        $cleanTitle = substr($cleanTitle, 0, 50); // Limit length
        
        // Language suffix
        $langSuffix = strtoupper($language);
        
        // Date component
        $date = $prophecy->jebikalam_vanga_date ? 
            Carbon::parse($prophecy->jebikalam_vanga_date)->format('Y-m-d') : 
            'no-date';
        
        // Security ID
        $securityId = substr(md5($prophecy->id . now()->timestamp), 0, 8);
        
        return "JV_Prophecy_{$cleanTitle}_{$date}_{$langSuffix}_{$securityId}.pdf";
    }

    /**
     * Clean HTML content while preserving original formatting.
     */
    private function cleanHtmlForPdf($html)
    {
        if (empty($html)) {
            return $html;
        }
        
        // Only remove Word-specific attributes, preserve everything else
        $html = preg_replace('/\s*(mso-[^=]*)\s*=\s*"[^"]*"/i', '', $html);
        
        // Remove problematic class attributes but keep style attributes
        $html = preg_replace('/\s*class\s*=\s*"[^"]*"/i', '', $html);
        
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
                if (preg_match('/^(mso-|font-family|margin|padding|width|height|position|top|left|right|bottom)\s*:/i', $style)) {
                    // Skip problematic styles that can break layout
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
        
        // Only remove truly empty spans
        $html = preg_replace('/<span[^>]*>\s*<\/span>/i', '', $html);
        
        // Only remove spans that have no attributes AND no content value
        $html = preg_replace('/<span\s*>([^<]+)<\/span>/i', '$1', $html);
        
        // Minimal cleanup - preserve structure
        $html = preg_replace('/\s+/', ' ', $html);
        
        // Remove empty paragraphs
        $html = preg_replace('/<p[^>]*>\s*<\/p>/i', '', $html);
        
        // Don't convert HTML tags - keep original formatting
        // $html = str_replace(['<strong>', '</strong>'], ['<b>', '</b>'], $html);
        // $html = str_replace(['<em>', '</em>'], ['<i>', '</i>'], $html);
        
        return trim($html);
    }
    
    /**
     * Download prophecy as PDF using web2pdf (better Unicode support).
     */
    public function downloadProphecyWeb2Pdf(Request $request, $id)
    {
        $language = $request->language ?? (Auth::user()->preferred_language ?? 'en');

        $prophecy = Prophecy::published()
            ->public()
            ->with(['category', 'creator', 'translations' => function($query) use ($language) {
                $query->where('language', $language);
            }])
            ->findOrFail($id);

        // Increment download count
        $prophecy->incrementDownloadCount();

        // Log download event
        $this->logSecurityEvent('prophecy_web2pdf_download', Auth::id(), [
            'prophecy_id' => $prophecy->id,
            'prophecy_title' => $prophecy->title,
            'language' => $language,
            'format' => 'web2pdf'
        ], 'medium');

        try {
            return $this->generateWeb2PDF($prophecy, $language);
        } catch (\Exception $e) {
            \Log::error('Web2PDF generation failed, falling back to DomPDF', [
                'prophecy_id' => $prophecy->id,
                'language' => $language,
                'error' => $e->getMessage()
            ]);

            // Fallback to original PDF method
            return $this->generateSecurePDF($prophecy, $language);
        }
    }

    /**
     * Download prophecy as JPEG using web2jpeg.
     */
    public function downloadProphecyJpeg(Request $request, $id)
    {
        $language = $request->language ?? (Auth::user()->preferred_language ?? 'en');

        $prophecy = Prophecy::published()
            ->public()
            ->with(['category', 'creator', 'translations' => function($query) use ($language) {
                $query->where('language', $language);
            }])
            ->findOrFail($id);

        // Increment download count
        $prophecy->incrementDownloadCount();

        // Log download event
        $this->logSecurityEvent('prophecy_jpeg_download', Auth::id(), [
            'prophecy_id' => $prophecy->id,
            'prophecy_title' => $prophecy->title,
            'language' => $language,
            'format' => 'jpeg'
        ], 'medium');

        try {
            return $this->generateWeb2JPEG($prophecy, $language);
        } catch (\Exception $e) {
            \Log::error('Web2JPEG generation failed', [
                'prophecy_id' => $prophecy->id,
                'language' => $language,
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Failed to generate JPEG'], 500);
        }
    }

    /**
     * Show web print view for web2pdf conversion.
     */
    public function showWebPrintView(Request $request, $id)
    {
        $language = $request->language ?? (Auth::user()->preferred_language ?? 'en');

        $prophecy = Prophecy::published()
            ->public()
            ->with(['category', 'creator', 'translations' => function($query) use ($language) {
                $query->where('language', $language);
            }])
            ->findOrFail($id);

        // Get the translation for the specified language
        $translation = $prophecy->translations->where('language', $language)->first();
        
        // If no translation found, try to get it from database
        if (!$translation && $language !== 'en') {
            $translation = $prophecy->translations()->where('language', $language)->first();
        }
        
        // If still no translation, fall back to English
        if (!$translation) {
            $translation = $prophecy->translations->where('language', 'en')->first();
        }
        
        // If no English translation either, get any available translation
        if (!$translation) {
            $translation = $prophecy->translations->first();
        }

        $data = [
            'prophecy' => $prophecy,
            'translation' => $translation,
            'language' => $language,
            'user' => Auth::user(),
            'generated_at' => now(),
            'download_id' => uniqid('pdf_', true),
            'security_level' => 'PROTECTED'
        ];

        return view('pdf.web-print', $data);
    }

    /**
     * Generate PDF using web2pdf service.
     */
    private function generateWeb2PDF($prophecy, $language)
    {
        $web2pdfService = new Web2PdfService();

        // Check if service is available
        if (!$web2pdfService->isAvailable()) {
            throw new \Exception('Web2PDF service is not available');
        }

        // Generate URL for the web print view
        $printUrl = route('prophecy.web-print', ['id' => $prophecy->id, 'language' => $language]);

        // Add authentication token to URL for secure access
        $user = Auth::user();
        if ($user) {
            $printUrl .= '?token=' . encrypt([
                'user_id' => $user->id,
                'prophecy_id' => $prophecy->id,
                'expires_at' => now()->addMinutes(10)->timestamp
            ]);
        }

        // PDF generation options
        $options = [
            'format' => 'A4',
            'orientation' => 'portrait',
            'margin' => [
                'top' => '1cm',
                'right' => '1cm',
                'bottom' => '1cm',
                'left' => '1cm'
            ],
            'printBackground' => true,
            'displayHeaderFooter' => false,
            'waitUntilNetworkIdle' => true,
            'timeout' => 60,
            'scale' => 1.0
        ];

        // Generate PDF content
        $pdfContent = $web2pdfService->generatePdfFromUrl($printUrl, null, $options);

        // Generate secure filename
        $filename = $this->generateSecureFilename($prophecy, $language);

        // Return PDF download response
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Generate JPEG using web2jpeg service.
     */
    private function generateWeb2JPEG($prophecy, $language)
    {
        $web2pdfService = new Web2PdfService();

        // Check if service is available
        if (!$web2pdfService->isAvailable()) {
            throw new \Exception('Web2JPEG service is not available');
        }

        // Generate URL for the web print view
        $printUrl = route('prophecy.web-print', ['id' => $prophecy->id, 'language' => $language]);

        // Add authentication token to URL for secure access
        $user = Auth::user();
        if ($user) {
            $printUrl .= '?token=' . encrypt([
                'user_id' => $user->id,
                'prophecy_id' => $prophecy->id,
                'expires_at' => now()->addMinutes(10)->timestamp
            ]);
        }

        // JPEG generation options
        $options = [
            'width' => 1200,
            'height' => 1600,
            'quality' => 90,
            'fullPage' => true,
            'waitUntilNetworkIdle' => true,
            'timeout' => 60
        ];

        // Generate JPEG content
        $jpegContent = $web2pdfService->generateJpegFromUrl($printUrl, null, $options);

        // Generate secure filename
        $filename = str_replace('.pdf', '.jpg', $this->generateSecureFilename($prophecy, $language));

        // Return JPEG download response
        return response($jpegContent)
            ->header('Content-Type', 'image/jpeg')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Log security events.
     */
    private function logSecurityEvent($eventType, $userId = null, $metadata = [], $severity = 'low')
    {
        SecurityLog::create([
            'user_id' => $userId,
            'event_type' => $eventType,
            'resource_type' => 'prophecy',
            'resource_id' => $metadata['prophecy_id'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => $metadata,
            'severity' => $severity,
            'event_time' => now(),
        ]);
    }
}
