<?php

namespace App\Services;

use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class Web2PdfService
{
    /**
     * Generate PDF from web URL using headless browser
     */
    public function generatePdfFromUrl($url, $filename = null, $options = [])
    {
        try {
            Log::info('Web2PDF: Starting PDF generation', [
                'url' => $url,
                'filename' => $filename,
                'options' => $options
            ]);

            // Default options
            $defaultOptions = [
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

            $options = array_merge($defaultOptions, $options);

            // Create browsershot instance
            $browsershot = Browsershot::url($url)
                ->format($options['format'])
                ->margins(
                    $options['margin']['top'],
                    $options['margin']['right'],
                    $options['margin']['bottom'],
                    $options['margin']['left']
                )
                ->showBackground($options['printBackground'])
                ->waitUntilNetworkIdle($options['waitUntilNetworkIdle'])
                ->timeout($options['timeout'])
                ->scale($options['scale']);

            // Set orientation
            if ($options['orientation'] === 'landscape') {
                $browsershot->landscape();
            }

            // Add header/footer if specified
            if ($options['displayHeaderFooter']) {
                $browsershot->showBrowserHeaderAndFooter();
                
                if (isset($options['headerTemplate'])) {
                    $browsershot->headerHtml($options['headerTemplate']);
                }
                
                if (isset($options['footerTemplate'])) {
                    $browsershot->footerHtml($options['footerTemplate']);
                }
            }

            // Add custom CSS if provided
            if (isset($options['css'])) {
                $browsershot->addChromiumArguments(['--disable-web-security']);
                // Note: Custom CSS injection would need to be handled differently
            }

            // Generate PDF
            $pdfContent = $browsershot->pdf();

            Log::info('Web2PDF: PDF generated successfully', [
                'url' => $url,
                'size' => strlen($pdfContent) . ' bytes'
            ]);

            return $pdfContent;

        } catch (Exception $e) {
            Log::error('Web2PDF: PDF generation failed', [
                'url' => $url,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw new Exception('Failed to generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF from HTML content
     */
    public function generatePdfFromHtml($html, $filename = null, $options = [])
    {
        try {
            Log::info('Web2PDF: Starting PDF generation from HTML', [
                'filename' => $filename,
                'html_length' => strlen($html),
                'options' => $options
            ]);

            // Default options
            $defaultOptions = [
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
                'waitUntilNetworkIdle' => false,
                'timeout' => 60,
                'scale' => 1.0
            ];

            $options = array_merge($defaultOptions, $options);

            // Create browsershot instance
            $browsershot = Browsershot::html($html)
                ->format($options['format'])
                ->margins(
                    $options['margin']['top'],
                    $options['margin']['right'],
                    $options['margin']['bottom'],
                    $options['margin']['left']
                )
                ->showBackground($options['printBackground'])
                ->timeout($options['timeout'])
                ->scale($options['scale']);

            // Set orientation
            if ($options['orientation'] === 'landscape') {
                $browsershot->landscape();
            }

            // Generate PDF
            $pdfContent = $browsershot->pdf();

            Log::info('Web2PDF: PDF generated successfully from HTML', [
                'size' => strlen($pdfContent) . ' bytes'
            ]);

            return $pdfContent;

        } catch (Exception $e) {
            Log::error('Web2PDF: PDF generation from HTML failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw new Exception('Failed to generate PDF from HTML: ' . $e->getMessage());
        }
    }

    /**
     * Generate JPEG from web URL
     */
    public function generateJpegFromUrl($url, $filename = null, $options = [])
    {
        try {
            Log::info('Web2JPEG: Starting JPEG generation', [
                'url' => $url,
                'filename' => $filename,
                'options' => $options
            ]);

            // Default options
            $defaultOptions = [
                'width' => 1200,
                'height' => 1600,
                'quality' => 90,
                'fullPage' => true,
                'waitUntilNetworkIdle' => true,
                'timeout' => 60
            ];

            $options = array_merge($defaultOptions, $options);

            // Create browsershot instance
            $browsershot = Browsershot::url($url)
                ->windowSize($options['width'], $options['height'])
                ->waitUntilNetworkIdle($options['waitUntilNetworkIdle'])
                ->timeout($options['timeout']);

            if ($options['fullPage']) {
                $browsershot->fullPage();
            }

            // Generate JPEG
            $jpegContent = $browsershot->screenshot();

            Log::info('Web2JPEG: JPEG generated successfully', [
                'url' => $url,
                'size' => strlen($jpegContent) . ' bytes'
            ]);

            return $jpegContent;

        } catch (Exception $e) {
            Log::error('Web2JPEG: JPEG generation failed', [
                'url' => $url,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw new Exception('Failed to generate JPEG: ' . $e->getMessage());
        }
    }

    /**
     * Check if Puppeteer/Chrome is available
     */
    public function isAvailable()
    {
        try {
            // Try to create a simple browsershot instance
            $browsershot = Browsershot::html('<html><body>Test</body></html>')
                ->timeout(10);
            
            // Try to generate a small PDF to test availability
            $browsershot->pdf();
            
            return true;
        } catch (Exception $e) {
            Log::warning('Web2PDF: Service not available', [
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Get system requirements and status
     */
    public function getSystemStatus()
    {
        $status = [
            'browsershot_installed' => class_exists('Spatie\Browsershot\Browsershot'),
            'service_available' => false,
            'chrome_path' => null,
            'node_path' => null,
            'requirements' => [
                'php_version' => PHP_VERSION,
                'required_extensions' => [
                    'curl' => extension_loaded('curl'),
                    'json' => extension_loaded('json'),
                    'mbstring' => extension_loaded('mbstring')
                ]
            ],
            'errors' => []
        ];

        try {
            $status['service_available'] = $this->isAvailable();
        } catch (Exception $e) {
            $status['errors'][] = $e->getMessage();
        }

        return $status;
    }
}
