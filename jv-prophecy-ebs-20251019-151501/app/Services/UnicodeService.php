<?php

namespace App\Services;

class UnicodeService
{
    /**
     * Ensure proper UTF-8 encoding for text content
     */
    public static function ensureUtf8($text)
    {
        if (empty($text)) {
            return $text;
        }

        // Check if already valid UTF-8
        if (mb_check_encoding($text, 'UTF-8')) {
            return $text;
        }

        // Try to convert from common encodings
        $encodings = ['UTF-8', 'ISO-8859-1', 'Windows-1252', 'ASCII'];
        
        foreach ($encodings as $encoding) {
            $converted = mb_convert_encoding($text, 'UTF-8', $encoding);
            if (mb_check_encoding($converted, 'UTF-8')) {
                return $converted;
            }
        }

        // Fallback: force UTF-8 conversion
        return mb_convert_encoding($text, 'UTF-8', 'UTF-8');
    }

    /**
     * Clean and normalize Unicode text for database storage
     */
    public static function normalizeForDatabase($text)
    {
        if (empty($text)) {
            return $text;
        }

        // Ensure UTF-8 encoding
        $text = self::ensureUtf8($text);

        // Normalize Unicode (NFC - Canonical Decomposition followed by Canonical Composition)
        if (class_exists('Normalizer')) {
            $text = \Normalizer::normalize($text, \Normalizer::FORM_C);
        }

        return $text;
    }

    /**
     * Prepare text for PDF generation with proper encoding
     */
    public static function prepareForPdf($text)
    {
        if (empty($text)) {
            return $text;
        }

        // Ensure UTF-8 encoding
        $text = self::ensureUtf8($text);

        // Convert problematic characters that might not render well in PDF
        $replacements = [
            // Smart quotes
            "\u{201C}" => '"',  // Left double quotation mark
            "\u{201D}" => '"',  // Right double quotation mark
            "\u{2018}" => "'",  // Left single quotation mark
            "\u{2019}" => "'",  // Right single quotation mark
            
            // Dashes
            "\u{2013}" => '-',  // En dash
            "\u{2014}" => '-',  // Em dash
            
            // Other common problematic characters
            "\u{2026}" => '...',  // Horizontal ellipsis
            "\u{2122}" => '(TM)', // Trade mark sign
            "\u{00AE}" => '(R)',  // Registered sign
            "\u{00A9}" => '(C)',  // Copyright sign
        ];

        foreach ($replacements as $search => $replace) {
            $text = str_replace($search, $replace, $text);
        }

        return $text;
    }

    /**
     * Get appropriate font family for a given language
     */
    public static function getFontForLanguage($language)
    {
        $fontMap = [
            'ta' => "'Noto Sans Tamil', 'Latha', 'Vijaya', 'DejaVu Sans', Arial, sans-serif",
            'kn' => "'Noto Sans Kannada', 'DejaVu Sans', Arial, sans-serif",
            'te' => "'Noto Sans Telugu', 'DejaVu Sans', Arial, sans-serif",
            'ml' => "'Noto Sans Malayalam', 'DejaVu Sans', Arial, sans-serif",
            'hi' => "'Noto Sans Devanagari', 'DejaVu Sans', Arial, sans-serif",
            'en' => "'Noto Sans', 'DejaVu Sans', 'Arial Unicode MS', Arial, sans-serif",
        ];

        return $fontMap[$language] ?? $fontMap['en'];
    }

    /**
     * Detect the language of text content
     */
    public static function detectLanguage($text)
    {
        if (empty($text)) {
            return 'en';
        }

        // Simple language detection based on Unicode ranges
        $text = mb_substr($text, 0, 100); // Check first 100 characters

        // Tamil (U+0B80–U+0BFF)
        if (preg_match('/[\x{0B80}-\x{0BFF}]/u', $text)) {
            return 'ta';
        }

        // Kannada (U+0C80–U+0CFF)
        if (preg_match('/[\x{0C80}-\x{0CFF}]/u', $text)) {
            return 'kn';
        }

        // Telugu (U+0C00–U+0C7F)
        if (preg_match('/[\x{0C00}-\x{0C7F}]/u', $text)) {
            return 'te';
        }

        // Malayalam (U+0D00–U+0D7F)
        if (preg_match('/[\x{0D00}-\x{0D7F}]/u', $text)) {
            return 'ml';
        }

        // Hindi/Devanagari (U+0900–U+097F)
        if (preg_match('/[\x{0900}-\x{097F}]/u', $text)) {
            return 'hi';
        }

        // Default to English
        return 'en';
    }

    /**
     * Set proper HTTP headers for Unicode content
     */
    public static function setUnicodeHeaders()
    {
        if (!headers_sent()) {
            header('Content-Type: text/html; charset=UTF-8');
            header('Cache-Control: no-cache, must-revalidate');
            header('Pragma: no-cache');
        }
    }

    /**
     * Validate and clean HTML content for multi-language support
     */
    public static function cleanHtmlForMultiLanguage($html)
    {
        if (empty($html)) {
            return $html;
        }

        // Ensure UTF-8 encoding
        $html = self::ensureUtf8($html);

        // Remove problematic encoding declarations
        $html = preg_replace('/<meta[^>]*charset[^>]*>/i', '', $html);

        // Clean up Word-specific formatting that can break Unicode
        $html = preg_replace('/\s*(class|lang|mso-[^=]*)\s*=\s*"[^"]*"/i', '', $html);

        // Fix malformed HTML with multiple style attributes
        $html = preg_replace_callback('/<([^>]+)>/i', function($matches) {
            $tag = $matches[1];
            
            // Check if tag has multiple style attributes
            if (preg_match_all('/style\s*=\s*"([^"]*)"/i', $tag, $styleMatches)) {
                if (count($styleMatches[0]) > 1) {
                    // Remove all style attributes first
                    $cleanTag = preg_replace('/\s*style\s*=\s*"[^"]*"/i', '', $tag);
                    
                    // Combine all style values
                    $allStyles = [];
                    foreach ($styleMatches[1] as $styleValue) {
                        $styleArray = explode(';', $styleValue);
                        foreach ($styleArray as $style) {
                            $style = trim($style);
                            if (!empty($style) && strpos($style, ':') !== false) {
                                list($property, $value) = explode(':', $style, 2);
                                $property = trim($property);
                                $value = trim($value);
                                
                                // Keep essential formatting styles only
                                if (preg_match('/^(color|background-color|font-weight|font-style|text-decoration)$/i', $property)) {
                                    $allStyles[$property] = $value;
                                }
                            }
                        }
                    }
                    
                    // Add combined style attribute if we have styles
                    if (!empty($allStyles)) {
                        $combinedStyles = [];
                        foreach ($allStyles as $property => $value) {
                            $combinedStyles[] = $property . ': ' . $value;
                        }
                        $cleanTag .= ' style="' . implode('; ', $combinedStyles) . '"';
                    }
                    
                    return '<' . $cleanTag . '>';
                }
            }
            
            // For tags with single style attribute, clean them normally
            return preg_replace_callback('/style\s*=\s*"([^"]*)"/i', function($styleMatches) {
                $styles = $styleMatches[1];
                $styleArray = explode(';', $styles);
                $cleanStyles = [];
                
                foreach ($styleArray as $style) {
                    $style = trim($style);
                    if (empty($style)) continue;
                    
                    // Keep essential formatting styles
                    if (preg_match('/^(color|background-color|font-weight|font-style|text-decoration)\s*:/i', $style)) {
                        $cleanStyles[] = $style;
                    }
                    // Remove problematic layout styles
                    elseif (preg_match('/^(mso-|font-family|margin|padding|width|height|position|top|left|right|bottom|line-height|font-size)\s*:/i', $style)) {
                        continue;
                    }
                    // Keep other safe styles
                    else {
                        $cleanStyles[] = $style;
                    }
                }
                
                if (!empty($cleanStyles)) {
                    return 'style="' . implode('; ', $cleanStyles) . '"';
                }
                return '';
            }, $matches[0]);
        }, $html);

        return $html;
    }
}
