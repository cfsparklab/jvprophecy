<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prophecy;
use App\Models\User;
use App\Models\Category;
use App\Models\ProphecyTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BulkOperationsController extends Controller
{
    /**
     * Display the bulk operations dashboard.
     */
    public function index()
    {
        // Get statistics for bulk operations
        $stats = [
            'total_prophecies' => Prophecy::count(),
            'draft_prophecies' => Prophecy::where('status', 'draft')->count(),
            'published_prophecies' => Prophecy::where('status', 'published')->count(),
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'inactive_users' => User::where('status', 'inactive')->count(),
            'total_categories' => Category::count(),
            'total_translations' => ProphecyTranslation::count(),
        ];

        // Recent bulk operations (you might want to create a bulk_operations table for this)
        $recentOperations = [
            // This would come from a bulk_operations log table in a real implementation
        ];

        return view('admin.bulk.index', compact('stats', 'recentOperations'));
    }

    /**
     * Bulk update prophecies.
     */
    public function bulkUpdateProphecies(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'prophecy_ids' => 'required|array',
            'prophecy_ids.*' => 'exists:prophecies,id',
            'action' => 'required|in:publish,unpublish,archive,delete,change_category,change_visibility',
            'category_id' => 'nullable|exists:categories,id',
            'visibility' => 'nullable|in:public,private,restricted',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $prophecyIds = $request->prophecy_ids;
            $action = $request->action;
            $updatedCount = 0;

            DB::beginTransaction();

            switch ($action) {
                case 'publish':
                    $updatedCount = Prophecy::whereIn('id', $prophecyIds)
                        ->update([
                            'status' => 'published',
                            'published_at' => Carbon::now()
                        ]);
                    break;

                case 'unpublish':
                    $updatedCount = Prophecy::whereIn('id', $prophecyIds)
                        ->update([
                            'status' => 'draft',
                            'published_at' => null
                        ]);
                    break;

                case 'archive':
                    $updatedCount = Prophecy::whereIn('id', $prophecyIds)
                        ->update(['status' => 'archived']);
                    break;

                case 'delete':
                    // Delete translations first
                    ProphecyTranslation::whereIn('prophecy_id', $prophecyIds)->delete();
                    // Then delete prophecies
                    $updatedCount = Prophecy::whereIn('id', $prophecyIds)->delete();
                    break;

                case 'change_category':
                    if (!$request->category_id) {
                        throw new \Exception('Category ID is required for category change');
                    }
                    $updatedCount = Prophecy::whereIn('id', $prophecyIds)
                        ->update(['category_id' => $request->category_id]);
                    break;

                case 'change_visibility':
                    if (!$request->visibility) {
                        throw new \Exception('Visibility is required for visibility change');
                    }
                    $updatedCount = Prophecy::whereIn('id', $prophecyIds)
                        ->update(['visibility' => $request->visibility]);
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully {$action}ed {$updatedCount} prophecies",
                'updated_count' => $updatedCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Bulk operation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update users.
     */
    public function bulkUpdateUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'action' => 'required|in:activate,deactivate,suspend,delete,assign_role,remove_role',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $userIds = $request->user_ids;
            $action = $request->action;
            $updatedCount = 0;

            DB::beginTransaction();

            switch ($action) {
                case 'activate':
                    $updatedCount = User::whereIn('id', $userIds)
                        ->update(['status' => 'active']);
                    break;

                case 'deactivate':
                    $updatedCount = User::whereIn('id', $userIds)
                        ->update(['status' => 'inactive']);
                    break;

                case 'suspend':
                    $updatedCount = User::whereIn('id', $userIds)
                        ->update(['status' => 'suspended']);
                    break;

                case 'delete':
                    // Remove user roles first
                    DB::table('user_roles')->whereIn('user_id', $userIds)->delete();
                    // Then delete users
                    $updatedCount = User::whereIn('id', $userIds)->delete();
                    break;

                case 'assign_role':
                    if (!$request->role_id) {
                        throw new \Exception('Role ID is required for role assignment');
                    }
                    
                    foreach ($userIds as $userId) {
                        DB::table('user_roles')->updateOrInsert(
                            ['user_id' => $userId, 'role_id' => $request->role_id],
                            ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
                        );
                        $updatedCount++;
                    }
                    break;

                case 'remove_role':
                    if (!$request->role_id) {
                        throw new \Exception('Role ID is required for role removal');
                    }
                    
                    $updatedCount = DB::table('user_roles')
                        ->whereIn('user_id', $userIds)
                        ->where('role_id', $request->role_id)
                        ->delete();
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully processed {$updatedCount} users",
                'updated_count' => $updatedCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Bulk operation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import prophecies from CSV/JSON.
     */
    public function importProphecies(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'import_file' => 'required|file|mimes:csv,json|max:10240', // 10MB max
            'import_type' => 'required|in:csv,json',
            'update_existing' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('import_file');
            $importType = $request->import_type;
            $updateExisting = $request->boolean('update_existing', false);

            $importedCount = 0;
            $errorCount = 0;
            $errors = [];

            DB::beginTransaction();

            if ($importType === 'csv') {
                $importedCount = $this->importFromCSV($file, $updateExisting, $errors);
            } else {
                $importedCount = $this->importFromJSON($file, $updateExisting, $errors);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully imported {$importedCount} prophecies",
                'imported_count' => $importedCount,
                'error_count' => count($errors),
                'errors' => array_slice($errors, 0, 10) // Show only first 10 errors
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export prophecies to CSV/JSON.
     */
    public function exportProphecies(Request $request)
    {
        $format = $request->get('format', 'csv');
        $filters = $request->get('filters', []);

        try {
            $query = Prophecy::with(['category', 'creator', 'translations']);

            // Apply filters
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (!empty($filters['category_id'])) {
                $query->where('category_id', $filters['category_id']);
            }

            if (!empty($filters['visibility'])) {
                $query->where('visibility', $filters['visibility']);
            }

            if (!empty($filters['date_from'])) {
                $query->where('created_at', '>=', $filters['date_from']);
            }

            if (!empty($filters['date_to'])) {
                $query->where('created_at', '<=', $filters['date_to']);
            }

            $prophecies = $query->get();

            if ($format === 'json') {
                return $this->exportToJSON($prophecies);
            } else {
                return $this->exportToCSV($prophecies);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clean up system data.
     */
    public function cleanup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cleanup_type' => 'required|in:logs,cache,temp_files,orphaned_translations,old_backups',
            'days_old' => 'nullable|integer|min:1|max:365',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $cleanupType = $request->cleanup_type;
            $daysOld = $request->get('days_old', 30);
            $cleanedCount = 0;

            switch ($cleanupType) {
                case 'logs':
                    $cleanedCount = $this->cleanupLogs($daysOld);
                    break;

                case 'cache':
                    $cleanedCount = $this->cleanupCache();
                    break;

                case 'temp_files':
                    $cleanedCount = $this->cleanupTempFiles($daysOld);
                    break;

                case 'orphaned_translations':
                    $cleanedCount = $this->cleanupOrphanedTranslations();
                    break;

                case 'old_backups':
                    $cleanedCount = $this->cleanupOldBackups($daysOld);
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => "Cleanup completed. Removed {$cleanedCount} items",
                'cleaned_count' => $cleanedCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cleanup failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import prophecies from CSV file.
     */
    private function importFromCSV($file, $updateExisting, &$errors)
    {
        $importedCount = 0;
        $handle = fopen($file->getRealPath(), 'r');
        
        // Skip header row
        $header = fgetcsv($handle);
        
        while (($data = fgetcsv($handle)) !== FALSE) {
            try {
                $prophecyData = [
                    'title' => $data[0] ?? '',
                    'description' => $data[1] ?? '',
                    'jebikalam_vanga_date' => $data[2] ?? null,
                    'status' => $data[3] ?? 'draft',
                    'visibility' => $data[4] ?? 'public',
                    'category_id' => $data[5] ?? null,
                    'created_by' => auth()->id(),
                ];

                if ($updateExisting && !empty($data[6])) {
                    // Update existing prophecy by ID
                    Prophecy::where('id', $data[6])->update($prophecyData);
                } else {
                    // Create new prophecy
                    Prophecy::create($prophecyData);
                }
                
                $importedCount++;
            } catch (\Exception $e) {
                $errors[] = "Row " . ($importedCount + 2) . ": " . $e->getMessage();
            }
        }
        
        fclose($handle);
        return $importedCount;
    }

    /**
     * Import prophecies from JSON file.
     */
    private function importFromJSON($file, $updateExisting, &$errors)
    {
        $importedCount = 0;
        $jsonData = json_decode(file_get_contents($file->getRealPath()), true);
        
        if (!is_array($jsonData)) {
            throw new \Exception('Invalid JSON format');
        }

        foreach ($jsonData as $index => $prophecyData) {
            try {
                $prophecyData['created_by'] = auth()->id();
                
                if ($updateExisting && !empty($prophecyData['id'])) {
                    // Update existing prophecy
                    Prophecy::where('id', $prophecyData['id'])->update($prophecyData);
                } else {
                    // Create new prophecy
                    unset($prophecyData['id']); // Remove ID for new records
                    Prophecy::create($prophecyData);
                }
                
                $importedCount++;
            } catch (\Exception $e) {
                $errors[] = "Record " . ($index + 1) . ": " . $e->getMessage();
            }
        }
        
        return $importedCount;
    }

    /**
     * Export prophecies to CSV.
     */
    private function exportToCSV($prophecies)
    {
        $filename = 'prophecies_export_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($prophecies) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Title', 'Description', 'Jebikalam Vaanga Date', 'Status', 'Visibility', 'Category', 'Translations', 'Created At']);
            
            foreach ($prophecies as $prophecy) {
                fputcsv($file, [
                    $prophecy->id,
                    $prophecy->title,
                    strip_tags($prophecy->description),
                    $prophecy->jebikalam_vanga_date?->format('d/m/Y'),
                    $prophecy->status,
                    $prophecy->visibility,
                    $prophecy->category?->name,
                    $prophecy->translations->count(),
                    $prophecy->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export prophecies to JSON.
     */
    private function exportToJSON($prophecies)
    {
        $filename = 'prophecies_export_' . date('Y-m-d_H-i-s') . '.json';
        $headers = [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->json($prophecies->toArray(), 200, $headers);
    }

    /**
     * Cleanup old log files.
     */
    private function cleanupLogs($daysOld)
    {
        $logPath = storage_path('logs');
        $cutoffDate = Carbon::now()->subDays($daysOld);
        $cleanedCount = 0;

        $files = glob($logPath . '/*.log');
        
        foreach ($files as $file) {
            if (filemtime($file) < $cutoffDate->timestamp) {
                unlink($file);
                $cleanedCount++;
            }
        }

        return $cleanedCount;
    }

    /**
     * Cleanup cache.
     */
    private function cleanupCache()
    {
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        
        return 4; // Number of cache types cleared
    }

    /**
     * Cleanup temporary files.
     */
    private function cleanupTempFiles($daysOld)
    {
        $tempPath = storage_path('app/temp');
        $cutoffDate = Carbon::now()->subDays($daysOld);
        $cleanedCount = 0;

        if (is_dir($tempPath)) {
            $files = glob($tempPath . '/*');
            
            foreach ($files as $file) {
                if (filemtime($file) < $cutoffDate->timestamp) {
                    if (is_file($file)) {
                        unlink($file);
                        $cleanedCount++;
                    }
                }
            }
        }

        return $cleanedCount;
    }

    /**
     * Cleanup orphaned translations.
     */
    private function cleanupOrphanedTranslations()
    {
        return ProphecyTranslation::whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('prophecies')
                  ->whereRaw('prophecies.id = prophecy_translations.prophecy_id');
        })->delete();
    }

    /**
     * Cleanup old backup files.
     */
    private function cleanupOldBackups($daysOld)
    {
        $backupPath = storage_path('app/backups');
        $cutoffDate = Carbon::now()->subDays($daysOld);
        $cleanedCount = 0;

        if (is_dir($backupPath)) {
            $files = glob($backupPath . '/*');
            
            foreach ($files as $file) {
                if (filemtime($file) < $cutoffDate->timestamp) {
                    unlink($file);
                    $cleanedCount++;
                }
            }
        }

        return $cleanedCount;
    }
}