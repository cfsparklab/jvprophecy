@extends('layouts.admin')

@section('title', 'Bulk Operations')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Bulk Operations</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage multiple records efficiently</p>
                </div>
                <div class="flex space-x-3">
                    <button id="refreshBtn" class="intel-btn-secondary">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Prophecies -->
            <div class="intel-card p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Prophecies</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_prophecies']) }}</p>
                        <p class="text-sm text-blue-600">
                            <i class="fas fa-check-circle mr-1"></i>{{ $stats['published_prophecies'] }} published
                        </p>
                    </div>
                </div>
            </div>

            <!-- Draft Prophecies -->
            <div class="intel-card p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-edit text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Draft Prophecies</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['draft_prophecies']) }}</p>
                        <p class="text-sm text-orange-600">
                            <i class="fas fa-clock mr-1"></i>Pending publication
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="intel-card p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</p>
                        <p class="text-sm text-green-600">
                            <i class="fas fa-user-check mr-1"></i>{{ $stats['active_users'] }} active
                        </p>
                    </div>
                </div>
            </div>

            <!-- Translations -->
            <div class="intel-card p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-language text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Translations</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_translations']) }}</p>
                        <p class="text-sm text-purple-600">
                            <i class="fas fa-globe mr-1"></i>6 languages
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Operations Tabs -->
        <div class="intel-card rounded-lg overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6" id="bulk-tabs">
                    <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm active" data-tab="prophecies">
                        <i class="fas fa-book mr-2"></i>Prophecies
                    </button>
                    <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm" data-tab="users">
                        <i class="fas fa-users mr-2"></i>Users
                    </button>
                    <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm" data-tab="import-export">
                        <i class="fas fa-exchange-alt mr-2"></i>Import/Export
                    </button>
                    <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm" data-tab="cleanup">
                        <i class="fas fa-broom mr-2"></i>Cleanup
                    </button>
                </nav>
            </div>

            <!-- Prophecies Tab -->
            <div id="tab-prophecies" class="tab-content p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Bulk Prophecy Operations</h3>
                    <p class="text-sm text-gray-600 mb-4">Select prophecies and perform bulk actions</p>
                    
                    <!-- Prophecy Filters -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <select id="prophecyStatus" class="intel-select">
                            <option value="">All Status</option>
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="archived">Archived</option>
                        </select>
                        <select id="prophecyVisibility" class="intel-select">
                            <option value="">All Visibility</option>
                            <option value="public">Public</option>
                            <option value="private">Private</option>
                            <option value="restricted">Restricted</option>
                        </select>
                        <select id="prophecyCategory" class="intel-select">
                            <option value="">All Categories</option>
                            <!-- Categories will be loaded dynamically -->
                        </select>
                        <button id="loadPropheciesBtn" class="intel-btn-primary">
                            <i class="fas fa-search mr-2"></i>Load Prophecies
                        </button>
                    </div>

                    <!-- Prophecy Actions -->
                    <div class="flex flex-wrap gap-3 mb-6">
                        <button id="selectAllProphecies" class="intel-btn-secondary text-sm">
                            <i class="fas fa-check-square mr-1"></i>Select All
                        </button>
                        <button id="deselectAllProphecies" class="intel-btn-secondary text-sm">
                            <i class="fas fa-square mr-1"></i>Deselect All
                        </button>
                        <div class="border-l border-gray-300 mx-2"></div>
                        <button class="bulk-action-btn intel-btn-success text-sm" data-action="publish">
                            <i class="fas fa-eye mr-1"></i>Publish
                        </button>
                        <button class="bulk-action-btn intel-btn-warning text-sm" data-action="unpublish">
                            <i class="fas fa-eye-slash mr-1"></i>Unpublish
                        </button>
                        <button class="bulk-action-btn intel-btn-secondary text-sm" data-action="archive">
                            <i class="fas fa-archive mr-1"></i>Archive
                        </button>
                        <button class="bulk-action-btn intel-btn-danger text-sm" data-action="delete">
                            <i class="fas fa-trash mr-1"></i>Delete
                        </button>
                    </div>

                    <!-- Prophecies Table -->
                    <div id="propheciesTableContainer" class="overflow-x-auto">
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-search text-4xl mb-4"></i>
                            <p>Click "Load Prophecies" to view and select prophecies for bulk operations</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Tab -->
            <div id="tab-users" class="tab-content p-6 hidden">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Bulk User Operations</h3>
                    <p class="text-sm text-gray-600 mb-4">Select users and perform bulk actions</p>
                    
                    <!-- User Filters -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <select id="userStatus" class="intel-select">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                        </select>
                        <select id="userRole" class="intel-select">
                            <option value="">All Roles</option>
                            <option value="user">User</option>
                            <option value="editor">Editor</option>
                            <option value="admin">Admin</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                        <input type="text" id="userSearch" placeholder="Search users..." class="intel-input">
                        <button id="loadUsersBtn" class="intel-btn-primary">
                            <i class="fas fa-search mr-2"></i>Load Users
                        </button>
                    </div>

                    <!-- User Actions -->
                    <div class="flex flex-wrap gap-3 mb-6">
                        <button id="selectAllUsers" class="intel-btn-secondary text-sm">
                            <i class="fas fa-check-square mr-1"></i>Select All
                        </button>
                        <button id="deselectAllUsers" class="intel-btn-secondary text-sm">
                            <i class="fas fa-square mr-1"></i>Deselect All
                        </button>
                        <div class="border-l border-gray-300 mx-2"></div>
                        <button class="bulk-user-action-btn intel-btn-success text-sm" data-action="activate">
                            <i class="fas fa-user-check mr-1"></i>Activate
                        </button>
                        <button class="bulk-user-action-btn intel-btn-warning text-sm" data-action="deactivate">
                            <i class="fas fa-user-times mr-1"></i>Deactivate
                        </button>
                        <button class="bulk-user-action-btn intel-btn-secondary text-sm" data-action="suspend">
                            <i class="fas fa-user-lock mr-1"></i>Suspend
                        </button>
                        <button class="bulk-user-action-btn intel-btn-danger text-sm" data-action="delete">
                            <i class="fas fa-trash mr-1"></i>Delete
                        </button>
                    </div>

                    <!-- Users Table -->
                    <div id="usersTableContainer" class="overflow-x-auto">
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-search text-4xl mb-4"></i>
                            <p>Click "Load Users" to view and select users for bulk operations</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Import/Export Tab -->
            <div id="tab-import-export" class="tab-content p-6 hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Import Section -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Import Data</h3>
                        <div class="space-y-6">
                            <!-- Import Prophecies -->
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900 mb-3">Import Prophecies</h4>
                                <form id="importPropheciesForm" enctype="multipart/form-data">
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Select File</label>
                                            <input type="file" id="importFile" name="import_file" accept=".csv,.json" class="intel-input">
                                            <p class="text-xs text-gray-500 mt-1">Supported formats: CSV, JSON (Max: 10MB)</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Import Type</label>
                                            <select id="importType" name="import_type" class="intel-select">
                                                <option value="csv">CSV</option>
                                                <option value="json">JSON</option>
                                            </select>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" id="updateExisting" name="update_existing" class="rounded border-gray-300 text-blue-600">
                                            <label for="updateExisting" class="ml-2 text-sm text-gray-700">Update existing records</label>
                                        </div>
                                        <button type="submit" class="intel-btn-primary w-full">
                                            <i class="fas fa-upload mr-2"></i>Import Prophecies
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Export Section -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Export Data</h3>
                        <div class="space-y-6">
                            <!-- Export Prophecies -->
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900 mb-3">Export Prophecies</h4>
                                <form id="exportPropheciesForm">
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Export Format</label>
                                            <select id="exportFormat" name="format" class="intel-select">
                                                <option value="csv">CSV</option>
                                                <option value="json">JSON</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Filters</label>
                                            <div class="space-y-2">
                                                <select name="filters[status]" class="intel-select">
                                                    <option value="">All Status</option>
                                                    <option value="published">Published</option>
                                                    <option value="draft">Draft</option>
                                                    <option value="archived">Archived</option>
                                                </select>
                                                <select name="filters[visibility]" class="intel-select">
                                                    <option value="">All Visibility</option>
                                                    <option value="public">Public</option>
                                                    <option value="private">Private</option>
                                                    <option value="restricted">Restricted</option>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="submit" class="intel-btn-primary w-full">
                                            <i class="fas fa-download mr-2"></i>Export Prophecies
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cleanup Tab -->
            <div id="tab-cleanup" class="tab-content p-6 hidden">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">System Cleanup</h3>
                <p class="text-sm text-gray-600 mb-6">Clean up system data to improve performance</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Clean Logs -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-file-alt text-blue-600"></i>
                            </div>
                            <h4 class="font-medium text-gray-900">Clean Logs</h4>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">Remove old log files</p>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Days to keep</label>
                            <input type="number" id="logDays" value="30" min="1" max="365" class="intel-input">
                        </div>
                        <button class="cleanup-btn intel-btn-primary w-full" data-type="logs">
                            <i class="fas fa-broom mr-2"></i>Clean Logs
                        </button>
                    </div>

                    <!-- Clean Cache -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-memory text-green-600"></i>
                            </div>
                            <h4 class="font-medium text-gray-900">Clean Cache</h4>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">Clear all application cache</p>
                        <button class="cleanup-btn intel-btn-primary w-full" data-type="cache">
                            <i class="fas fa-broom mr-2"></i>Clean Cache
                        </button>
                    </div>

                    <!-- Clean Temp Files -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-folder text-orange-600"></i>
                            </div>
                            <h4 class="font-medium text-gray-900">Clean Temp Files</h4>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">Remove temporary files</p>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Days to keep</label>
                            <input type="number" id="tempDays" value="7" min="1" max="30" class="intel-input">
                        </div>
                        <button class="cleanup-btn intel-btn-primary w-full" data-type="temp_files">
                            <i class="fas fa-broom mr-2"></i>Clean Temp Files
                        </button>
                    </div>

                    <!-- Clean Orphaned Translations -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-language text-purple-600"></i>
                            </div>
                            <h4 class="font-medium text-gray-900">Orphaned Translations</h4>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">Remove translations without prophecies</p>
                        <button class="cleanup-btn intel-btn-primary w-full" data-type="orphaned_translations">
                            <i class="fas fa-broom mr-2"></i>Clean Translations
                        </button>
                    </div>

                    <!-- Clean Old Backups -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-archive text-red-600"></i>
                            </div>
                            <h4 class="font-medium text-gray-900">Old Backups</h4>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">Remove old backup files</p>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Days to keep</label>
                            <input type="number" id="backupDays" value="90" min="1" max="365" class="intel-input">
                        </div>
                        <button class="cleanup-btn intel-btn-primary w-full" data-type="old_backups">
                            <i class="fas fa-broom mr-2"></i>Clean Backups
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div id="loadingModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                <i class="fas fa-spinner fa-spin text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Processing...</h3>
            <p id="loadingMessage" class="text-sm text-gray-500 mt-2">Please wait while we process your request.</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            
            // Update button states
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            this.classList.add('active', 'border-blue-500', 'text-blue-600');
            this.classList.remove('border-transparent', 'text-gray-500');
            
            // Update content visibility
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById(`tab-${tabId}`).classList.remove('hidden');
        });
    });

    // Loading modal functions
    const loadingModal = document.getElementById('loadingModal');
    const loadingMessage = document.getElementById('loadingMessage');

    function showLoading(message = 'Processing...') {
        loadingMessage.textContent = message;
        loadingModal.classList.remove('hidden');
    }

    function hideLoading() {
        loadingModal.classList.add('hidden');
    }

    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' : 
            type === 'error' ? 'bg-red-500 text-white' : 
            'bg-blue-500 text-white'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 5000);
    }

    // Cleanup operations
    document.querySelectorAll('.cleanup-btn').forEach(button => {
        button.addEventListener('click', function() {
            const type = this.dataset.type;
            let daysOld = null;
            
            if (type === 'logs') {
                daysOld = document.getElementById('logDays').value;
            } else if (type === 'temp_files') {
                daysOld = document.getElementById('tempDays').value;
            } else if (type === 'old_backups') {
                daysOld = document.getElementById('backupDays').value;
            }

            showLoading(`Cleaning ${type.replace('_', ' ')}...`);

            const requestData = { cleanup_type: type };
            if (daysOld) {
                requestData.days_old = parseInt(daysOld);
            }

            fetch('{{ route("admin.bulk.cleanup") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(requestData)
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                hideLoading();
                showNotification('Cleanup operation failed', 'error');
            });
        });
    });

    // Import form
    document.getElementById('importPropheciesForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        showLoading('Importing prophecies...');

        fetch('{{ route("admin.bulk.import-prophecies") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showNotification(`Successfully imported ${data.imported_count} prophecies`, 'success');
                this.reset();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            hideLoading();
            showNotification('Import failed', 'error');
        });
    });

    // Export form
    document.getElementById('exportPropheciesForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const params = new URLSearchParams(formData);
        
        window.open('{{ route("admin.bulk.export-prophecies") }}?' + params.toString(), '_blank');
        showNotification('Export started', 'info');
    });

    // Refresh button
    document.getElementById('refreshBtn').addEventListener('click', function() {
        location.reload();
    });
});
</script>
@endpush
@endsection
