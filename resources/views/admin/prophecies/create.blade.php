@extends('layouts.admin')

@section('page-title', 'Create New Prophecy')

@section('admin-content')
<!-- Page Header -->
<div class="intel-page-header">
    <div class="intel-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="intel-page-title">
                    <i class="fas fa-plus"></i>
                    Create New Prophecy
                </h1>
                <p class="intel-page-subtitle">Add a new prophecy to the system</p>
            </div>
            <a href="{{ route('admin.prophecies.index') }}" class="intel-btn intel-btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back to List
            </a>
        </div>
    </div>
</div>

<div class="intel-container">
    <form method="POST" action="{{ route('admin.prophecies.store') }}" enctype="multipart/form-data">
        @csrf
        
        <!-- Basic Information -->
        <div class="intel-form-section">
            <div class="intel-form-section-header">
                <h2 class="intel-form-section-title">
                    <i class="fas fa-info-circle"></i>
                    Basic Information
                </h2>
                <p class="intel-form-section-subtitle">Core prophecy details and metadata</p>
            </div>
            <div class="intel-form-section-body">
                <div class="intel-form-grid">
                    <!-- Title -->
                    <div class="intel-form-group">
                        <label for="title" class="intel-form-label">
                            Title <span style="color: var(--error-color);">*</span>
                        </label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               required
                               value="{{ old('title') }}"
                               class="intel-form-input @error('title') error @enderror"
                               placeholder="Enter prophecy title">
                        @error('title')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Jebikalam Vaanga Date -->
                    <div class="intel-form-group">
                        <label for="jebikalam_vanga_date" class="intel-form-label">
                            Jebikalam Vaanga Date <span style="color: var(--error-color);">*</span>
                        </label>
                        <input type="date" 
                               id="jebikalam_vanga_date" 
                               name="jebikalam_vanga_date" 
                               required
                               value="{{ old('jebikalam_vanga_date', now()->format('Y-m-d')) }}"
                               class="intel-form-input @error('jebikalam_vanga_date') error @enderror">
                        @error('jebikalam_vanga_date')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Week Number -->
                    <div class="intel-form-group">
                        <label for="week_number" class="intel-form-label">
                            Week Number <span style="color: var(--error-color);">*</span>
                        </label>
                        <input type="number" 
                               id="week_number" 
                               name="week_number" 
                               required
                               min="1"
                               value="{{ old('week_number') }}"
                               class="intel-form-input @error('week_number') error @enderror"
                               placeholder="Enter week number (e.g., 1, 2, 3...)">
                        @error('week_number')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                        <p class="intel-form-help">Continuous week number across all prophecies (1st prophecy = Week 1, 2nd = Week 2, etc.)</p>
                    </div>
                    
                    <!-- Category -->
                    <div class="intel-form-group">
                        <label for="category_id" class="intel-form-label">Category</label>
                        <select id="category_id" name="category_id" class="intel-form-select @error('category_id') error @enderror">
                            <option value="">Select a category</option>
                            @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                            <!-- Sample categories for demo -->
                            <option value="1" {{ old('category_id') == '1' ? 'selected' : '' }}>FAMILY</option>
                            <option value="2" {{ old('category_id') == '2' ? 'selected' : '' }}>General Prophecies</option>
                            <option value="3" {{ old('category_id') == '3' ? 'selected' : '' }}>End Times</option>
                            <option value="4" {{ old('category_id') == '4' ? 'selected' : '' }}>Healing & Miracles</option>
                            <option value="5" {{ old('category_id') == '5' ? 'selected' : '' }}>Church & Ministry</option>
                        </select>
                        @error('category_id')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Status -->
                    <div class="intel-form-group">
                        <label for="status" class="intel-form-label">
                            Status <span style="color: var(--error-color);">*</span>
                        </label>
                        <select id="status" name="status" required class="intel-form-select @error('status') error @enderror">
                            <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        @error('status')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Visibility -->
                    <div class="intel-form-group">
                        <label for="visibility" class="intel-form-label">Visibility</label>
                        <select id="visibility" name="visibility" class="intel-form-select @error('visibility') error @enderror">
                            <option value="public" {{ old('visibility', 'public') === 'public' ? 'selected' : '' }}>Public</option>
                            <option value="private" {{ old('visibility') === 'private' ? 'selected' : '' }}>Private</option>
                            <option value="restricted" {{ old('visibility') === 'restricted' ? 'selected' : '' }}>Restricted</option>
                        </select>
                        @error('visibility')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Tags -->
                    <div class="intel-form-group">
                        <label for="tags" class="intel-form-label">Tags</label>
                        <input type="text" 
                               id="tags" 
                               name="tags"
                               value="{{ old('tags') }}"
                               class="intel-form-input @error('tags') error @enderror"
                               placeholder="healing, miracles, divine">
                        <p class="intel-form-help">Separate multiple tags with commas</p>
                        @error('tags')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Video URL -->
                    <div class="intel-form-group">
                        <label for="video_url" class="intel-form-label">
                            <i class="fas fa-video" style="margin-right: var(--space-xs);"></i>
                            Video URL
                        </label>
                        <input type="url" 
                               id="video_url" 
                               name="video_url"
                               value="{{ old('video_url') }}"
                               class="intel-form-input @error('video_url') error @enderror"
                               placeholder="https://www.youtube.com/watch?v=... or https://vimeo.com/...">
                        <p class="intel-form-help">Optional: YouTube, Vimeo, or other video URL for this prophecy</p>
                        @error('video_url')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Content Section -->
        <div class="intel-form-section">
            <div class="intel-form-section-header">
                <h2 class="intel-form-section-title">
                    <i class="fas fa-file-text"></i>
                    Prophecy Content
                </h2>
                <p class="intel-form-section-subtitle">Main prophecy text and excerpt</p>
            </div>
            <div class="intel-form-section-body">
                <!-- Excerpt -->
                <div class="intel-form-group">
                    <label for="excerpt" class="intel-form-label">Excerpt</label>
                    <textarea id="excerpt" 
                              name="excerpt" 
                              rows="3"
                              class="intel-form-textarea @error('excerpt') error @enderror"
                              placeholder="Brief summary of the prophecy">{{ old('excerpt') }}</textarea>
                    <p class="intel-form-help">Brief summary (max 500 characters)</p>
                    @error('excerpt')
                    <p class="intel-form-error">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Main Content -->
                <div class="intel-form-group">
                    <label for="description" class="intel-form-label">
                        Prophecy Content <span style="color: var(--error-color);">*</span>
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="15"
                              required
                              class="intel-form-textarea @error('description') error @enderror"
                              placeholder="Enter the full prophecy content">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="intel-form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Prayer Points Section -->
        <div class="intel-form-section">
            <div class="intel-form-section-header">
                <h2 class="intel-form-section-title">
                    <i class="fas fa-praying-hands"></i>
                    Prayer Points
                </h2>
                <p class="intel-form-section-subtitle">Add specific prayer points for this prophecy using rich text editor</p>
            </div>
            <div class="intel-form-section-body">
                <div class="intel-form-group">
                    <label for="prayer_points_editor" class="intel-form-label">
                        Prayer Points Content
                    </label>
                    <textarea id="prayer_points_editor" 
                              name="prayer_points_content" 
                              rows="10"
                              class="intel-form-textarea @error('prayer_points_content') error @enderror"
                              placeholder="Enter prayer points using the rich text editor. You can format text, add lists, and structure your prayer points.">{{ old('prayer_points_content') }}</textarea>
                    <p class="intel-form-help">Use the rich text editor to format your prayer points with lists, bold text, and other formatting options</p>
                    @error('prayer_points_content')
                    <p class="intel-form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Featured Image Section -->
        <div class="intel-form-section">
            <div class="intel-form-section-header">
                <h2 class="intel-form-section-title">
                    <i class="fas fa-image"></i>
                    Featured Image / Thumbnail
                </h2>
                <p class="intel-form-section-subtitle">Upload a thumbnail image for this prophecy</p>
            </div>
            <div class="intel-form-section-body">
                <div class="intel-form-group">
                    <label for="featured_image" class="intel-form-label">
                        <i class="fas fa-image mr-2"></i>
                        Featured Image
                    </label>
                    <input type="file" 
                           id="featured_image" 
                           name="featured_image" 
                           accept="image/jpeg,image/jpg,image/png,image/webp"
                           class="intel-form-input @error('featured_image') error @enderror"
                           onchange="handleImageUpload(this)">
                    <p class="intel-form-help">Upload a featured image (JPEG, PNG, WEBP - Max: 5MB). Recommended size: 800x1200px (portrait). This will be displayed on the prophecy detail page.</p>
                    @error('featured_image')
                    <p class="intel-form-error">{{ $message }}</p>
                    @enderror
                    
                    <!-- Image Preview -->
                    <div id="image_preview" style="display: none; margin-top: var(--space-md);">
                        <p style="margin: 0 0 var(--space-sm) 0; font-weight: 600; color: var(--intel-gray-700);">Image Preview:</p>
                        <div style="max-width: 300px; border-radius: var(--radius-lg); overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            <img id="image_preview_img" src="" alt="Preview" style="width: 100%; height: auto; display: block;">
                        </div>
                        <button type="button" onclick="removeImageFile()" class="intel-btn intel-btn-sm intel-btn-error" style="margin-top: var(--space-sm);">
                            <i class="fas fa-times"></i>
                            Remove Image
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- PDF Upload Section -->
        <div class="intel-form-section">
            <div class="intel-form-section-header">
                <h2 class="intel-form-section-title">
                    <i class="fas fa-file-pdf"></i>
                    PDF File Upload
                </h2>
                <p class="intel-form-section-subtitle">Upload a PDF file for this prophecy (English)</p>
            </div>
            <div class="intel-form-section-body">
                <div class="intel-form-group">
                    <label for="featured_image" class="intel-form-label">
                        <i class="fas fa-file-pdf mr-2"></i>
                        PDF File (English)
                    </label>
                    <input type="file" 
                           id="pdf_file" 
                           name="pdf_file" 
                           accept=".pdf"
                           class="intel-form-input @error('pdf_file') error @enderror"
                           onchange="handlePdfUpload(this, 'english')">
                    <p class="intel-form-help">Upload a PDF file for this prophecy in English (Max: 10MB)</p>
                    @error('pdf_file')
                    <p class="intel-form-error">{{ $message }}</p>
                    @enderror
                    
                    <!-- PDF Preview -->
                    <div id="pdf_preview_english" style="display: none; margin-top: var(--space-sm); padding: var(--space-md); background: var(--intel-gray-50); border-radius: var(--radius-md); border: 1px solid var(--intel-gray-200);">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: var(--space-sm);">
                                <i class="fas fa-file-pdf" style="color: #dc2626; font-size: 1.25rem;"></i>
                                <div>
                                    <p id="pdf_name_english" style="margin: 0; font-weight: 600; color: var(--intel-gray-900);"></p>
                                    <p id="pdf_size_english" style="margin: 0; font-size: 0.875rem; color: var(--intel-gray-600);"></p>
                                </div>
                            </div>
                            <button type="button" onclick="removePdfFile('english')" style="background: none; border: none; color: var(--intel-gray-500); cursor: pointer; padding: var(--space-xs);">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div style="margin-top: var(--space-xl); display: flex; justify-content: space-between; align-items: center; background: white; padding: var(--space-lg); border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid var(--intel-gray-200);">
            <div style="display: flex; gap: var(--space-md);">
                <button type="submit" class="intel-btn intel-btn-primary">
                    <i class="fas fa-save"></i>
                    Create Prophecy
                </button>
                
                <button type="button" class="intel-btn intel-btn-secondary" onclick="previewProphecy()">
                    <i class="fas fa-eye"></i>
                    Preview
                </button>
                
                <button type="submit" name="save_and_continue" value="1" class="intel-btn intel-btn-success">
                    <i class="fas fa-arrow-right"></i>
                    Save & Continue Editing
                </button>
            </div>
            
            <a href="{{ route('admin.prophecies.index') }}" class="intel-btn intel-btn-secondary">
                <i class="fas fa-times"></i>
                Cancel
            </a>
        </div>
    </form>
</div>

<!-- TinyMCE Self-hosted Integration -->
<script src="{{ asset('assets/tinymce/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script>
// Initialize TinyMCE for the description field
tinymce.init({
    selector: '#description',
    height: 400,
    menubar: false,
    license_key: 'gpl',
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
    content_style: 'body { font-family: Inter, Arial, sans-serif; font-size: 14px; line-height: 1.6; }',
    branding: false,
    promotion: false,
    setup: function (editor) {
        // Handle form submission validation
        editor.on('init', function () {
            // Remove required attribute from original textarea since TinyMCE handles validation
            const textarea = document.getElementById('description');
            if (textarea) {
                textarea.removeAttribute('required');
            }
        });
        
        // Update hidden textarea when content changes
        editor.on('change keyup', function () {
            editor.save();
        });
    }
});

// Initialize TinyMCE for the prayer points editor
tinymce.init({
    selector: '#prayer_points_editor',
    height: 300,
    menubar: false,
    license_key: 'gpl',
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'charmap', 'preview',
        'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
    content_style: 'body { font-family: Inter, Arial, sans-serif; font-size: 14px; line-height: 1.6; }',
    branding: false,
    promotion: false,
    setup: function (editor) {
        editor.on('init', function () {
            // Add some sample prayer points structure
            if (!editor.getContent()) {
                editor.setContent(`
                    <ol>
                        <li><strong>Prayer Point 1:</strong> [Enter your first prayer point here]</li>
                        <li><strong>Prayer Point 2:</strong> [Enter your second prayer point here]</li>
                        <li><strong>Prayer Point 3:</strong> [Enter your third prayer point here]</li>
                    </ol>
                `);
            }
        });
        
        // Update hidden textarea when content changes
        editor.on('change keyup', function () {
            editor.save();
        });
    }
});

// Preview function
function previewProphecy() {
    // Get content from TinyMCE
    const content = tinymce.get('description').getContent();
    const prayerPoints = tinymce.get('prayer_points_editor').getContent();
    const title = document.getElementById('title').value || 'New Prophecy';
    
    // Open preview in new window
    const previewWindow = window.open('', '_blank', 'width=800,height=600,scrollbars=yes');
    previewWindow.document.write(`
        <html>
            <head>
                <title>Preview: ${title}</title>
                <style>
                    body { font-family: Inter, Arial, sans-serif; padding: 40px; line-height: 1.6; }
                    h1 { color: #1e40af; border-bottom: 2px solid #3b82f6; padding-bottom: 10px; }
                    h2 { color: #1e40af; margin-top: 30px; }
                    .prayer-points { background: #f8fafc; padding: 20px; border-radius: 8px; margin-top: 20px; }
                </style>
            </head>
            <body>
                <h1>${title}</h1>
                <div>${content}</div>
                ${prayerPoints ? `<div class="prayer-points"><h2>Prayer Points</h2>${prayerPoints}</div>` : ''}
            </body>
        </html>
    `);
    previewWindow.document.close();
}

// Form validation handler
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[method="POST"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Ensure TinyMCE content is saved to textareas before validation
            if (typeof tinymce !== 'undefined') {
                tinymce.triggerSave();
                
                // Custom validation for required TinyMCE fields
                const descriptionEditor = tinymce.get('description');
                if (descriptionEditor) {
                    const content = descriptionEditor.getContent({format: 'text'}).trim();
                    if (!content) {
                        e.preventDefault();
                        alert('Please enter the prophecy content.');
                        descriptionEditor.focus();
                        return false;
                    }
                }
            }
        });
    }
});

// Image Upload Handling Functions
function handleImageUpload(input) {
    const file = input.files[0];
    const preview = document.getElementById('image_preview');
    const img = document.getElementById('image_preview_img');
    
    if (file) {
        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            alert('Please select a valid image file (JPEG, PNG, or WEBP).');
            input.value = '';
            preview.style.display = 'none';
            return;
        }
        
        // Validate file size (5MB max)
        if (file.size > 5 * 1024 * 1024) {
            alert('Image size must be less than 5MB.');
            input.value = '';
            preview.style.display = 'none';
            return;
        }
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
}

function removeImageFile() {
    const input = document.getElementById('featured_image');
    const preview = document.getElementById('image_preview');
    
    input.value = '';
    preview.style.display = 'none';
}

// PDF Upload Handling Functions
function handlePdfUpload(input, language) {
    const file = input.files[0];
    const preview = document.getElementById(`pdf_preview_${language}`);
    const nameElement = document.getElementById(`pdf_name_${language}`);
    const sizeElement = document.getElementById(`pdf_size_${language}`);
    
    if (file) {
        // Validate file type
        if (file.type !== 'application/pdf') {
            alert('Please select a PDF file only.');
            input.value = '';
            preview.style.display = 'none';
            return;
        }
        
        // Validate file size (10MB limit)
        const maxSize = 10 * 1024 * 1024; // 10MB in bytes
        if (file.size > maxSize) {
            alert('File size must be less than 10MB.');
            input.value = '';
            preview.style.display = 'none';
            return;
        }
        
        // Show preview
        nameElement.textContent = file.name;
        sizeElement.textContent = formatFileSize(file.size);
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
}

function removePdfFile(language) {
    const input = document.getElementById(`pdf_file${language === 'english' ? '' : '_' + language}`);
    const preview = document.getElementById(`pdf_preview_${language}`);
    
    input.value = '';
    preview.style.display = 'none';
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}
</script>
@endsection