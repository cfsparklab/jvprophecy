<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prophecy;
use App\Models\User;
use App\Models\SecurityLog;
use App\Models\ProphecyTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard.
     */
    public function index()
    {
        // Get date range for analytics (last 30 days by default)
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        // User Analytics
        $userStats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'new_users_this_month' => User::where('created_at', '>=', Carbon::now()->startOfMonth())->count(),
            'users_by_role' => User::select('roles.name as role', DB::raw('count(*) as count'))
                ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
                ->join('roles', 'user_roles.role_id', '=', 'roles.id')
                ->groupBy('roles.name')
                ->get(),
        ];

        // Prophecy Analytics
        $prophecyStats = [
            'total_prophecies' => Prophecy::count(),
            'published_prophecies' => Prophecy::where('status', 'published')->count(),
            'draft_prophecies' => Prophecy::where('status', 'draft')->count(),
            'prophecies_by_category' => Prophecy::select('categories.name as category', DB::raw('count(*) as count'))
                ->join('categories', 'prophecies.category_id', '=', 'categories.id')
                ->groupBy('categories.name')
                ->get(),
            'prophecies_by_month' => Prophecy::select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('count(*) as count')
                )
                ->where('created_at', '>=', $startDate)
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get(),
        ];

        // Translation Analytics
        $translationStats = [
            'total_translations' => ProphecyTranslation::count(),
            'translations_by_language' => ProphecyTranslation::select('language', DB::raw('count(*) as count'))
                ->groupBy('language')
                ->get(),
            'completion_rate' => $this->getTranslationCompletionRate(),
        ];

        // Activity Analytics
        $activityStats = [
            'total_views' => SecurityLog::where('event_type', 'prophecy_view')->count(),
            'total_downloads' => SecurityLog::where('event_type', 'prophecy_download')->count(),
            'total_prints' => SecurityLog::where('event_type', 'prophecy_print')->count(),
            'recent_activities' => SecurityLog::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
            'activity_by_day' => SecurityLog::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('count(*) as count')
                )
                ->where('created_at', '>=', $startDate)
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->get(),
        ];

        // Performance Metrics
        $performanceStats = [
            'avg_prophecy_views' => round(SecurityLog::where('event_type', 'prophecy_view')->count() / max(Prophecy::count(), 1), 2),
            'most_viewed_prophecies' => $this->getMostViewedProphecies(),
            'most_downloaded_prophecies' => $this->getMostDownloadedProphecies(),
            'popular_languages' => $this->getPopularLanguages(),
        ];

        return view('admin.analytics.index', compact(
            'userStats',
            'prophecyStats', 
            'translationStats',
            'activityStats',
            'performanceStats',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export analytics data.
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $type = $request->get('type', 'summary');

        switch ($type) {
            case 'users':
                return $this->exportUsers($format);
            case 'prophecies':
                return $this->exportProphecies($format);
            case 'activities':
                return $this->exportActivities($format);
            default:
                return $this->exportSummary($format);
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
     * Get most viewed prophecies.
     */
    private function getMostViewedProphecies()
    {
        return SecurityLog::select('prophecies.title', DB::raw('count(*) as views'))
            ->join('prophecies', 'security_logs.metadata->prophecy_id', '=', 'prophecies.id')
            ->where('event_type', 'prophecy_view')
            ->groupBy('prophecies.id', 'prophecies.title')
            ->orderBy('views', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Get most downloaded prophecies.
     */
    private function getMostDownloadedProphecies()
    {
        return SecurityLog::select('prophecies.title', DB::raw('count(*) as downloads'))
            ->join('prophecies', 'security_logs.metadata->prophecy_id', '=', 'prophecies.id')
            ->where('event_type', 'prophecy_download')
            ->groupBy('prophecies.id', 'prophecies.title')
            ->orderBy('downloads', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Get popular languages.
     */
    private function getPopularLanguages()
    {
        return SecurityLog::select('metadata->language as language', DB::raw('count(*) as usage'))
            ->whereIn('event_type', ['prophecy_view', 'prophecy_download'])
            ->whereNotNull('metadata->language')
            ->groupBy('language')
            ->orderBy('usage', 'desc')
            ->get();
    }

    /**
     * Export users data.
     */
    private function exportUsers($format)
    {
        $users = User::with('roles')->get();
        
        if ($format === 'json') {
            return response()->json($users);
        }

        // CSV export
        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Mobile', 'Status', 'Roles', 'Created At']);
            
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->mobile,
                    $user->status,
                    $user->roles->pluck('name')->implode(', '),
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export prophecies data.
     */
    private function exportProphecies($format)
    {
        $prophecies = Prophecy::with(['category', 'creator', 'translations'])->get();
        
        if ($format === 'json') {
            return response()->json($prophecies);
        }

        // CSV export
        $filename = 'prophecies_export_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($prophecies) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Title', 'Category', 'Status', 'Visibility', 'Jebikalam Vaanga Date', 'Translations', 'Created At']);
            
            foreach ($prophecies as $prophecy) {
                fputcsv($file, [
                    $prophecy->id,
                    $prophecy->title,
                    $prophecy->category?->name,
                    $prophecy->status,
                    $prophecy->visibility,
                    $prophecy->jebikalam_vanga_date?->format('d/m/Y'),
                    $prophecy->translations->count(),
                    $prophecy->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export activities data.
     */
    private function exportActivities($format)
    {
        $activities = SecurityLog::with('user')->orderBy('created_at', 'desc')->limit(1000)->get();
        
        if ($format === 'json') {
            return response()->json($activities);
        }

        // CSV export
        $filename = 'activities_export_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($activities) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Event Type', 'User', 'IP Address', 'Severity', 'Description', 'Created At']);
            
            foreach ($activities as $activity) {
                fputcsv($file, [
                    $activity->id,
                    $activity->event_type,
                    $activity->user?->name ?? 'Guest',
                    $activity->ip_address,
                    $activity->severity,
                    $activity->description,
                    $activity->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export summary data.
     */
    private function exportSummary($format)
    {
        $summary = [
            'users' => [
                'total' => User::count(),
                'active' => User::where('status', 'active')->count(),
                'new_this_month' => User::where('created_at', '>=', Carbon::now()->startOfMonth())->count(),
            ],
            'prophecies' => [
                'total' => Prophecy::count(),
                'published' => Prophecy::where('status', 'published')->count(),
                'draft' => Prophecy::where('status', 'draft')->count(),
            ],
            'translations' => [
                'total' => ProphecyTranslation::count(),
                'completion_rate' => $this->getTranslationCompletionRate(),
            ],
            'activities' => [
                'total_views' => SecurityLog::where('event_type', 'prophecy_view')->count(),
                'total_downloads' => SecurityLog::where('event_type', 'prophecy_download')->count(),
                'total_prints' => SecurityLog::where('event_type', 'prophecy_print')->count(),
            ],
        ];

        if ($format === 'json') {
            return response()->json($summary);
        }

        // CSV export for summary
        $filename = 'analytics_summary_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($summary) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Category', 'Metric', 'Value']);
            
            foreach ($summary as $category => $metrics) {
                foreach ($metrics as $metric => $value) {
                    fputcsv($file, [ucfirst($category), ucfirst(str_replace('_', ' ', $metric)), $value]);
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}