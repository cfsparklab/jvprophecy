<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SystemController extends Controller
{
    /**
     * Display the system management dashboard.
     */
    public function index()
    {
        // System Information
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database_version' => $this->getDatabaseVersion(),
            'timezone' => config('app.timezone'),
            'environment' => config('app.env'),
            'debug_mode' => config('app.debug'),
        ];

        // System Status
        $systemStatus = [
            'database_connection' => $this->checkDatabaseConnection(),
            'cache_status' => $this->checkCacheStatus(),
            'storage_writable' => $this->checkStorageWritable(),
            'log_writable' => $this->checkLogWritable(),
            'queue_status' => $this->checkQueueStatus(),
        ];

        // Storage Information
        $storageInfo = [
            'total_space' => $this->formatBytes(disk_total_space(storage_path())),
            'free_space' => $this->formatBytes(disk_free_space(storage_path())),
            'used_space' => $this->formatBytes(disk_total_space(storage_path()) - disk_free_space(storage_path())),
            'storage_usage_percent' => round(((disk_total_space(storage_path()) - disk_free_space(storage_path())) / disk_total_space(storage_path())) * 100, 2),
        ];

        // Performance Metrics
        $performanceMetrics = [
            'memory_usage' => $this->formatBytes(memory_get_usage(true)),
            'memory_peak' => $this->formatBytes(memory_get_peak_usage(true)),
            'memory_limit' => ini_get('memory_limit'),
            'execution_time' => round(microtime(true) - LARAVEL_START, 4) . 's',
            'database_queries' => DB::getQueryLog() ? count(DB::getQueryLog()) : 'Not tracked',
        ];

        // Recent System Logs
        $recentLogs = $this->getRecentSystemLogs();

        // Backup Information
        $backupInfo = $this->getBackupInformation();

        return view('admin.system.index', compact(
            'systemInfo',
            'systemStatus',
            'storageInfo',
            'performanceMetrics',
            'recentLogs',
            'backupInfo'
        ));
    }

    /**
     * Clear application cache.
     */
    public function clearCache(Request $request)
    {
        try {
            $cacheTypes = $request->get('cache_types', ['application', 'config', 'route', 'view']);
            $results = [];

            if (in_array('application', $cacheTypes)) {
                Artisan::call('cache:clear');
                $results[] = 'Application cache cleared';
            }

            if (in_array('config', $cacheTypes)) {
                Artisan::call('config:clear');
                $results[] = 'Configuration cache cleared';
            }

            if (in_array('route', $cacheTypes)) {
                Artisan::call('route:clear');
                $results[] = 'Route cache cleared';
            }

            if (in_array('view', $cacheTypes)) {
                Artisan::call('view:clear');
                $results[] = 'View cache cleared';
            }

            return response()->json([
                'success' => true,
                'message' => 'Cache cleared successfully',
                'details' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Optimize the application.
     */
    public function optimize(Request $request)
    {
        try {
            $optimizations = $request->get('optimizations', ['config', 'route', 'view']);
            $results = [];

            if (in_array('config', $optimizations)) {
                Artisan::call('config:cache');
                $results[] = 'Configuration cached';
            }

            if (in_array('route', $optimizations)) {
                Artisan::call('route:cache');
                $results[] = 'Routes cached';
            }

            if (in_array('view', $optimizations)) {
                Artisan::call('view:cache');
                $results[] = 'Views cached';
            }

            // Run additional optimizations
            Artisan::call('optimize');
            $results[] = 'Application optimized';

            return response()->json([
                'success' => true,
                'message' => 'System optimized successfully',
                'details' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to optimize system: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create system backup.
     */
    public function backup(Request $request)
    {
        try {
            $backupType = $request->get('type', 'full'); // full, database, files
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $backupPath = storage_path('app/backups');
            
            // Ensure backup directory exists
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }

            $results = [];

            if ($backupType === 'full' || $backupType === 'database') {
                // Database backup
                $dbBackupFile = $backupPath . "/database_backup_{$timestamp}.sql";
                $this->createDatabaseBackup($dbBackupFile);
                $results[] = 'Database backup created';
            }

            if ($backupType === 'full' || $backupType === 'files') {
                // Files backup (excluding vendor, node_modules, storage/logs)
                $filesBackupFile = $backupPath . "/files_backup_{$timestamp}.zip";
                $this->createFilesBackup($filesBackupFile);
                $results[] = 'Files backup created';
            }

            return response()->json([
                'success' => true,
                'message' => 'Backup created successfully',
                'details' => $results,
                'backup_path' => $backupPath
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View system logs.
     */
    public function logs(Request $request)
    {
        $logFile = $request->get('file', 'laravel.log');
        $lines = $request->get('lines', 100);
        
        $logPath = storage_path('logs/' . $logFile);
        
        if (!File::exists($logPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Log file not found'
            ], 404);
        }

        try {
            $logContent = $this->tailFile($logPath, $lines);
            $logFiles = $this->getAvailableLogFiles();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'content' => $logContent,
                    'available_files' => $logFiles
                ]);
            }

            return view('admin.system.logs', compact('logContent', 'logFiles', 'logFile'));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to read log file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get database version.
     */
    private function getDatabaseVersion()
    {
        try {
            return DB::select('SELECT VERSION() as version')[0]->version;
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    /**
     * Check database connection.
     */
    private function checkDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'connected', 'message' => 'Database connection successful'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()];
        }
    }

    /**
     * Check cache status.
     */
    private function checkCacheStatus()
    {
        try {
            Cache::put('system_check', 'test', 1);
            $value = Cache::get('system_check');
            Cache::forget('system_check');
            
            return $value === 'test' 
                ? ['status' => 'working', 'message' => 'Cache is working properly']
                : ['status' => 'error', 'message' => 'Cache is not working properly'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Cache error: ' . $e->getMessage()];
        }
    }

    /**
     * Check if storage is writable.
     */
    private function checkStorageWritable()
    {
        $storagePath = storage_path();
        return is_writable($storagePath)
            ? ['status' => 'writable', 'message' => 'Storage directory is writable']
            : ['status' => 'error', 'message' => 'Storage directory is not writable'];
    }

    /**
     * Check if log directory is writable.
     */
    private function checkLogWritable()
    {
        $logPath = storage_path('logs');
        return is_writable($logPath)
            ? ['status' => 'writable', 'message' => 'Log directory is writable']
            : ['status' => 'error', 'message' => 'Log directory is not writable'];
    }

    /**
     * Check queue status.
     */
    private function checkQueueStatus()
    {
        try {
            // This is a basic check - in production you might want to check actual queue workers
            $queueDriver = config('queue.default');
            return ['status' => 'configured', 'message' => "Queue driver: {$queueDriver}"];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Queue configuration error: ' . $e->getMessage()];
        }
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Get recent system logs.
     */
    private function getRecentSystemLogs()
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (!File::exists($logPath)) {
            return [];
        }

        try {
            $logs = $this->tailFile($logPath, 20);
            return array_filter(explode("\n", $logs));
        } catch (\Exception $e) {
            return ['Error reading logs: ' . $e->getMessage()];
        }
    }

    /**
     * Get backup information.
     */
    private function getBackupInformation()
    {
        $backupPath = storage_path('app/backups');
        
        if (!File::exists($backupPath)) {
            return ['backups' => [], 'total_size' => 0];
        }

        $backups = [];
        $totalSize = 0;
        
        $files = File::files($backupPath);
        
        foreach ($files as $file) {
            $size = $file->getSize();
            $totalSize += $size;
            
            $backups[] = [
                'name' => $file->getFilename(),
                'size' => $this->formatBytes($size),
                'created_at' => Carbon::createFromTimestamp($file->getMTime())->format('Y-m-d H:i:s'),
            ];
        }

        // Sort by creation time (newest first)
        usort($backups, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return [
            'backups' => array_slice($backups, 0, 10), // Show only last 10 backups
            'total_size' => $this->formatBytes($totalSize),
            'count' => count($files)
        ];
    }

    /**
     * Create database backup.
     */
    private function createDatabaseBackup($filePath)
    {
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port', 3306);

        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s --port=%s %s > %s',
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($database),
            escapeshellarg($filePath)
        );

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception('Database backup failed');
        }
    }

    /**
     * Create files backup.
     */
    private function createFilesBackup($filePath)
    {
        $zip = new \ZipArchive();
        
        if ($zip->open($filePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
            throw new \Exception('Cannot create zip file');
        }

        $basePath = base_path();
        $excludePaths = ['vendor', 'node_modules', 'storage/logs', 'storage/framework/cache'];
        
        $this->addDirectoryToZip($zip, $basePath, '', $excludePaths);
        
        $zip->close();
    }

    /**
     * Add directory to zip recursively.
     */
    private function addDirectoryToZip($zip, $basePath, $relativePath, $excludePaths)
    {
        $fullPath = $basePath . '/' . $relativePath;
        
        if (!is_dir($fullPath)) {
            return;
        }

        $files = scandir($fullPath);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $fileRelativePath = $relativePath ? $relativePath . '/' . $file : $file;
            $fileFullPath = $fullPath . '/' . $file;

            // Skip excluded paths
            foreach ($excludePaths as $excludePath) {
                if (strpos($fileRelativePath, $excludePath) === 0) {
                    continue 2;
                }
            }

            if (is_dir($fileFullPath)) {
                $zip->addEmptyDir($fileRelativePath);
                $this->addDirectoryToZip($zip, $basePath, $fileRelativePath, $excludePaths);
            } else {
                $zip->addFile($fileFullPath, $fileRelativePath);
            }
        }
    }

    /**
     * Get last N lines from a file.
     */
    private function tailFile($filePath, $lines)
    {
        $file = fopen($filePath, 'r');
        $buffer = 4096;
        $output = '';
        $chunk = '';

        fseek($file, -1, SEEK_END);
        
        if (fread($file, 1) != "\n") {
            $lines -= 1;
        }

        while (ftell($file) > 0 && $lines >= 0) {
            $seek = min(ftell($file), $buffer);
            fseek($file, -$seek, SEEK_CUR);
            $output = ($chunk = fread($file, $seek)) . $output;
            fseek($file, -mb_strlen($chunk, '8bit'), SEEK_CUR);
            $lines -= substr_count($chunk, "\n");
        }

        fclose($file);

        while ($lines++ < 0) {
            $output = substr($output, strpos($output, "\n") + 1);
        }

        return trim($output);
    }

    /**
     * Get available log files.
     */
    private function getAvailableLogFiles()
    {
        $logPath = storage_path('logs');
        $files = File::files($logPath);
        
        return array_map(function($file) {
            return $file->getFilename();
        }, $files);
    }
}