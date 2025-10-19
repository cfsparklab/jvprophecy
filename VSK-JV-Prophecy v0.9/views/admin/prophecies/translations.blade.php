@extends('layouts.admin')

@section('page-title', 'Manage Translations')

@section('admin-content')
<!-- Page Header -->
<div class="intel-page-header">
    <div class="intel-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="intel-page-title">
                    <i class="fas fa-language"></i>
                    Manage Translations
                </h1>
                <p class="intel-page-subtitle">{{ $prophecy->title ?? 'Divine Revelation of Hope - Extended' }}</p>
            </div>
            <a href="{{ route('admin.prophecies.show', $prophecy->id ?? 1) }}" class="intel-btn intel-btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back to Prophecy
            </a>
        </div>
    </div>
</div>

<div class="intel-container">
    <!-- English Content Management -->
    <div class="intel-card mb-4">
        <div class="intel-card-header">
            <h2 class="intel-card-title">
                <i class="fas fa-globe"></i>
                English Content Management
            </h2>
            <p class="intel-card-subtitle">English content is managed in the main prophecy form. Use this section to add translations in other languages.</p>
        </div>
    </div>
    
    <!-- Multi-Language Editor -->
    <div class="intel-card">
        <div class="intel-card-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 class="intel-card-title">
                        <i class="fas fa-edit"></i>
                        Multi-Language Editor
                    </h2>
                    <p class="intel-card-subtitle">Create and manage translations in different languages</p>
                </div>
                <div style="display: flex; align-items: center; gap: var(--space-md);">
                    <span class="intel-badge intel-badge-info">
                        <i class="fas fa-check"></i>
                        1/5 languages completed
                    </span>
                </div>
            </div>
        </div>
        <div class="intel-card-body">
            <!-- Language Tabs -->
            <div style="border-bottom: 1px solid var(--intel-gray-200); margin-bottom: var(--space-xl);">
                <div style="display: flex; gap: 0;">
                    <!-- Tamil Tab -->
                    <button type="button" 
                            class="language-tab" 
                            data-language="tamil"
                            style="padding: var(--space-md) var(--space-lg); border: none; background: var(--intel-gray-100); color: var(--intel-gray-600); font-weight: 500; border-radius: var(--radius-md) var(--radius-md) 0 0; cursor: pointer; transition: all 0.2s ease;">
                        <i class="fas fa-plus mr-2"></i>
                        தமிழ் (Tamil)
                    </button>
                    
                    <!-- Kannada Tab -->
                    <button type="button" 
                            class="language-tab" 
                            data-language="kannada"
                            style="padding: var(--space-md) var(--space-lg); border: none; background: var(--intel-gray-100); color: var(--intel-gray-600); font-weight: 500; border-radius: var(--radius-md) var(--radius-md) 0 0; cursor: pointer; transition: all 0.2s ease;">
                        <i class="fas fa-plus mr-2"></i>
                        ಕನ್ನಡ (Kannada)
                    </button>
                    
                    <!-- Telugu Tab -->
                    <button type="button" 
                            class="language-tab" 
                            data-language="telugu"
                            style="padding: var(--space-md) var(--space-lg); border: none; background: var(--intel-gray-100); color: var(--intel-gray-600); font-weight: 500; border-radius: var(--radius-md) var(--radius-md) 0 0; cursor: pointer; transition: all 0.2s ease;">
                        <i class="fas fa-plus mr-2"></i>
                        తెలుగు (Telugu)
                    </button>
                    
                    <!-- Malayalam Tab - Active -->
                    <button type="button" 
                            class="language-tab active" 
                            data-language="malayalam"
                            style="padding: var(--space-md) var(--space-lg); border: none; background: white; color: var(--intel-blue-600); font-weight: 600; border-radius: var(--radius-md) var(--radius-md) 0 0; cursor: pointer; transition: all 0.2s ease; border-bottom: 2px solid var(--intel-blue-500);">
                        <i class="fas fa-edit mr-2"></i>
                        മലയാളം (Malayalam)
                    </button>
                    
                    <!-- Hindi Tab -->
                    <button type="button" 
                            class="language-tab" 
                            data-language="hindi"
                            style="padding: var(--space-md) var(--space-lg); border: none; background: var(--intel-gray-100); color: var(--intel-gray-600); font-weight: 500; border-radius: var(--radius-md) var(--radius-md) 0 0; cursor: pointer; transition: all 0.2s ease;">
                        <i class="fas fa-check mr-2 text-green-600"></i>
                        हिंदी (Hindi)
                    </button>
                </div>
            </div>
            
            <!-- Malayalam Translation Form -->
            <div id="malayalam-content" class="language-content active">
                <form method="POST" action="{{ route('admin.prophecies.translations.store', $prophecy->id ?? 1) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="language" value="ml">
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg);">
                        <h3 style="margin: 0; font-size: 1.25rem; font-weight: 600; color: var(--intel-gray-900); display: flex; align-items: center; gap: var(--space-sm);">
                            <i class="fas fa-language text-blue-600"></i>
                            മലയാളം (Malayalam)
                        </h3>
                        <div style="display: flex; gap: var(--space-sm);">
                            <button type="button" class="intel-btn intel-btn-secondary intel-btn-sm" onclick="createNewTranslation()">
                                <i class="fas fa-plus"></i>
                                Create New
                            </button>
                        </div>
                    </div>
                    
                    <div style="background: var(--intel-gray-50); border: 1px solid var(--intel-gray-200); border-radius: var(--radius-lg); padding: var(--space-lg); margin-bottom: var(--space-lg);">
                        <p style="margin: 0; color: var(--intel-gray-600); font-size: 0.875rem;">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            No translation available - create new
                        </p>
                    </div>
                    
                    <!-- Title Field -->
                    <div class="intel-form-group">
                        <label for="title_malayalam" class="intel-form-label">
                            <i class="fas fa-heading mr-2"></i>
                            Title in Malayalam <span style="color: var(--error-color);">*</span>
                        </label>
                        <input type="text" 
                               id="title_malayalam" 
                               name="title" 
                               required
                               class="intel-form-input"
                               placeholder="Enter title in Malayalam"
                               value="{{ old('title') }}">
                        @error('title')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Excerpt Field -->
                    <div class="intel-form-group">
                        <label for="excerpt_malayalam" class="intel-form-label">
                            <i class="fas fa-quote-left mr-2"></i>
                            Excerpt in Malayalam
                        </label>
                        <textarea id="excerpt_malayalam" 
                                  name="excerpt" 
                                  rows="3"
                                  class="intel-form-textarea"
                                  placeholder="Brief summary in Malayalam (max 500 characters)">{{ old('excerpt') }}</textarea>
                        <p class="intel-form-help">Brief summary (max 500 characters)</p>
                        @error('excerpt')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Description Field -->
                    <div class="intel-form-group">
                        <label for="description_malayalam" class="intel-form-label">
                            <i class="fas fa-align-left mr-2"></i>
                            Description in Malayalam
                        </label>
                        <textarea id="description_malayalam" 
                                  name="description" 
                                  rows="8"
                                  class="intel-form-textarea"
                                  placeholder="Description in Malayalam">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Content Field -->
                    <div class="intel-form-group">
                        <label for="content_malayalam" class="intel-form-label">
                            <i class="fas fa-file-text mr-2"></i>
                            Full Content in Malayalam <span style="color: var(--error-color);">*</span>
                        </label>
                        <textarea id="content_malayalam" 
                                  name="content" 
                                  rows="15"
                                  required
                                  class="intel-form-textarea"
                                  placeholder="Enter the complete prophecy content in Malayalam">{{ old('content') }}</textarea>
                        @error('content')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Prayer Points Field -->
                    <div class="intel-form-group">
                        <label for="prayer_points_malayalam" class="intel-form-label">
                            <i class="fas fa-praying-hands mr-2"></i>
                            Prayer Points in Malayalam
                        </label>
                        <textarea id="prayer_points_malayalam" 
                                  name="prayer_points" 
                                  rows="10"
                                  class="intel-form-textarea"
                                  placeholder="Enter prayer points in Malayalam using the rich text editor">{{ old('prayer_points') }}</textarea>
                        <p class="intel-form-help">Use the rich text editor to format your prayer points with lists, bold text, and other formatting options</p>
                        @error('prayer_points')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- PDF Upload Field -->
                    <div class="intel-form-group">
                        <label for="pdf_file_malayalam" class="intel-form-label">
                            <i class="fas fa-file-pdf mr-2"></i>
                            PDF File in Malayalam
                        </label>
                        <input type="file" 
                               id="pdf_file_malayalam" 
                               name="pdf_file" 
                               accept=".pdf"
                               class="intel-form-input"
                               onchange="handlePdfUpload(this, 'malayalam')">
                        <p class="intel-form-help">Upload a PDF file for this prophecy in Malayalam (Max: 10MB)</p>
                        @error('pdf_file')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                        
                        <!-- PDF Preview -->
                        <div id="pdf_preview_malayalam" style="display: none; margin-top: var(--space-sm); padding: var(--space-md); background: var(--intel-gray-50); border-radius: var(--radius-md); border: 1px solid var(--intel-gray-200);">
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: var(--space-sm);">
                                    <i class="fas fa-file-pdf" style="color: #dc2626; font-size: 1.25rem;"></i>
                                    <div>
                                        <p id="pdf_name_malayalam" style="margin: 0; font-weight: 600; color: var(--intel-gray-900);"></p>
                                        <p id="pdf_size_malayalam" style="margin: 0; font-size: 0.875rem; color: var(--intel-gray-600);"></p>
                                    </div>
                                </div>
                                <button type="button" onclick="removePdfFile('malayalam')" style="background: none; border: none; color: var(--intel-gray-500); cursor: pointer; padding: var(--space-xs);">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: var(--space-lg); border-top: 1px solid var(--intel-gray-200);">
                        <div style="display: flex; gap: var(--space-md);">
                            <button type="submit" class="intel-btn intel-btn-primary">
                                <i class="fas fa-save"></i>
                                Save Translation
                            </button>
                            
                            <button type="button" class="intel-btn intel-btn-secondary" onclick="previewTranslation()">
                                <i class="fas fa-eye"></i>
                                Preview
                            </button>
                        </div>
                        
                        <button type="button" class="intel-btn intel-btn-warning intel-btn-sm" onclick="clearForm()">
                            <i class="fas fa-eraser"></i>
                            Clear Form
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Other Language Content Placeholders -->
            <div id="tamil-content" class="language-content" style="display: none;">
                @php
                    $tamilTranslation = $prophecy->translations->where('language', 'ta')->first();
                @endphp
                
                @if($tamilTranslation)
                    <!-- Existing Translation Display -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg);">
                        <h3 style="margin: 0; font-size: 1.25rem; font-weight: 600; color: var(--intel-gray-900); display: flex; align-items: center; gap: var(--space-sm);">
                            <i class="fas fa-language text-blue-600"></i>
                            தமிழ் (Tamil)
                        </h3>
                        <div style="display: flex; gap: var(--space-sm);">
                            <a href="{{ route('admin.prophecies.translations.edit', [$prophecy->id, 'ta']) }}" class="intel-btn intel-btn-primary intel-btn-sm">
                                <i class="fas fa-edit"></i>
                                Edit Translation
                            </a>
                        </div>
                    </div>
                    
                    <div style="background: #dcfce7; border: 1px solid #86efac; border-radius: var(--radius-lg); padding: var(--space-lg); margin-bottom: var(--space-lg);">
                        <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
                            <i class="fas fa-check-circle text-green-600" style="font-size: 1.5rem;"></i>
                            <div>
                                <p style="margin: 0; font-size: 1rem; font-weight: 600; color: #166534;">Tamil Translation Complete</p>
                                <p style="margin: var(--space-xs) 0 0 0; font-size: 0.875rem; color: var(--success-color);">Translation saved and ready for use</p>
                            </div>
                        </div>
                        
                        <div style="background: white; border-radius: var(--radius-md); padding: var(--space-md); margin-top: var(--space-md);">
                            <h4 style="margin: 0 0 var(--space-sm) 0; font-size: 0.875rem; font-weight: 600; color: var(--intel-gray-700);">Title:</h4>
                            <p style="margin: 0 0 var(--space-md) 0; color: var(--intel-gray-900);">{{ $tamilTranslation->title }}</p>
                            
                            @if($tamilTranslation->description)
                            <h4 style="margin: 0 0 var(--space-sm) 0; font-size: 0.875rem; font-weight: 600; color: var(--intel-gray-700);">Description:</h4>
                            <p style="margin: 0 0 var(--space-md) 0; color: var(--intel-gray-900);">{{ Str::limit($tamilTranslation->description, 200) }}</p>
                            @endif
                            
                            @if($tamilTranslation->content)
                            <h4 style="margin: 0 0 var(--space-sm) 0; font-size: 0.875rem; font-weight: 600; color: var(--intel-gray-700);">Content Preview:</h4>
                            <p style="margin: 0; color: var(--intel-gray-900);">{{ Str::limit(strip_tags($tamilTranslation->content), 300) }}</p>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Create New Translation Form -->
                    <form method="POST" action="{{ route('admin.prophecies.translations.store', $prophecy->id ?? 1) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="language" value="ta">
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg);">
                            <h3 style="margin: 0; font-size: 1.25rem; font-weight: 600; color: var(--intel-gray-900); display: flex; align-items: center; gap: var(--space-sm);">
                                <i class="fas fa-language text-blue-600"></i>
                                தமிழ் (Tamil)
                            </h3>
                            <div style="display: flex; gap: var(--space-sm);">
                                <button type="button" class="intel-btn intel-btn-secondary intel-btn-sm" onclick="createNewTranslation('tamil')">
                                    <i class="fas fa-plus"></i>
                                    Create New
                                </button>
                            </div>
                        </div>
                        
                        <div style="background: var(--intel-gray-50); border: 1px solid var(--intel-gray-200); border-radius: var(--radius-lg); padding: var(--space-lg); margin-bottom: var(--space-lg);">
                            <p style="margin: 0; color: var(--intel-gray-600); font-size: 0.875rem;">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                No translation available - create new
                            </p>
                        </div>
                    
                    <!-- Title Field -->
                    <div class="intel-form-group">
                        <label for="title_tamil" class="intel-form-label">
                            <i class="fas fa-heading mr-2"></i>
                            Title in Tamil <span style="color: var(--error-color);">*</span>
                        </label>
                        <input type="text" 
                               id="title_tamil" 
                               name="title" 
                               required
                               class="intel-form-input"
                               placeholder="Enter title in Tamil"
                               value="{{ old('title') }}">
                        @error('title')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Excerpt Field -->
                    <div class="intel-form-group">
                        <label for="excerpt_tamil" class="intel-form-label">
                            <i class="fas fa-quote-left mr-2"></i>
                            Excerpt in Tamil
                        </label>
                        <textarea id="excerpt_tamil" 
                                  name="excerpt" 
                                  rows="3"
                                  class="intel-form-textarea"
                                  placeholder="Brief summary in Tamil (max 500 characters)">{{ old('excerpt') }}</textarea>
                        <p class="intel-form-help">Brief summary (max 500 characters)</p>
                        @error('excerpt')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Description Field -->
                    <div class="intel-form-group">
                        <label for="description_tamil" class="intel-form-label">
                            <i class="fas fa-align-left mr-2"></i>
                            Description in Tamil
                        </label>
                        <textarea id="description_tamil" 
                                  name="description" 
                                  rows="8"
                                  class="intel-form-textarea"
                                  placeholder="Description in Tamil">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Content Field -->
                    <div class="intel-form-group">
                        <label for="content_tamil" class="intel-form-label">
                            <i class="fas fa-file-text mr-2"></i>
                            Full Content in Tamil <span style="color: var(--error-color);">*</span>
                        </label>
                        <textarea id="content_tamil" 
                                  name="content" 
                                  rows="15"
                                  required
                                  class="intel-form-textarea"
                                  placeholder="Enter the complete prophecy content in Tamil">{{ old('content') }}</textarea>
                        @error('content')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Prayer Points Field -->
                    <div class="intel-form-group">
                        <label for="prayer_points_tamil" class="intel-form-label">
                            <i class="fas fa-praying-hands mr-2"></i>
                            Prayer Points in Tamil
                        </label>
                        <textarea id="prayer_points_tamil" 
                                  name="prayer_points" 
                                  rows="10"
                                  class="intel-form-textarea"
                                  placeholder="Enter prayer points in Tamil using the rich text editor">{{ old('prayer_points') }}</textarea>
                        <p class="intel-form-help">Use the rich text editor to format your prayer points with lists, bold text, and other formatting options</p>
                        @error('prayer_points')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    
                    
                    <!-- PDF Upload Field -->
                    <div class="intel-form-group">
                        <label for="pdf_file_tamil" class="intel-form-label">
                            <i class="fas fa-file-pdf mr-2"></i>
                            PDF File in Tamil
                        </label>
                        <input type="file" 
                               id="pdf_file_tamil" 
                               name="pdf_file" 
                               accept=".pdf"
                               class="intel-form-input"
                               onchange="handlePdfUpload(this, 'tamil')">
                        <p class="intel-form-help">Upload a PDF file for this prophecy in Tamil (Max: 10MB)</p>
                        @error('pdf_file')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                        
                        <!-- PDF Preview -->
                        <div id="pdf_preview_tamil" style="display: none; margin-top: var(--space-sm); padding: var(--space-md); background: var(--intel-gray-50); border-radius: var(--radius-md); border: 1px solid var(--intel-gray-200);">
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: var(--space-sm);">
                                    <i class="fas fa-file-pdf" style="color: #dc2626; font-size: 1.25rem;"></i>
                                    <div>
                                        <p id="pdf_name_tamil" style="margin: 0; font-weight: 600; color: var(--intel-gray-900);"></p>
                                        <p id="pdf_size_tamil" style="margin: 0; font-size: 0.875rem; color: var(--intel-gray-600);"></p>
                                    </div>
                                </div>
                                <button type="button" onclick="removePdfFile('tamil')" style="background: none; border: none; color: var(--intel-gray-500); cursor: pointer; padding: var(--space-xs);">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div><!-- Form Actions -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: var(--space-lg); border-top: 1px solid var(--intel-gray-200);">
                        <div style="display: flex; gap: var(--space-md);">
                            <button type="submit" class="intel-btn intel-btn-primary">
                                <i class="fas fa-save"></i>
                                Save Translation
                            </button>
                            
                            <button type="button" class="intel-btn intel-btn-secondary" onclick="previewTranslation('tamil')">
                                <i class="fas fa-eye"></i>
                                Preview
                            </button>
                        </div>
                        
                        <button type="button" class="intel-btn intel-btn-warning intel-btn-sm" onclick="clearForm('tamil')">
                            <i class="fas fa-eraser"></i>
                            Clear Form
                        </button>
                    </div>
                </form>
                @endif
            </div>
            
            <div id="kannada-content" class="language-content" style="display: none;">
                <form method="POST" action="{{ route('admin.prophecies.translations.store', $prophecy->id ?? 1) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="language" value="kn">
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg);">
                        <h3 style="margin: 0; font-size: 1.25rem; font-weight: 600; color: var(--intel-gray-900); display: flex; align-items: center; gap: var(--space-sm);">
                            <i class="fas fa-language text-blue-600"></i>
                            ಕನ್ನಡ (Kannada)
                        </h3>
                        <div style="display: flex; gap: var(--space-sm);">
                            <button type="button" class="intel-btn intel-btn-secondary intel-btn-sm" onclick="createNewTranslation('kannada')">
                                <i class="fas fa-plus"></i>
                                Create New
                            </button>
                        </div>
                    </div>
                    
                    <div style="background: var(--intel-gray-50); border: 1px solid var(--intel-gray-200); border-radius: var(--radius-lg); padding: var(--space-lg); margin-bottom: var(--space-lg);">
                        <p style="margin: 0; color: var(--intel-gray-600); font-size: 0.875rem;">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            No translation available - create new
                        </p>
                    </div>
                    
                    <!-- Title Field -->
                    <div class="intel-form-group">
                        <label for="title_kannada" class="intel-form-label">
                            <i class="fas fa-heading mr-2"></i>
                            Title in Kannada <span style="color: var(--error-color);">*</span>
                        </label>
                        <input type="text" 
                               id="title_kannada" 
                               name="title" 
                               required
                               class="intel-form-input"
                               placeholder="Enter title in Kannada"
                               value="{{ old('title') }}">
                        @error('title')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Excerpt Field -->
                    <div class="intel-form-group">
                        <label for="excerpt_kannada" class="intel-form-label">
                            <i class="fas fa-quote-left mr-2"></i>
                            Excerpt in Kannada
                        </label>
                        <textarea id="excerpt_kannada" 
                                  name="excerpt" 
                                  rows="3"
                                  class="intel-form-textarea"
                                  placeholder="Brief summary in Kannada (max 500 characters)">{{ old('excerpt') }}</textarea>
                        <p class="intel-form-help">Brief summary (max 500 characters)</p>
                        @error('excerpt')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Description Field -->
                    <div class="intel-form-group">
                        <label for="description_kannada" class="intel-form-label">
                            <i class="fas fa-align-left mr-2"></i>
                            Description in Kannada
                        </label>
                        <textarea id="description_kannada" 
                                  name="description" 
                                  rows="8"
                                  class="intel-form-textarea"
                                  placeholder="Description in Kannada">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Content Field -->
                    <div class="intel-form-group">
                        <label for="content_kannada" class="intel-form-label">
                            <i class="fas fa-file-text mr-2"></i>
                            Full Content in Kannada <span style="color: var(--error-color);">*</span>
                        </label>
                        <textarea id="content_kannada" 
                                  name="content" 
                                  rows="15"
                                  required
                                  class="intel-form-textarea"
                                  placeholder="Enter the complete prophecy content in Kannada">{{ old('content') }}</textarea>
                        @error('content')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Prayer Points Field -->
                    <div class="intel-form-group">
                        <label for="prayer_points_kannada" class="intel-form-label">
                            <i class="fas fa-praying-hands mr-2"></i>
                            Prayer Points in Kannada
                        </label>
                        <textarea id="prayer_points_kannada" 
                                  name="prayer_points" 
                                  rows="10"
                                  class="intel-form-textarea"
                                  placeholder="Enter prayer points in Kannada using the rich text editor">{{ old('prayer_points') }}</textarea>
                        <p class="intel-form-help">Use the rich text editor to format your prayer points with lists, bold text, and other formatting options</p>
                        @error('prayer_points')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    
                    
                    <!-- PDF Upload Field -->
                    <div class="intel-form-group">
                        <label for="pdf_file_kannada" class="intel-form-label">
                            <i class="fas fa-file-pdf mr-2"></i>
                            PDF File in Kannada
                        </label>
                        <input type="file" 
                               id="pdf_file_kannada" 
                               name="pdf_file" 
                               accept=".pdf"
                               class="intel-form-input"
                               onchange="handlePdfUpload(this, 'kannada')">
                        <p class="intel-form-help">Upload a PDF file for this prophecy in Kannada (Max: 10MB)</p>
                        @error('pdf_file')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                        
                        <!-- PDF Preview -->
                        <div id="pdf_preview_kannada" style="display: none; margin-top: var(--space-sm); padding: var(--space-md); background: var(--intel-gray-50); border-radius: var(--radius-md); border: 1px solid var(--intel-gray-200);">
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: var(--space-sm);">
                                    <i class="fas fa-file-pdf" style="color: #dc2626; font-size: 1.25rem;"></i>
                                    <div>
                                        <p id="pdf_name_kannada" style="margin: 0; font-weight: 600; color: var(--intel-gray-900);"></p>
                                        <p id="pdf_size_kannada" style="margin: 0; font-size: 0.875rem; color: var(--intel-gray-600);"></p>
                                    </div>
                                </div>
                                <button type="button" onclick="removePdfFile('kannada')" style="background: none; border: none; color: var(--intel-gray-500); cursor: pointer; padding: var(--space-xs);">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div><!-- Form Actions -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: var(--space-lg); border-top: 1px solid var(--intel-gray-200);">
                        <div style="display: flex; gap: var(--space-md);">
                            <button type="submit" class="intel-btn intel-btn-primary">
                                <i class="fas fa-save"></i>
                                Save Translation
                            </button>
                            
                            <button type="button" class="intel-btn intel-btn-secondary" onclick="previewTranslation('kannada')">
                                <i class="fas fa-eye"></i>
                                Preview
                            </button>
                        </div>
                        
                        <button type="button" class="intel-btn intel-btn-warning intel-btn-sm" onclick="clearForm('kannada')">
                            <i class="fas fa-eraser"></i>
                            Clear Form
                        </button>
                    </div>
                </form>
            </div>
            
            <div id="telugu-content" class="language-content" style="display: none;">
                <form method="POST" action="{{ route('admin.prophecies.translations.store', $prophecy->id ?? 1) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="language" value="te">
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg);">
                        <h3 style="margin: 0; font-size: 1.25rem; font-weight: 600; color: var(--intel-gray-900); display: flex; align-items: center; gap: var(--space-sm);">
                            <i class="fas fa-language text-blue-600"></i>
                            తెలుగు (Telugu)
                        </h3>
                        <div style="display: flex; gap: var(--space-sm);">
                            <button type="button" class="intel-btn intel-btn-secondary intel-btn-sm" onclick="createNewTranslation('telugu')">
                                <i class="fas fa-plus"></i>
                                Create New
                            </button>
                        </div>
                    </div>
                    
                    <div style="background: var(--intel-gray-50); border: 1px solid var(--intel-gray-200); border-radius: var(--radius-lg); padding: var(--space-lg); margin-bottom: var(--space-lg);">
                        <p style="margin: 0; color: var(--intel-gray-600); font-size: 0.875rem;">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            No translation available - create new
                        </p>
                    </div>
                    
                    <!-- Title Field -->
                    <div class="intel-form-group">
                        <label for="title_telugu" class="intel-form-label">
                            <i class="fas fa-heading mr-2"></i>
                            Title in Telugu <span style="color: var(--error-color);">*</span>
                        </label>
                        <input type="text" 
                               id="title_telugu" 
                               name="title" 
                               required
                               class="intel-form-input"
                               placeholder="Enter title in Telugu"
                               value="{{ old('title') }}">
                        @error('title')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Excerpt Field -->
                    <div class="intel-form-group">
                        <label for="excerpt_telugu" class="intel-form-label">
                            <i class="fas fa-quote-left mr-2"></i>
                            Excerpt in Telugu
                        </label>
                        <textarea id="excerpt_telugu" 
                                  name="excerpt" 
                                  rows="3"
                                  class="intel-form-textarea"
                                  placeholder="Brief summary in Telugu (max 500 characters)">{{ old('excerpt') }}</textarea>
                        <p class="intel-form-help">Brief summary (max 500 characters)</p>
                        @error('excerpt')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Description Field -->
                    <div class="intel-form-group">
                        <label for="description_telugu" class="intel-form-label">
                            <i class="fas fa-align-left mr-2"></i>
                            Description in Telugu
                        </label>
                        <textarea id="description_telugu" 
                                  name="description" 
                                  rows="8"
                                  class="intel-form-textarea"
                                  placeholder="Description in Telugu">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Content Field -->
                    <div class="intel-form-group">
                        <label for="content_telugu" class="intel-form-label">
                            <i class="fas fa-file-text mr-2"></i>
                            Full Content in Telugu <span style="color: var(--error-color);">*</span>
                        </label>
                        <textarea id="content_telugu" 
                                  name="content" 
                                  rows="15"
                                  required
                                  class="intel-form-textarea"
                                  placeholder="Enter the complete prophecy content in Telugu">{{ old('content') }}</textarea>
                        @error('content')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Prayer Points Field -->
                    <div class="intel-form-group">
                        <label for="prayer_points_telugu" class="intel-form-label">
                            <i class="fas fa-praying-hands mr-2"></i>
                            Prayer Points in Telugu
                        </label>
                        <textarea id="prayer_points_telugu" 
                                  name="prayer_points" 
                                  rows="10"
                                  class="intel-form-textarea"
                                  placeholder="Enter prayer points in Telugu using the rich text editor">{{ old('prayer_points') }}</textarea>
                        <p class="intel-form-help">Use the rich text editor to format your prayer points with lists, bold text, and other formatting options</p>
                        @error('prayer_points')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    
                    
                    <!-- PDF Upload Field -->
                    <div class="intel-form-group">
                        <label for="pdf_file_telugu" class="intel-form-label">
                            <i class="fas fa-file-pdf mr-2"></i>
                            PDF File in Telugu
                        </label>
                        <input type="file" 
                               id="pdf_file_telugu" 
                               name="pdf_file" 
                               accept=".pdf"
                               class="intel-form-input"
                               onchange="handlePdfUpload(this, 'telugu')">
                        <p class="intel-form-help">Upload a PDF file for this prophecy in Telugu (Max: 10MB)</p>
                        @error('pdf_file')
                        <p class="intel-form-error">{{ $message }}</p>
                        @enderror
                        
                        <!-- PDF Preview -->
                        <div id="pdf_preview_telugu" style="display: none; margin-top: var(--space-sm); padding: var(--space-md); background: var(--intel-gray-50); border-radius: var(--radius-md); border: 1px solid var(--intel-gray-200);">
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: var(--space-sm);">
                                    <i class="fas fa-file-pdf" style="color: #dc2626; font-size: 1.25rem;"></i>
                                    <div>
                                        <p id="pdf_name_telugu" style="margin: 0; font-weight: 600; color: var(--intel-gray-900);"></p>
                                        <p id="pdf_size_telugu" style="margin: 0; font-size: 0.875rem; color: var(--intel-gray-600);"></p>
                                    </div>
                                </div>
                                <button type="button" onclick="removePdfFile('telugu')" style="background: none; border: none; color: var(--intel-gray-500); cursor: pointer; padding: var(--space-xs);">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div><!-- Form Actions -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: var(--space-lg); border-top: 1px solid var(--intel-gray-200);">
                        <div style="display: flex; gap: var(--space-md);">
                            <button type="submit" class="intel-btn intel-btn-primary">
                                <i class="fas fa-save"></i>
                                Save Translation
                            </button>
                            
                            <button type="button" class="intel-btn intel-btn-secondary" onclick="previewTranslation('telugu')">
                                <i class="fas fa-eye"></i>
                                Preview
                            </button>
                        </div>
                        
                        <button type="button" class="intel-btn intel-btn-warning intel-btn-sm" onclick="clearForm('telugu')">
                            <i class="fas fa-eraser"></i>
                            Clear Form
                        </button>
                    </div>
                </form>
            </div>
            
            <div id="hindi-content" class="language-content" style="display: none;">
                <div style="background: #dcfce7; border: 1px solid #86efac; border-radius: var(--radius-lg); padding: var(--space-lg); text-align: center;">
                    <i class="fas fa-check-circle text-green-600" style="font-size: 2rem; margin-bottom: var(--space-md);"></i>
                    <p style="margin: 0; font-size: 1.125rem; font-weight: 600; color: #166534;">Hindi Translation Complete</p>
                    <p style="margin: var(--space-sm) 0 0 0; font-size: 0.875rem; color: var(--success-color);">Translation has been saved and is ready for use</p>
                    <div style="margin-top: var(--space-md);">
                        <button type="button" class="intel-btn intel-btn-secondary intel-btn-sm" onclick="editTranslation('hindi')">
                            <i class="fas fa-edit"></i>
                            Edit Translation
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Translation Progress -->
    <div class="intel-card" style="margin-top: var(--space-lg);">
        <div class="intel-card-header">
            <h2 class="intel-card-title">
                <i class="fas fa-chart-pie"></i>
                Translation Progress
            </h2>
            <p class="intel-card-subtitle">Overview of translation completion across all languages</p>
        </div>
        <div class="intel-card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-lg);">
                <!-- Tamil Progress -->
                <div style="text-align: center; padding: var(--space-md);">
                    <div style="width: 60px; height: 60px; background: var(--intel-gray-100); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-sm) auto;">
                        <i class="fas fa-plus text-gray-400"></i>
                    </div>
                    <h4 style="margin: 0; font-size: 0.875rem; font-weight: 600; color: var(--intel-gray-900);">தமிழ் (Tamil)</h4>
                    <p style="margin: var(--space-xs) 0 0 0; font-size: 0.75rem; color: var(--intel-gray-500);">Not started</p>
                </div>
                
                <!-- Kannada Progress -->
                <div style="text-align: center; padding: var(--space-md);">
                    <div style="width: 60px; height: 60px; background: var(--intel-gray-100); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-sm) auto;">
                        <i class="fas fa-plus text-gray-400"></i>
                    </div>
                    <h4 style="margin: 0; font-size: 0.875rem; font-weight: 600; color: var(--intel-gray-900);">ಕನ್ನಡ (Kannada)</h4>
                    <p style="margin: var(--space-xs) 0 0 0; font-size: 0.75rem; color: var(--intel-gray-500);">Not started</p>
                </div>
                
                <!-- Telugu Progress -->
                <div style="text-align: center; padding: var(--space-md);">
                    <div style="width: 60px; height: 60px; background: var(--intel-gray-100); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-sm) auto;">
                        <i class="fas fa-plus text-gray-400"></i>
                    </div>
                    <h4 style="margin: 0; font-size: 0.875rem; font-weight: 600; color: var(--intel-gray-900);">తెలుగు (Telugu)</h4>
                    <p style="margin: var(--space-xs) 0 0 0; font-size: 0.75rem; color: var(--intel-gray-500);">Not started</p>
                </div>
                
                <!-- Malayalam Progress -->
                <div style="text-align: center; padding: var(--space-md);">
                    <div style="width: 60px; height: 60px; background: var(--intel-blue-100); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-sm) auto;">
                        <i class="fas fa-edit text-blue-600"></i>
                    </div>
                    <h4 style="margin: 0; font-size: 0.875rem; font-weight: 600; color: var(--intel-gray-900);">മലയാളം (Malayalam)</h4>
                    <p style="margin: var(--space-xs) 0 0 0; font-size: 0.75rem; color: var(--intel-blue-600);">In progress</p>
                </div>
                
                <!-- Hindi Progress -->
                <div style="text-align: center; padding: var(--space-md);">
                    <div style="width: 60px; height: 60px; background: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-sm) auto;">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                    <h4 style="margin: 0; font-size: 0.875rem; font-weight: 600; color: var(--intel-gray-900);">हिंदी (Hindi)</h4>
                    <p style="margin: var(--space-xs) 0 0 0; font-size: 0.75rem; color: var(--success-color);">Completed</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Tab Management -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const tabs = document.querySelectorAll('.language-tab');
    const contents = document.querySelectorAll('.language-content');
    
    // Check if there's a saved active tab or error state
    const urlParams = new URLSearchParams(window.location.search);
    const hasError = document.querySelector('.intel-form-error') !== null;
    let activeTab = localStorage.getItem('activeTranslationTab') || 'malayalam';
    
    // If there's an error, try to determine which tab was being used
    if (hasError) {
        // Check which form has old() values
        const forms = document.querySelectorAll('form[method="POST"]');
        forms.forEach(form => {
            const languageInput = form.querySelector('input[name="language"]');
            const titleInput = form.querySelector('input[name="title"]');
            if (languageInput && titleInput && titleInput.value) {
                const langCode = languageInput.value;
                const langMap = {'ta': 'tamil', 'kn': 'kannada', 'te': 'telugu', 'ml': 'malayalam', 'hi': 'hindi'};
                activeTab = langMap[langCode] || activeTab;
            }
        });
    }
    
    // Function to switch to a specific tab
    function switchToTab(language) {
        // Remove active class from all tabs
        tabs.forEach(t => {
            t.classList.remove('active');
            t.style.background = 'var(--intel-gray-100)';
            t.style.color = 'var(--intel-gray-600)';
            t.style.fontWeight = '500';
            t.style.borderBottom = 'none';
        });
        
        // Add active class to target tab
        const targetTab = document.querySelector(`[data-language="${language}"]`);
        if (targetTab) {
            targetTab.classList.add('active');
            targetTab.style.background = 'white';
            targetTab.style.color = 'var(--intel-blue-600)';
            targetTab.style.fontWeight = '600';
            targetTab.style.borderBottom = '2px solid var(--intel-blue-500)';
        }
        
        // Hide all content
        contents.forEach(content => {
            content.style.display = 'none';
        });
        
        // Show selected content
        const selectedContent = document.getElementById(language + '-content');
        if (selectedContent) {
            selectedContent.style.display = 'block';
        }
        
        // Save active tab
        localStorage.setItem('activeTranslationTab', language);
    }
    
    // Initialize with the active tab
    switchToTab(activeTab);
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const language = this.getAttribute('data-language');
            switchToTab(language);
        });
    });
});

function createNewTranslation(language) {
    // Map display language names to language codes
    const languageMap = {
        'tamil': 'ta',
        'kannada': 'kn', 
        'telugu': 'te',
        'malayalam': 'ml',
        'hindi': 'hi'
    };
    
    const langCode = languageMap[language] || language;
    
    // Clear form and show input fields for the specified language
    const titleField = document.getElementById(`title_${language}`);
    const descriptionField = document.getElementById(`description_${language}`);
    const contentField = document.getElementById(`content_${language}`);
    
    if (titleField) titleField.value = '';
    if (descriptionField) descriptionField.value = '';
    if (contentField) contentField.value = '';
    
    // Focus on title field
    if (titleField) titleField.focus();
}

function previewTranslation(language = 'malayalam') {
    const titleField = document.getElementById(`title_${language}`);
    const contentField = document.getElementById(`content_${language}`);
    
    const title = titleField ? titleField.value || `${language.charAt(0).toUpperCase() + language.slice(1)} Translation Preview` : 'Translation Preview';
    const content = contentField ? contentField.value || 'No content available' : 'No content available';
    
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

function clearForm(language = 'malayalam') {
    if (confirm('Are you sure you want to clear all form data? This action cannot be undone.')) {
        const titleField = document.getElementById(`title_${language}`);
        const descriptionField = document.getElementById(`description_${language}`);
        const contentField = document.getElementById(`content_${language}`);
        
        if (titleField) titleField.value = '';
        if (descriptionField) descriptionField.value = '';
        if (contentField) contentField.value = '';
    }
}

function editTranslation(language) {
    // Map display language names to language codes
    const languageMap = {
        'tamil': 'ta',
        'kannada': 'kn', 
        'telugu': 'te',
        'malayalam': 'ml',
        'hindi': 'hi'
    };
    
    const langCode = languageMap[language] || language;
    const prophecyId = {{ $prophecy->id ?? 1 }};
    
    // Navigate to edit translation page
    window.location.href = `/admin/prophecies/${prophecyId}/translations/${langCode}/edit`;
}
</script>

<!-- TinyMCE Self-hosted Integration -->
<script src="{{ asset('assets/tinymce/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script>
// Initialize TinyMCE for all content and prayer points editors
document.addEventListener('DOMContentLoaded', function() {
    // Initialize TinyMCE for content fields
    tinymce.init({
        selector: '#content_malayalam, #content_tamil, #content_kannada, #content_telugu',
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
                const textarea = document.getElementById(editor.id);
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

    // Initialize TinyMCE for prayer points fields
    tinymce.init({
        selector: '#prayer_points_malayalam, #prayer_points_tamil, #prayer_points_kannada, #prayer_points_telugu',
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
                // Add sample prayer points structure if empty
                if (!editor.getContent()) {
                    const language = editor.id.split('_')[2]; // Extract language from ID
                    const languageNames = {
                        'malayalam': 'Malayalam',
                        'tamil': 'Tamil', 
                        'kannada': 'Kannada',
                        'telugu': 'Telugu'
                    };
                    const langName = languageNames[language] || language;
                    
                    editor.setContent(`
                        <ol>
                            <li><strong>Prayer Point 1:</strong> [Enter your first prayer point in ${langName}]</li>
                            <li><strong>Prayer Point 2:</strong> [Enter your second prayer point in ${langName}]</li>
                            <li><strong>Prayer Point 3:</strong> [Enter your third prayer point in ${langName}]</li>
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
});

// Update preview function to include prayer points from TinyMCE
function previewTranslation(language = 'malayalam') {
    const titleField = document.getElementById(`title_${language}`);
    const contentEditor = tinymce.get(`content_${language}`);
    const prayerPointsEditor = tinymce.get(`prayer_points_${language}`);
    
    const title = titleField ? titleField.value || `${language.charAt(0).toUpperCase() + language.slice(1)} Translation Preview` : 'Translation Preview';
    const content = contentEditor ? contentEditor.getContent() || 'No content available' : 'No content available';
    const prayerPoints = prayerPointsEditor ? prayerPointsEditor.getContent() : '';
    
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

// Update clear form function to clear TinyMCE editors
function clearForm(language = 'malayalam') {
    if (confirm('Are you sure you want to clear all form data? This action cannot be undone.')) {
        const titleField = document.getElementById(`title_${language}`);
        const descriptionField = document.getElementById(`description_${language}`);
        const excerptField = document.getElementById(`excerpt_${language}`);
        const contentEditor = tinymce.get(`content_${language}`);
        const prayerPointsEditor = tinymce.get(`prayer_points_${language}`);
        
        if (titleField) titleField.value = '';
        if (descriptionField) descriptionField.value = '';
        if (excerptField) excerptField.value = '';
        if (contentEditor) contentEditor.setContent('');
        if (prayerPointsEditor) prayerPointsEditor.setContent('');
    }
}

// Add form validation for translations
document.addEventListener('DOMContentLoaded', function() {
    // Add form validation to all translation forms
    const forms = document.querySelectorAll('form[method="POST"]');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            // Ensure TinyMCE content is saved to textareas before validation
            if (typeof tinymce !== 'undefined') {
                tinymce.triggerSave();
                
                // Get the language from the form's hidden input
                const languageInput = form.querySelector('input[name="language"]');
                if (languageInput) {
                    const langCode = languageInput.value;
                    const langMap = {'ta': 'tamil', 'kn': 'kannada', 'te': 'telugu', 'ml': 'malayalam', 'hi': 'hindi'};
                    const language = langMap[langCode] || 'malayalam';
                    
                    // Custom validation for required TinyMCE fields
                    const contentEditor = tinymce.get(`content_${language}`);
                    if (contentEditor) {
                        const content = contentEditor.getContent({format: 'text'}).trim();
                        if (!content) {
                            e.preventDefault();
                            alert('Please enter the prophecy content in the selected language.');
                            contentEditor.focus();
                            return false;
                        }
                    }
                }
            }
        });
    });
});

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
    const input = document.getElementById(`pdf_file_${language}`);
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