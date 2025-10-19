@extends('layouts.admin')

@section('page-title', 'Edit Category')

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
                <h1 class="text-2xl font-bold text-gray-900">Edit Category</h1>
                <p class="text-gray-600 mt-1">Update category information and settings</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.categories.show', $category) }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-eye mr-2"></i>View Details
            </a>
        </div>
    </div>
    
    <!-- Form -->
    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="intel-card rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Category Information</h3>
                
                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" required
                           value="{{ old('name', $category->name) }}"
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
                              placeholder="Enter category description">{{ old('description', $category->description) }}</textarea>
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
                        <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
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
                               value="{{ old('icon', $category->icon) }}"
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
                               value="{{ old('color', $category->color) }}"
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
                            <option value="active" {{ old('status', $category->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $category->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                               value="{{ old('sort_order', $category->sort_order) }}"
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
                         style="background-color: {{ $category->color }}20; color: {{ $category->color }}">
                        <i class="{{ $category->icon }}"></i>
                    </div>
                    <div>
                        <div id="preview-name" class="text-lg font-semibold text-gray-900">{{ $category->name }}</div>
                        <div id="preview-description" class="text-sm text-gray-600">{{ $category->description ?: 'No description provided' }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Category Statistics -->
            <div class="intel-card rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Category Statistics</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $category->prophecies->count() }}</div>
                        <div class="text-sm text-gray-600">Prophecies</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $category->children->count() }}</div>
                        <div class="text-sm text-gray-600">Subcategories</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $category->prophecies->sum('view_count') }}</div>
                        <div class="text-sm text-gray-600">Total Views</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-orange-600">{{ $category->prophecies->sum('download_count') }}</div>
                        <div class="text-sm text-gray-600">Downloads</div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center justify-between pt-6">
                <div>
                    @if(auth()->user()->hasPermission('delete_categories'))
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" 
                          class="inline" onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                            <i class="fas fa-trash mr-2"></i>Delete Category
                        </button>
                    </form>
                    @endif
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.categories.index') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="intel-btn-primary px-6 py-2 rounded-lg font-medium">
                        <i class="fas fa-save mr-2"></i>Update Category
                    </button>
                </div>
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
        previewDescription.textContent = descriptionInput.value || 'No description provided';
        
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
