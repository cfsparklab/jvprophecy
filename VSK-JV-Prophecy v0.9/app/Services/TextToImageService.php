<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TextToImageService
{
    /**
     * Convert text to image for PDF embedding
     */
    public static function convertTextToImage($text, $language = 'en', $options = [])
    {
        if (empty($text) || $language === 'en') {
            return null; // Don't convert English text
        }

        // Default options
        $defaults = [
            'font_size' => 24,
            'width' => 800,
            'line_height' => 1.5,
            'background_color' => '#ffffff',
            'text_color' => '#333333',
            'padding' => 20,
        ];
        
        $options = array_merge($defaults, $options);
        
        try {
            // Create image manager with GD driver
            $manager = new ImageManager(new Driver());
            
            // Calculate text dimensions and wrap text
            $lines = self::wrapText($text, $options['width'] - ($options['padding'] * 2), $options['font_size']);
            
            // Calculate required height
            $lineHeight = $options['font_size'] * $options['line_height'];
            $totalHeight = (count($lines) * $lineHeight) + ($options['padding'] * 2);
            
            // Create image with correct dimensions
            $img = $manager->create($options['width'], $totalHeight)->fill($options['background_color']);
            
            // Use GD functions to add text (since Intervention Image v3 text method is different)
            $gdImage = $img->core()->native();
            
            // Convert hex color to RGB
            $textColor = self::hexToRgb($options['text_color']);
            $gdTextColor = imagecolorallocate($gdImage, $textColor[0], $textColor[1], $textColor[2]);
            
            // Add text lines using GD
            $y = $options['padding'] + $options['font_size'];
            foreach ($lines as $line) {
                imagestring($gdImage, 5, $options['padding'], $y - $options['font_size'], $line, $gdTextColor);
                $y += $lineHeight;
            }
            
            // Generate unique filename
            $filename = 'text_images/' . $language . '_' . md5($text . serialize($options)) . '.png';
            
            // Save image
            $imagePath = storage_path('app/public/' . $filename);
            
            // Ensure directory exists
            $directory = dirname($imagePath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            $img->save($imagePath);
            
            // Create base64 encoded version for PDF embedding
            $imageData = base64_encode(file_get_contents($imagePath));
            $base64Image = 'data:image/png;base64,' . $imageData;
            
            return [
                'path' => $imagePath,
                'url' => Storage::url($filename),
                'base64' => $base64Image,
                'width' => $options['width'],
                'height' => $totalHeight,
                'filename' => $filename
            ];
            
        } catch (\Exception $e) {
            \Log::error('Text to image conversion failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Convert hex color to RGB array
     */
    private static function hexToRgb($hex)
    {
        $hex = ltrim($hex, '#');
        
        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        
        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        ];
    }
    
    /**
     * Wrap text to fit within specified width
     */
    private static function wrapText($text, $maxWidth, $fontSize)
    {
        // Simple text wrapping - split by words and approximate character width
        $words = explode(' ', $text);
        $lines = [];
        $currentLine = '';
        
        // Approximate character width (adjust based on font)
        $charWidth = $fontSize * 0.6;
        $maxCharsPerLine = floor($maxWidth / $charWidth);
        
        foreach ($words as $word) {
            $testLine = $currentLine . ($currentLine ? ' ' : '') . $word;
            
            if (strlen($testLine) <= $maxCharsPerLine) {
                $currentLine = $testLine;
            } else {
                if ($currentLine) {
                    $lines[] = $currentLine;
                }
                $currentLine = $word;
            }
        }
        
        if ($currentLine) {
            $lines[] = $currentLine;
        }
        
        return $lines ?: [''];
    }
    
    /**
     * Clean up generated images
     */
    public static function cleanupImages($olderThan = 3600)
    {
        $directory = storage_path('app/public/text_images');
        
        if (!is_dir($directory)) {
            return;
        }
        
        $files = glob($directory . '/*.png');
        $cutoff = time() - $olderThan;
        
        foreach ($files as $file) {
            if (filemtime($file) < $cutoff) {
                unlink($file);
            }
        }
    }
}
