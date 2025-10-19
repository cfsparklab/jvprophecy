<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Prophecy;
use App\Models\SecurityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Log dashboard access
        $this->logSecurityEvent('admin_dashboard_access', $user->id, [
            'user_role' => $user->roles->pluck('name')->toArray()
        ]);

        // Get dashboard statistics
        $stats = $this->getDashboardStats();
        
        // Get recent activities
        $recentActivities = $this->getRecentActivities();
        
        // Get prophecy statistics by date
        $prophecyStats = $this->getProphecyStatistics();
        
        // Get security alerts
        $securityAlerts = $this->getSecurityAlerts();
        
        // Get system status
        $systemStatus = $this->getSystemStatus();

        return view('admin.dashboard', compact(
            'stats', 
            'recentActivities', 
            'prophecyStats', 
            'securityAlerts',
            'systemStatus'
        ));
    }
    
    /**
     * Get system status information.
     */
    private function getSystemStatus()
    {
        try {
            // Check database connection
            $databaseStatus = 'operational';
            try {
                DB::connection()->getPdo();
            } catch (\Exception $e) {
                $databaseStatus = 'error';
            }
            
            // Check cache (if configured)
            $cacheStatus = 'optimal';
            try {
                cache()->put('test_key', 'test_value', 10);
                cache()->get('test_key');
            } catch (\Exception $e) {
                $cacheStatus = 'error';
            }
            
            // Check storage
            $storageStatus = 'optimal';
            $storagePercentage = 0;
            try {
                $totalSpace = disk_total_space(storage_path());
                $freeSpace = disk_free_space(storage_path());
                if ($totalSpace > 0) {
                    $storagePercentage = round((($totalSpace - $freeSpace) / $totalSpace) * 100);
                    if ($storagePercentage > 90) {
                        $storageStatus = 'critical';
                    } elseif ($storagePercentage > 75) {
                        $storageStatus = 'warning';
                    }
                }
            } catch (\Exception $e) {
                $storageStatus = 'error';
                $storagePercentage = 0;
            }
            
            // Check if app is in debug mode
            $appStatus = config('app.debug') ? 'debug' : 'production';
            
            return [
                'database' => [
                    'status' => $databaseStatus,
                    'label' => ucfirst($databaseStatus)
                ],
                'cache' => [
                    'status' => $cacheStatus,
                    'label' => ucfirst($cacheStatus)
                ],
                'storage' => [
                    'status' => $storageStatus,
                    'percentage' => $storagePercentage,
                    'label' => $storagePercentage . '% Used'
                ],
                'app' => [
                    'status' => $appStatus,
                    'label' => ucfirst($appStatus) . ' Mode'
                ]
            ];
        } catch (\Exception $e) {
            return [
                'database' => ['status' => 'error', 'label' => 'Error'],
                'cache' => ['status' => 'error', 'label' => 'Error'],
                'storage' => ['status' => 'error', 'percentage' => 0, 'label' => 'Error'],
                'app' => ['status' => 'unknown', 'label' => 'Unknown']
            ];
        }
    }

    /**
     * Get dashboard statistics.
     */
    private function getDashboardStats()
    {
        return [
            'total_prophecies' => Prophecy::count(),
            'published_prophecies' => Prophecy::published()->count(),
            'draft_prophecies' => Prophecy::where('status', 'draft')->count(),
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'total_categories' => Category::count(),
            'active_categories' => Category::active()->count(),
            'total_views' => Prophecy::sum('view_count') ?? 0,
            'total_downloads' => Prophecy::sum('download_count') ?? 0,
            'downloads' => Prophecy::sum('download_count') ?? 0, // Add alias for view compatibility
            'total_prints' => Prophecy::sum('print_count') ?? 0,
            'security_events_today' => SecurityLog::whereDate('event_time', today())->count(),
            'total_security_events' => SecurityLog::count(),
            'security_events' => SecurityLog::count(), // Add alias for view compatibility
            'high_severity_events' => SecurityLog::whereIn('severity', ['high', 'critical'])->count(),
            'high_priority_events' => SecurityLog::whereIn('severity', ['high', 'critical'])->count(), // Add alias
        ];
    }

    /**
     * Get recent activities.
     */
    private function getRecentActivities()
    {
        return SecurityLog::with('user')
            ->whereIn('event_type', [
                'login_success', 
                'prophecy_view', 
                'prophecy_download', 
                'prophecy_print',
                'registration_success'
            ])
            ->orderBy('event_time', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'user_name' => $log->user->name ?? 'Guest',
                    'event_type' => $log->event_type,
                    'event_time' => $log->event_time,
                    'ip_address' => $log->ip_address,
                    'metadata' => $log->metadata,
                    'severity' => $log->severity,
                ];
            });
    }

    /**
     * Get prophecy statistics.
     */
    private function getProphecyStatistics()
    {
        // Prophecies by month for the last 12 months
        $monthlyStats = Prophecy::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Prophecies by category
        $categoryStats = Category::withCount('prophecies')
            ->orderBy('prophecies_count', 'desc')
            ->limit(10)
            ->get();

        // Most viewed prophecies
        $topViewed = Prophecy::published()
            ->orderBy('view_count', 'desc')
            ->limit(5)
            ->get(['id', 'title', 'view_count', 'download_count', 'print_count']);

        return [
            'monthly' => $monthlyStats,
            'by_category' => $categoryStats,
            'top_viewed' => $topViewed,
        ];
    }

    /**
     * Get security alerts.
     */
    private function getSecurityAlerts()
    {
        return SecurityLog::whereIn('severity', ['high', 'critical'])
            ->whereDate('event_time', '>=', now()->subDays(7))
            ->orderBy('event_time', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'event_type' => $log->event_type,
                    'severity' => $log->severity,
                    'event_time' => $log->event_time,
                    'ip_address' => $log->ip_address,
                    'user_name' => $log->user->name ?? 'Unknown',
                    'metadata' => $log->metadata,
                ];
            });
    }

    /**
     * Get dashboard data as JSON for AJAX requests.
     */
    public function getData(Request $request)
    {
        $type = $request->get('type', 'stats');

        switch ($type) {
            case 'stats':
                return response()->json($this->getDashboardStats());
            
            case 'activities':
                return response()->json($this->getRecentActivities());
            
            case 'prophecy_stats':
                return response()->json($this->getProphecyStatistics());
            
            case 'security_alerts':
                return response()->json($this->getSecurityAlerts());
            
            default:
                return response()->json(['error' => 'Invalid data type'], 400);
        }
    }

    /**
     * Log security events.
     */
    private function logSecurityEvent($eventType, $userId = null, $metadata = [], $severity = 'low')
    {
        SecurityLog::create([
            'user_id' => $userId,
            'event_type' => $eventType,
            'resource_type' => 'admin',
            'resource_id' => null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => $metadata,
            'severity' => $severity,
            'event_time' => now(),
        ]);
    }
}
