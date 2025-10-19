@props([
    'name' => 'content',
    'id' => null,
    'value' => '',
    'placeholder' => 'Enter content...',
    'height' => '300px',
    'required' => false,
    'class' => ''
])

@php
    $editorId = $id ?? 'ckeditor_' . $name . '_' . uniqid();
@endphp

<div class="tinymce-wrapper {{ $class }}">
    <!-- TinyMCE Textarea -->
    <textarea name="{{ $name }}" id="{{ $editorId }}" style="min-height: {{ $height }};" {{ $required ? 'required' : '' }}>{{ $value }}</textarea>
</div>

@once
@push('styles')
<style>
    .tinymce-wrapper {
        border-radius: 0.375rem;
    }
    
    .tinymce-wrapper textarea {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-family: 'Noto Sans', 'DejaVu Sans', 'Arial Unicode MS', Arial, sans-serif;
        line-height: 1.6;
    }
    
    .tinymce-wrapper textarea:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }
    
    /* TinyMCE Intel corporate styling */
    .tox .tox-toolbar {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%) !important;
        border-bottom: 1px solid #cbd5e1 !important;
    }
    
    .tox .tox-tbtn:hover {
        background: #e2e8f0 !important;
    }
    
    .tox .tox-tbtn--enabled {
        background: #dbeafe !important;
        color: #1e40af !important;
    }
    
    /* Multi-language font support in TinyMCE */
    .tox-edit-area iframe {
        font-family: 'Noto Sans', 'DejaVu Sans', 'Arial Unicode MS', Arial, sans-serif !important;
    }
    
    .tox .tox-edit-area__iframe[lang="ta"],
    .tox .tox-edit-area__iframe .tamil-text {
        font-family: 'Noto Sans Tamil', 'Latha', 'Vijaya', 'DejaVu Sans', Arial, sans-serif !important;
        font-size: 16px !important;
        line-height: 1.8 !important;
    }
    
    .tox .tox-edit-area__iframe[lang="kn"],
    .tox .tox-edit-area__iframe .kannada-text {
        font-family: 'Noto Sans Kannada', 'DejaVu Sans', Arial, sans-serif !important;
        font-size: 16px !important;
    }
    
    .tox .tox-edit-area__iframe[lang="te"],
    .tox .tox-edit-area__iframe .telugu-text {
        font-family: 'Noto Sans Telugu', 'DejaVu Sans', Arial, sans-serif !important;
        font-size: 16px !important;
    }
    
    .tox .tox-edit-area__iframe[lang="ml"],
    .tox .tox-edit-area__iframe .malayalam-text {
        font-family: 'Noto Sans Malayalam', 'DejaVu Sans', Arial, sans-serif !important;
        font-size: 16px !important;
    }
    
    .tox .tox-edit-area__iframe[lang="hi"],
    .tox .tox-edit-area__iframe .hindi-text {
        font-family: 'Noto Sans Devanagari', 'DejaVu Sans', Arial, sans-serif !important;
        font-size: 16px !important;
    }
    
    /* Fix for translations page layout */
    .tox-tinymce {
        border: 1px solid #d1d5db !important;
        border-radius: 0.375rem !important;
        overflow: hidden !important;
    }
    
    .tox .tox-toolbar-overlord {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%) !important;
    }
    
    .tox .tox-editor-header {
        border-bottom: 1px solid #cbd5e1 !important;
        box-shadow: none !important;
    }
    
    .tox .tox-menubar {
        display: none !important;
    }
    
    /* Ensure proper z-index for dropdowns */
    .tox .tox-collection {
        z-index: 9999 !important;
    }
    
    .tox .tox-pop {
        z-index: 9999 !important;
    }
    
    /* Fix for tabbed interface */
    .tox-tinymce-aux {
        z-index: 9999 !important;
    }
    
    /* Ensure editor fits properly in container */
    .tinymce-wrapper .tox-tinymce {
        width: 100% !important;
    }
    
    /* Fix editor height */
    .tox .tox-edit-area {
        border: none !important;
    }
    
    .tox .tox-edit-area__iframe {
        background: white !important;
    }
</style>
@endpush

@push('scripts')
<!-- TinyMCE Self-hosted -->
<script src="{{ asset('assets/tinymce/tinymce/js/tinymce/tinymce.min.js') }}"></script>
@endpush
@endonce

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize TinyMCE for {{ $editorId }}
    function initTinyMCE() {
        // Check if element exists and is visible
        const element = document.querySelector('#{{ $editorId }}');
        if (!element) {
            setTimeout(initTinyMCE, 100);
            return;
        }
        
        // Remove any existing instance
        if (tinymce.get('{{ $editorId }}')) {
            tinymce.get('{{ $editorId }}').remove();
        }
        
        tinymce.init({
            selector: '#{{ $editorId }}',
            height: {{ str_replace('px', '', $height ?? '400') }},
            menubar: false,
            resize: false,
            statusbar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons',
                'codesample'
            ],
            toolbar: 'undo redo | formatselect | fontsize fontfamily | ' +
                    'bold italic underline strikethrough | forecolor backcolor | ' +
                    'alignleft aligncenter alignright alignjustify | ' +
                    'bullist numlist outdent indent | ' +
                    'removeformat | link image media table | ' +
                    'searchreplace code fullscreen | help',
            font_size_formats: '8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 20pt 22pt 24pt 26pt 28pt 32pt 36pt 48pt 72pt',
            font_family_formats: 'Arial=arial,helvetica,sans-serif; ' +
                                'Times New Roman=times new roman,times,serif; ' +
                                'Courier New=courier new,courier,monospace; ' +
                                'Georgia=georgia,serif; ' +
                                'Verdana=verdana,geneva,sans-serif; ' +
                                'Tahoma=tahoma,geneva,sans-serif; ' +
                                'Trebuchet MS=trebuchet ms,helvetica,sans-serif; ' +
                                'Tamil=Noto Sans Tamil,Tamil,sans-serif; ' +
                                'Kannada=Noto Sans Kannada,Kannada,sans-serif; ' +
                                'Telugu=Noto Sans Telugu,Telugu,sans-serif; ' +
                                'Malayalam=Noto Sans Malayalam,Malayalam,sans-serif; ' +
                                'Hindi=Noto Sans Devanagari,Hindi,sans-serif',
            color_map: [
                '000000', 'Black',
                '4D4D4D', 'Dark Gray',
                '999999', 'Gray',
                'CCCCCC', 'Light Gray',
                'FFFFFF', 'White',
                'FF0000', 'Red',
                'FF9900', 'Orange',
                'FFFF00', 'Yellow',
                '00FF00', 'Green',
                '00FFFF', 'Cyan',
                '0000FF', 'Blue',
                '9900FF', 'Purple',
                'FF00FF', 'Magenta'
            ],
            content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6; margin: 10px; }',
            placeholder: '{{ $placeholder }}',
            branding: false,
            promotion: false,
            license_key: 'gpl',
            init_instance_callback: function (editor) {
                // Store editor instance
                window.tinymce_{{ str_replace(['-', '.'], '_', $editorId) }} = editor;
                
                // Auto-save content
                editor.on('change keyup', function () {
                    editor.save();
                });
                
                // Handle tab switching for translations page
                editor.on('init', function() {
                    // Ensure editor is properly sized
                    setTimeout(function() {
                        editor.getContainer().style.width = '100%';
                    }, 100);
                });
                
                console.log('TinyMCE initialized for {{ $editorId }}');
            }
        });
    }
    
    // Initialize immediately or wait for element
    initTinyMCE();
    
    // Re-initialize when tab becomes visible (for Alpine.js tabs)
    document.addEventListener('alpine:init', () => {
        Alpine.store('tinymce', {
            reinitEditor(editorId) {
                if (editorId === '{{ $editorId }}') {
                    setTimeout(() => {
                        if (tinymce.get('{{ $editorId }}')) {
                            tinymce.get('{{ $editorId }}').remove();
                        }
                        initTinyMCE();
                    }, 100);
                }
            }
        });
    });
});
</script>
@endpush
