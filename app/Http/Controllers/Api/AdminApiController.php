<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prophecy;
use App\Models\User;
use App\Models\SecurityLog;
use App\Models\ProphecyTranslation;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AdminApiController extends Controller
{
    /**
     * Get dashboard statistics.
     */
    public function getDashboardStats(Request $request)
    {
        try {
            $cacheKey = 'admin_dashboard_stats';
            $cacheDuration = 300; // 5 minutes

            $stats = Cache::remember($cacheKey, $cacheDuration, function () {
                return [
                    'users' => [
                        'total' => User::count(),
                        'active' => User::where('status', 'active')->count(),
                        'inactive' => User::where('status', 'inactive')->count(),
                        'suspended' => User::where('status', 'suspended')->count(),
                        'new_today' => User::whereDate('created_at', Carbon::today())->count(),
                        'new_this_week' => User::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
                        'new_this_month' => User::whereMonth('created_at', Carbon::now()->month)->count(),
                    ],
                    'prophecies' => [
                        'total' => Prophecy::count(),
                        'published' => Prophecy::where('status', 'published')->count(),
                        'draft' => Prophecy::where('status', 'draft')->count(),
                        'archived' => Prophecy::where('status', 'archived')->count(),
                        'public' => Prophecy::where('visibility', 'public')->count(),
                        'private' => Prophecy::where('visibility', 'private')->count(),
                        'restricted' => Prophecy::where('visibility', 'restricted')->count(),
                        'created_today' => Prophecy::whereDate('created_at', Carbon::today())->count(),
                        'created_this_week' => Prophecy::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
                        'created_this_month' => Prophecy::whereMonth('created_at', Carbon::now()->month)->count(),
                    ],
                    'translations' => [
                        'total' => ProphecyTranslation::count(),
                        'by_language' => ProphecyTranslation::select('language', DB::raw('count(*) as count'))
                            ->groupBy('language')
                            ->get()
                            ->pluck('count', 'language'),
                        'completion_rate' => $this->getTranslationCompletionRate(),
                    ],
                    'categories' => [
                        'total' => Category::count(),
                        'active' => Category::where('status', 'active')->count(),
                        'inactive' => Category::where('status', 'inactive')->count(),
                        'with_prophecies' => Category::has('prophecies')->count(),
                    ],
                    'activities' => [
                        'total_views' => SecurityLog::where('event_type', 'prophecy_view')->count(),
                        'total_downloads' => SecurityLog::where('event_type', 'prophecy_download')->count(),
                        'total_prints' => SecurityLog::where('event_type', 'prophecy_print')->count(),
                        'total_logins' => SecurityLog::where('event_type', 'user_login')->count(),
                        'views_today' => SecurityLog::where('event_type', 'prophecy_view')->whereDate('created_at', Carbon::today())->count(),
                        'downloads_today' => SecurityLog::where('event_type', 'prophecy_download')->whereDate('created_at', Carbon::today())->count(),
                        'prints_today' => SecurityLog::where('event_type', 'prophecy_print')->whereDate('created_at', Carbon::today())->count(),
                        'logins_today' => SecurityLog::where('event_type', 'user_login')->whereDate('created_at', Carbon::today())->count(),
                    ],
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $stats,
                'timestamp' => Carbon::now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dashboard stats: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get system status information.
     */
    public function getSystemStatus(Request $request)
    {
        try {
            $status = [
                'database' => $this->checkDatabaseStatus(),
                'cache' => $this->checkCacheStatus(),
                'storage' => $this->checkStorageStatus(),
                'queue' => $this->checkQueueStatus(),
                'memory' => $this->getMemoryUsage(),
                'disk_space' => $this->getDiskSpaceInfo(),
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'environment' => config('app.env'),
                'debug_mode' => config('app.debug'),
                'timezone' => config('app.timezone'),
                'uptime' => $this->getSystemUptime(),
            ];

            return response()->json([
                'success' => true,
                'data' => $status,
                'timestamp' => Carbon::now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch system status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user activity timeline.
     */
    public function getUserActivityTimeline(Request $request)
    {
        try {
            $days = $request->get('days', 7);
            $limit = $request->get('limit', 50);

            $activities = SecurityLog::with('user')
                ->where('created_at', '>=', Carbon::now()->subDays($days))
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($activity) {
                    return [
                        'id' => $activity->id,
                        'event_type' => $activity->event_type,
                        'description' => $activity->description,
                        'user' => $activity->user ? [
                            'id' => $activity->user->id,
                            'name' => $activity->user->name,
                            'email' => $activity->user->email,
                        ] : null,
                        'ip_address' => $activity->ip_address,
                        'user_agent' => $activity->user_agent,
                        'severity' => $activity->severity,
                        'metadata' => $activity->metadata,
                        'created_at' => $activity->created_at->toISOString(),
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $activities,
                'meta' => [
                    'days' => $days,
                    'limit' => $limit,
                    'total_count' => $activities->count(),
                ],
                'timestamp' => Carbon::now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user activity: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get prophecy statistics.
     */
    public function getProphecyStats(Request $request)
    {
        try {
            $period = $request->get('period', 'month'); // day, week, month, year
            $groupBy = $this->getGroupByFormat($period);

            $stats = [
                'creation_trend' => Prophecy::select(
                        DB::raw($groupBy . ' as period'),
                        DB::raw('count(*) as count')
                    )
                    ->where('created_at', '>=', $this->getPeriodStartDate($period))
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get(),
                'status_distribution' => Prophecy::select('status', DB::raw('count(*) as count'))
                    ->groupBy('status')
                    ->get(),
                'visibility_distribution' => Prophecy::select('visibility', DB::raw('count(*) as count'))
                    ->groupBy('visibility')
                    ->get(),
                'category_distribution' => Prophecy::select('categories.name as category', DB::raw('count(*) as count'))
                    ->join('categories', 'prophecies.category_id', '=', 'categories.id')
                    ->groupBy('categories.name')
                    ->orderBy('count', 'desc')
                    ->limit(10)
                    ->get(),
                'most_viewed' => $this->getMostViewedProphecies(10),
                'most_downloaded' => $this->getMostDownloadedProphecies(10),
                'recent_prophecies' => Prophecy::with(['category', 'creator'])
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get()
                    ->map(function ($prophecy) {
                        return [
                            'id' => $prophecy->id,
                            'title' => $prophecy->title,
                            'status' => $prophecy->status,
                            'visibility' => $prophecy->visibility,
                            'category' => $prophecy->category?->name,
                            'creator' => $prophecy->creator?->name,
                            'created_at' => $prophecy->created_at->toISOString(),
                        ];
                    }),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'meta' => [
                    'period' => $period,
                ],
                'timestamp' => Carbon::now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch prophecy stats: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Global search across all entities.
     */
    public function globalSearch(Request $request)
    {
        try {
            $query = $request->get('q', '');
            $limit = $request->get('limit', 20);

            if (strlen($query) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search query must be at least 2 characters long'
                ], 400);
            }

            $results = [
                'prophecies' => Prophecy::where('title', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    ->with(['category', 'creator'])
                    ->limit($limit)
                    ->get()
                    ->map(function ($prophecy) {
                        return [
                            'id' => $prophecy->id,
                            'title' => $prophecy->title,
                            'type' => 'prophecy',
                            'status' => $prophecy->status,
                            'category' => $prophecy->category?->name,
                            'creator' => $prophecy->creator?->name,
                            'url' => route('admin.prophecies.show', $prophecy),
                        ];
                    }),
                'users' => User::where('name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%")
                    ->limit($limit)
                    ->get()
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'title' => $user->name,
                            'subtitle' => $user->email,
                            'type' => 'user',
                            'status' => $user->status,
                            'url' => route('admin.users.show', $user),
                        ];
                    }),
                'categories' => Category::where('name', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    ->limit($limit)
                    ->get()
                    ->map(function ($category) {
                        return [
                            'id' => $category->id,
                            'title' => $category->name,
                            'subtitle' => $category->description,
                            'type' => 'category',
                            'status' => $category->status,
                            'url' => route('admin.categories.show', $category),
                        ];
                    }),
                'translations' => ProphecyTranslation::where('title', 'LIKE', "%{$query}%")
                    ->orWhere('content', 'LIKE', "%{$query}%")
                    ->with('prophecy')
                    ->limit($limit)
                    ->get()
                    ->map(function ($translation) {
                        return [
                            'id' => $translation->id,
                            'title' => $translation->title,
                            'subtitle' => "Translation ({$translation->language})",
                            'type' => 'translation',
                            'language' => $translation->language,
                            'prophecy_title' => $translation->prophecy?->title,
                            'url' => route('admin.prophecies.translations', $translation->prophecy),
                        ];
                    }),
            ];

            $totalResults = collect($results)->sum(function ($items) {
                return $items->count();
            });

            return response()->json([
                'success' => true,
                'data' => $results,
                'meta' => [
                    'query' => $query,
                    'total_results' => $totalResults,
                    'limit' => $limit,
                ],
                'timestamp' => Carbon::now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get translation completion rate.
     */
    private function getTranslationCompletionRate()
    {
        $totalProphecies = Prophecy::count();
        $languages = ['en', 'ta', 'kn', 'te', 'ml', 'hi'];
        $expectedTranslations = $totalProphecies * count($languages);
        $actualTranslations = ProphecyTranslation::count();
        
        return $expectedTranslations > 0 ? round(($actualTranslations / $expectedTranslations) * 100, 2) : 0;
    }

    /**
     * Check database status.
     */
    private function checkDatabaseStatus()
    {
        try {
            DB::connection()->getPdo();
            $version = DB::select('SELECT VERSION() as version')[0]->version ?? 'Unknown';
            return [
                'status' => 'connected',
                'version' => $version,
                'connection' => config('database.default'),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
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
            
            return [
                'status' => $value === 'test' ? 'working' : 'error',
                'driver' => config('cache.default'),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check storage status.
     */
    private function checkStorageStatus()
    {
        $storagePath = storage_path();
        return [
            'status' => is_writable($storagePath) ? 'writable' : 'error',
            'path' => $storagePath,
            'permissions' => substr(sprintf('%o', fileperms($storagePath)), -4),
        ];
    }

    /**
     * Check queue status.
     */
    private function checkQueueStatus()
    {
        return [
            'status' => 'configured',
            'driver' => config('queue.default'),
            'connection' => config('queue.connections.' . config('queue.default')),
        ];
    }

    /**
     * Get memory usage information.
     */
    private function getMemoryUsage()
    {
        return [
            'current' => $this->formatBytes(memory_get_usage(true)),
            'peak' => $this->formatBytes(memory_get_peak_usage(true)),
            'limit' => ini_get('memory_limit'),
            'current_bytes' => memory_get_usage(true),
            'peak_bytes' => memory_get_peak_usage(true),
        ];
    }

    /**
     * Get disk space information.
     */
    private function getDiskSpaceInfo()
    {
        $path = storage_path();
        $totalBytes = disk_total_space($path);
        $freeBytes = disk_free_space($path);
        $usedBytes = $totalBytes - $freeBytes;

        return [
            'total' => $this->formatBytes($totalBytes),
            'free' => $this->formatBytes($freeBytes),
            'used' => $this->formatBytes($usedBytes),
            'usage_percent' => round(($usedBytes / $totalBytes) * 100, 2),
            'total_bytes' => $totalBytes,
            'free_bytes' => $freeBytes,
            'used_bytes' => $usedBytes,
        ];
    }

    /**
     * Get system uptime (approximation based on Laravel start time).
     */
    private function getSystemUptime()
    {
        // This is an approximation - in a real system you might track this differently
        $startTime = defined('LARAVEL_START') ? LARAVEL_START : microtime(true);
        $uptime = microtime(true) - $startTime;
        
        return [
            'seconds' => round($uptime, 2),
            'formatted' => $this->formatUptime($uptime),
        ];
    }

    /**
     * Get most viewed prophecies.
     */
    private function getMostViewedProphecies($limit = 5)
    {
        return SecurityLog::select('prophecies.title', 'prophecies.id', DB::raw('count(*) as views'))
            ->join('prophecies', function($join) {
                $join->whereRaw('JSON_EXTRACT(security_logs.metadata, "$.prophecy_id") = prophecies.id');
            })
            ->where('event_type', 'prophecy_view')
            ->groupBy('prophecies.id', 'prophecies.title')
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get most downloaded prophecies.
     */
    private function getMostDownloadedProphecies($limit = 5)
    {
        return SecurityLog::select('prophecies.title', 'prophecies.id', DB::raw('count(*) as downloads'))
            ->join('prophecies', function($join) {
                $join->whereRaw('JSON_EXTRACT(security_logs.metadata, "$.prophecy_id") = prophecies.id');
            })
            ->where('event_type', 'prophecy_download')
            ->groupBy('prophecies.id', 'prophecies.title')
            ->orderBy('downloads', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get group by format for different periods.
     */
    private function getGroupByFormat($period)
    {
        switch ($period) {
            case 'day':
                return "DATE_FORMAT(created_at, '%Y-%m-%d')";
            case 'week':
                return "DATE_FORMAT(created_at, '%Y-%u')";
            case 'month':
                return "DATE_FORMAT(created_at, '%Y-%m')";
            case 'year':
                return "DATE_FORMAT(created_at, '%Y')";
            default:
                return "DATE_FORMAT(created_at, '%Y-%m')";
        }
    }

    /**
     * Get period start date.
     */
    private function getPeriodStartDate($period)
    {
        switch ($period) {
            case 'day':
                return Carbon::now()->subDays(30);
            case 'week':
                return Carbon::now()->subWeeks(12);
            case 'month':
                return Carbon::now()->subMonths(12);
            case 'year':
                return Carbon::now()->subYears(5);
            default:
                return Carbon::now()->subMonths(12);
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
     * Format uptime to human readable format.
     */
    private function formatUptime($seconds)
    {
        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = floor($seconds % 60);

        $parts = [];
        if ($days > 0) $parts[] = "{$days}d";
        if ($hours > 0) $parts[] = "{$hours}h";
        if ($minutes > 0) $parts[] = "{$minutes}m";
        if ($seconds > 0) $parts[] = "{$seconds}s";

        return implode(' ', $parts) ?: '0s';
    }
}