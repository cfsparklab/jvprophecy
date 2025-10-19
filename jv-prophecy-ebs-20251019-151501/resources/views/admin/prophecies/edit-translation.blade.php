@extends('layouts.admin')

@section('page-title', 'Edit Translation')

@section('admin-content')
<!-- Page Header -->
<div class="intel-page-header">
    <div class="intel-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="intel-page-title">
                    <i class="fas fa-edit"></i>
                    Edit {{ $languages[$language] ?? ucfirst($language) }} Translation
                </h1>
                <p class="intel-page-subtitle">{{ $prophecy->title ?? 'Prophecy Translation' }}</p>
            </div>
            <div style="display: flex; gap: var(--space-md);">
                <a href="{{ route('admin.prophecies.translations', $prophecy->id) }}" class="intel-btn intel-btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Translations
                </a>
                <a href="{{ route('admin.prophecies.show', $prophecy->id) }}" class="intel-btn intel-btn-secondary">
                    <i class="fas fa-eye"></i>
                    View Prophecy
                </a>
            </div>
        </div>
    </div>
</div>

<div class="intel-container">
    <form method="POST" action="{{ route('admin.prophecies.translations.update', [$prophecy->id, $language]) }}">
        @csrf
        @method('PUT')
        
        <!-- Translation Form -->
        <div class="intel-form-section">
            <div class="intel-form-section-header">
                <h2 class="intel-form-section-title">
                    <i class="fas fa-language"></i>
                    {{ $languages[$language] ?? ucfirst($language) }} Translation
                </h2>
                <p class="intel-form-section-subtitle">Edit the translation content for this prophecy</p>
            </div>
            <div class="intel-form-section-body">
                <!-- Title Field -->
                <div class="intel-form-group">
                    <label for="title" class="intel-form-label">
                        <i class="fas fa-heading mr-2"></i>
                        Title <span style="color: var(--error-color);">*</span>
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           required
                           value="{{ old('title', $translation->title ?? '') }}"
                           class="intel-form-input @error('title') error @enderror"
                           placeholder="Enter title in {{ $languages[$language] ?? ucfirst($language) }}">
                    @error('title')
                    <p class="intel-form-error">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Description Field -->
                <div class="intel-form-group">
                    <label for="description" class="intel-form-label">
                        <i class="fas fa-align-left mr-2"></i>
                        Description
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="8"
                              class="intel-form-textarea @error('description') error @enderror"
                              placeholder="Enter description in {{ $languages[$language] ?? ucfirst($language) }}">{{ old('description', $translation->description ?? '') }}</textarea>
                    @error('description')
                    <p class="intel-form-error">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Content Field -->
                <div class="intel-form-group">
                    <label for="content" class="intel-form-label">
                        <i class="fas fa-file-text mr-2"></i>
                        Full Content
                    </label>
                    <textarea id="content" 
                              name="content" 
                              rows="15"
                              class="intel-form-textarea @error('content') error @enderror"
                              placeholder="Enter the complete prophecy content in {{ $languages[$language] ?? ucfirst($language) }}">{{ old('content', $translation->content ?? '') }}</textarea>
                    @error('content')
                    <p class="intel-form-error">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Excerpt Field -->
                <div class="intel-form-group">
                    <label for="excerpt" class="intel-form-label">
                        <i class="fas fa-quote-left mr-2"></i>
                        Excerpt
                    </label>
                    <textarea id="excerpt" 
                              name="excerpt" 
                              rows="4"
                              class="intel-form-textarea @error('excerpt') error @enderror"
                              placeholder="Enter a brief excerpt in {{ $languages[$language] ?? ucfirst($language) }}">{{ old('excerpt', $translation->excerpt ?? '') }}</textarea>
                    <p class="intel-form-help">Brief summary or excerpt of the prophecy (max 500 characters)</p>
                    @error('excerpt')
                    <p class="intel-form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div style="display: flex; justify-content: space-between; align-items: center; background: white; padding: var(--space-lg); border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 1px solid var(--intel-gray-200);">
            <div style="display: flex; gap: var(--space-md);">
                <button type="submit" class="intel-btn intel-btn-primary">
                    <i class="fas fa-save"></i>
                    Update Translation
                </button>
                
                <button type="button" class="intel-btn intel-btn-secondary" onclick="previewTranslation()">
                    <i class="fas fa-eye"></i>
                    Preview
                </button>
                
                <button type="button" class="intel-btn intel-btn-warning" onclick="resetForm()">
                    <i class="fas fa-undo"></i>
                    Reset Changes
                </button>
            </div>
            
            <div style="display: flex; gap: var(--space-md);">
                <a href="{{ route('admin.prophecies.translations', $prophecy->id) }}" class="intel-btn intel-btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
                
                <button type="button" class="intel-btn intel-btn-danger" onclick="confirmDelete()">
                    <i class="fas fa-trash"></i>
                    Delete Translation
                </button>
            </div>
        </div>
    </form>
</div>

<!-- JavaScript for Form Actions -->
<script>
function previewTranslation() {
    const title = document.getElementById('title').value || '{{ $languages[$language] ?? ucfirst($language) }} Translation Preview';
    const content = document.getElementById('content').value || 'No content available';
    
    // Open preview in new window
    const previewWindow = window.open('', '_blank', 'width=800,height=600,scrollbars=yes');
    previewWindow.document.write(`
        <html>
            <head>
                <title>Preview: ${title}</title>
                <style>
                    body { font-family: Inter, Arial, sans-serif; padding: 40px; line-height: 1.6; }
                    h1 { color: #1e40af; border-bottom: 2px solid #3b82f6; padding-bottom: 10px; }
                </style>
            </head>
            <body>
                <h1>${title}</h1>
                <div>${content.replace(/\n/g, '<br>')}</div>
            </body>
        </html>
    `);
    previewWindow.document.close();
}

function resetForm() {
    if (confirm('Are you sure you want to reset all changes? This will restore the original values.')) {
        document.querySelector('form').reset();
    }
}

function confirmDelete() {
    if (confirm('Are you sure you want to delete this translation? This action cannot be undone.')) {
        // Create and submit delete form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.prophecies.translations.delete", [$prophecy->id, $language]) }}';
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
