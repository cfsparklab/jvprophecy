@extends('layouts.admin')

@section('page-title', 'Create Category')

@section('admin-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.categories.index') }}" 
               class="text-gray-600 hover:text-gray-900 transition-colors">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Create New Category</h1>
                <p class="text-gray-600 mt-1">Add a new category to organize prophecies</p>
            </div>
        </div>
    </div>
    
    <!-- Form -->
    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-6">
            @csrf
            
            <div class="intel-card rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Category Information</h3>
                
                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" required
                           value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Enter category name">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Enter category description">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Parent Category -->
                <div class="mb-6">
                    <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Parent Category
                    </label>
                    <select id="parent_id" name="parent_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select parent category (optional)</option>
                        @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Icon and Color -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                            Icon Class
                        </label>
                        <input type="text" id="icon" name="icon"
                               value="{{ old('icon', 'fas fa-folder') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                               placeholder="fas fa-folder">
                        <p class="text-xs text-gray-500 mt-1">Use Font Awesome icon classes</p>
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                            Color
                        </label>
                        <input type="color" id="color" name="color"
                               value="{{ old('color', '#3B82F6') }}"
                               class="w-full h-10 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        @error('color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Status and Sort Order -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            Sort Order
                        </label>
                        <input type="number" id="sort_order" name="sort_order" min="0"
                               value="{{ old('sort_order', 0) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0">
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Preview -->
            <div class="intel-card rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Preview</h3>
                <div id="category-preview" class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                    <div id="preview-icon" class="w-12 h-12 rounded-full flex items-center justify-center text-xl"
                         style="background-color: #3B82F620; color: #3B82F6">
                        <i class="fas fa-folder"></i>
                    </div>
                    <div>
                        <div id="preview-name" class="text-lg font-semibold text-gray-900">Category Name</div>
                        <div id="preview-description" class="text-sm text-gray-600">Category description will appear here</div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6">
                <a href="{{ route('admin.categories.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="intel-btn-primary px-6 py-2 rounded-lg font-medium">
                    <i class="fas fa-save mr-2"></i>Create Category
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const iconInput = document.getElementById('icon');
    const colorInput = document.getElementById('color');
    
    const previewName = document.getElementById('preview-name');
    const previewDescription = document.getElementById('preview-description');
    const previewIcon = document.getElementById('preview-icon');
    const previewIconElement = previewIcon.querySelector('i');
    
    function updatePreview() {
        // Update name
        previewName.textContent = nameInput.value || 'Category Name';
        
        // Update description
        previewDescription.textContent = descriptionInput.value || 'Category description will appear here';
        
        // Update icon
        previewIconElement.className = iconInput.value || 'fas fa-folder';
        
        // Update color
        const color = colorInput.value || '#3B82F6';
        previewIcon.style.backgroundColor = color + '20';
        previewIcon.style.color = color;
    }
    
    nameInput.addEventListener('input', updatePreview);
    descriptionInput.addEventListener('input', updatePreview);
    iconInput.addEventListener('input', updatePreview);
    colorInput.addEventListener('input', updatePreview);
});
</script>
@endpush
@endsection
