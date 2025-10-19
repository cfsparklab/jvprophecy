@extends('layouts.admin')

@section('page-title', 'Category Details')

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
                <h1 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
                <p class="text-gray-600 mt-1">Category Details and Statistics</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            @if(auth()->user()->hasPermission('edit_categories'))
            <a href="{{ route('admin.categories.edit', $category) }}" 
               class="intel-btn-primary px-4 py-2 rounded-lg text-sm font-medium">
                <i class="fas fa-edit mr-2"></i>Edit Category
            </a>
            @endif
            
            @if(auth()->user()->hasPermission('create_categories'))
            <a href="{{ route('admin.categories.create') }}?parent_id={{ $category->id }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-plus mr-2"></i>Add Subcategory
            </a>
            @endif
        </div>
    </div>
    
    <!-- Category Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Category Details -->
            <div class="intel-card rounded-lg p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-16 h-16 rounded-xl flex items-center justify-center text-2xl"
                         style="background-color: {{ $category->color }}20; color: {{ $category->color }}">
                        <i class="{{ $category->icon }}"></i>
                    </div>
                    
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h2 class="text-xl font-semibold text-gray-900">{{ $category->name }}</h2>
                            @if($category->status === 'active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Active
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-pause-circle mr-1"></i>Inactive
                            </span>
                            @endif
                        </div>
                        
                        @if($category->description)
                        <p class="text-gray-600 mb-4">{{ $category->description }}</p>
                        @endif
                        
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Slug:</span>
                                <span class="font-medium text-gray-900">{{ $category->slug }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Sort Order:</span>
                                <span class="font-medium text-gray-900">{{ $category->sort_order }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Created:</span>
                                <span class="font-medium text-gray-900">{{ $category->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Updated:</span>
                                <span class="font-medium text-gray-900">{{ $category->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Hierarchy -->
            @if($category->parent || $category->children->count() > 0)
            <div class="intel-card rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Category Hierarchy</h3>
                
                @if($category->parent)
                <div class="mb-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Parent Category</h4>
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                             style="background-color: {{ $category->parent->color }}20; color: {{ $category->parent->color }}">
                            <i class="{{ $category->parent->icon }} text-sm"></i>
                        </div>
                        <div>
                            <a href="{{ route('admin.categories.show', $category->parent) }}" 
                               class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                {{ $category->parent->name }}
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                
                @if($category->children->count() > 0)
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Subcategories ({{ $category->children->count() }})</h4>
                    <div class="space-y-2">
                        @foreach($category->children as $child)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                                     style="background-color: {{ $child->color }}20; color: {{ $child->color }}">
                                    <i class="{{ $child->icon }} text-sm"></i>
                                </div>
                                <div>
                                    <a href="{{ route('admin.categories.show', $child) }}" 
                                       class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                        {{ $child->name }}
                                    </a>
                                    <div class="text-xs text-gray-500">{{ $child->prophecies->count() }} prophecies</div>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $child->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($child->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endif
            
            <!-- Recent Prophecies -->
            @if($category->prophecies->count() > 0)
            <div class="intel-card rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Prophecies</h3>
                    <a href="{{ route('admin.prophecies.index') }}?category={{ $category->id }}" 
                       class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                </div>
                
                <div class="space-y-3">
                    @foreach($category->prophecies->take(5) as $prophecy)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <a href="{{ route('admin.prophecies.show', $prophecy) }}" 
                               class="text-sm font-medium text-gray-900 hover:text-blue-600">
                                {{ $prophecy->title }}
                            </a>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $prophecy->jebikalam_vanga_date ? \Carbon\Carbon::parse($prophecy->jebikalam_vanga_date)->format('d/m/Y') : 'No date' }}
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $prophecy->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($prophecy->status) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        
        <!-- Statistics Sidebar -->
        <div class="space-y-6">
            <!-- Statistics -->
            <div class="intel-card rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-scroll text-blue-600"></i>
                            <span class="text-sm text-gray-600">Total Prophecies</span>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">{{ $category->prophecies->count() }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-eye text-green-600"></i>
                            <span class="text-sm text-gray-600">Total Views</span>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">{{ $category->prophecies->sum('view_count') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-download text-purple-600"></i>
                            <span class="text-sm text-gray-600">Total Downloads</span>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">{{ $category->prophecies->sum('download_count') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-sitemap text-orange-600"></i>
                            <span class="text-sm text-gray-600">Subcategories</span>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">{{ $category->children->count() }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Status Overview -->
            <div class="intel-card rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Prophecy Status</h3>
                
                <div class="space-y-3">
                    @php
                        $published = $category->prophecies->where('status', 'published')->count();
                        $draft = $category->prophecies->where('status', 'draft')->count();
                        $pending = $category->prophecies->where('status', 'pending')->count();
                    @endphp
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Published</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $category->prophecies->count() > 0 ? ($published / $category->prophecies->count()) * 100 : 0 }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $published }}</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Draft</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $category->prophecies->count() > 0 ? ($draft / $category->prophecies->count()) * 100 : 0 }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $draft }}</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Pending</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $category->prophecies->count() > 0 ? ($pending / $category->prophecies->count()) * 100 : 0 }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $pending }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="intel-card rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                
                <div class="space-y-3">
                    @if(auth()->user()->hasPermission('create_prophecies'))
                    <a href="{{ route('admin.prophecies.create') }}?category={{ $category->id }}" 
                       class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add Prophecy
                    </a>
                    @endif
                    
                    @if(auth()->user()->hasPermission('create_categories'))
                    <a href="{{ route('admin.categories.create') }}?parent_id={{ $category->id }}" 
                       class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <i class="fas fa-sitemap mr-2"></i>Add Subcategory
                    </a>
                    @endif
                    
                    <a href="{{ route('admin.prophecies.index') }}?category={{ $category->id }}" 
                       class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <i class="fas fa-list mr-2"></i>View All Prophecies
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
