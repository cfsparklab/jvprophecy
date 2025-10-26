<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prophecy;
use App\Models\SecurityLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Show analytics page.
     */
    public function index()
    {
        $analytics = $this->getAnalytics();
        return view('admin.analytics', compact('analytics'));
    }

    /**
     * Optional CSV export for specific dataset (fallback to client-side exports).
     */
    public function export()
    {
        $type = request('type');
        $filename = 'analytics-export-' . ($type ?: 'data') . '-' . now()->format('Ymd_His') . '.csv';

        $rows = [];
        switch ($type) {
            case 'windows':
                $win = $this->getAnalytics()['windows'];
                $rows[] = ['Window', 'Logins', 'Downloads', 'Views', 'Prints'];
                foreach ($win as $w) {
                    $rows[] = [$w['label'], $w['logins'], $w['downloads'], $w['views'], $w['prints']];
                }
                break;
            case 'logins_by_user':
                $rows[] = ['User ID', 'Name', 'Email', 'Logins'];
                foreach ($this->aggregatePerUser(['login_success', 'login'], 1000) as $r) {
                    $rows[] = [$r['user_id'], $r['name'], $r['email'], $r['total']];
                }
                break;
            case 'downloads_by_user':
                $rows[] = ['User ID', 'Name', 'Email', 'Downloads'];
                foreach ($this->aggregatePerUser(['prophecy_pdf_download', 'prophecy_download'], 1000) as $r) {
                    $rows[] = [$r['user_id'], $r['name'], $r['email'], $r['total']];
                }
                break;
            case 'views_by_user':
                $rows[] = ['User ID', 'Name', 'Email', 'Views'];
                foreach ($this->aggregatePerUser(['prophecy_view'], 1000) as $r) {
                    $rows[] = [$r['user_id'], $r['name'], $r['email'], $r['total']];
                }
                break;
            case 'top_downloads':
                $rows[] = ['Prophecy ID', 'Title', 'Downloads', 'View Count', 'Download Count', 'Print Count'];
                foreach ($this->topPropheciesByEvents(['prophecy_pdf_download', 'prophecy_download'], 1000) as $r) {
                    $rows[] = [$r['prophecy_id'], $r['title'], $r['total'], $r['view_count'], $r['download_count'], $r['print_count']];
                }
                break;
            case 'top_views':
                $rows[] = ['Prophecy ID', 'Title', 'Views', 'View Count', 'Download Count', 'Print Count'];
                foreach ($this->topPropheciesByEvents(['prophecy_view'], 1000) as $r) {
                    $rows[] = [$r['prophecy_id'], $r['title'], $r['total'], $r['view_count'], $r['download_count'], $r['print_count']];
                }
                break;
            case 'top_prints':
                $rows[] = ['Prophecy ID', 'Title', 'Prints', 'View Count', 'Download Count', 'Print Count'];
                foreach ($this->topPropheciesByEvents(['prophecy_print'], 1000) as $r) {
                    $rows[] = [$r['prophecy_id'], $r['title'], $r['total'], $r['view_count'], $r['download_count'], $r['print_count']];
                }
                break;
            default:
                $rows[] = ['Unsupported type'];
        }

        $handle = fopen('php://temp', 'r+');
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=' . $filename,
        ]);
    }

    private function getAnalytics()
    {
        $windows = [
            ['key' => 'today', 'label' => 'Today', 'start' => now()->startOfDay()],
            ['key' => '24h', 'label' => 'Last 24h', 'start' => now()->copy()->subHours(24)],
            ['key' => '48h', 'label' => 'Last 48h', 'start' => now()->copy()->subHours(48)],
            ['key' => '72h', 'label' => 'Last 72h', 'start' => now()->copy()->subHours(72)],
            ['key' => '7d', 'label' => 'Last 7 Days', 'start' => now()->copy()->subDays(7)],
            ['key' => '15d', 'label' => 'Last 15 Days', 'start' => now()->copy()->subDays(15)],
            ['key' => '30d', 'label' => 'Last 30 Days', 'start' => now()->copy()->subDays(30)],
        ];

        $eventSets = [
            'logins' => ['login_success', 'login'],
            'downloads' => ['prophecy_pdf_download', 'prophecy_download'],
            'views' => ['prophecy_view'],
            'prints' => ['prophecy_print'],
        ];

        $byWindow = [];
        foreach ($windows as $w) {
            $row = ['label' => $w['label']];
            foreach ($eventSets as $key => $types) {
                $row[$key] = SecurityLog::whereIn('event_type', $types)
                    ->where('event_time', '>=', $w['start'])
                    ->count();
            }
            $byWindow[$w['key']] = $row;
        }

        $totalUsers = User::count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->where('is_active', true)->count();
        $nonVerifiedUsers = max(0, $totalUsers - $verifiedUsers);

        $perUser = [
            'logins' => $this->aggregatePerUser(['login_success', 'login'], 50),
            'downloads' => $this->aggregatePerUser(['prophecy_pdf_download', 'prophecy_download'], 50),
            'views' => $this->aggregatePerUser(['prophecy_view'], 50),
        ];

        $top = [
            'downloads' => $this->topPropheciesByEvents(['prophecy_pdf_download', 'prophecy_download'], 5),
            'views' => $this->topPropheciesByEvents(['prophecy_view'], 5),
            'prints' => $this->topPropheciesByEvents(['prophecy_print'], 5),
        ];

        $totalViews = SecurityLog::where('event_type', 'prophecy_view')->count();
        $uniqueKeys = SecurityLog::where('event_type', 'prophecy_view')
            ->get(['user_id', 'ip_address', 'user_agent'])
            ->map(function ($log) {
                if (!empty($log->user_id)) {
                    return 'u:' . $log->user_id;
                }
                return 'g:' . ($log->ip_address ?: '0.0.0.0') . '|' . substr((string)$log->user_agent, 0, 120);
            })
            ->unique()
            ->count();

        return [
            'users' => [
                'total' => $totalUsers,
                'verified' => $verifiedUsers,
                'non_verified' => $nonVerifiedUsers,
            ],
            'windows' => $byWindow,
            'per_user' => $perUser,
            'top_prophecies' => $top,
            'views' => [
                'total' => $totalViews,
                'unique' => $uniqueKeys,
            ],
        ];
    }

    private function aggregatePerUser(array $eventTypes, int $limit = 50)
    {
        return SecurityLog::select('user_id', DB::raw('COUNT(*) as total'))
            ->whereIn('event_type', $eventTypes)
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit($limit)
            ->get()
            ->map(function ($row) {
                $user = User::find($row->user_id);
                return [
                    'user_id' => $row->user_id,
                    'name' => $user?->name ?? 'Unknown',
                    'email' => $user?->email ?? null,
                    'total' => (int) $row->total,
                ];
            });
    }

    private function topPropheciesByEvents(array $eventTypes, int $limit = 5)
    {
        $rows = SecurityLog::select('resource_id', DB::raw('COUNT(*) as total'))
            ->whereIn('event_type', $eventTypes)
            ->where('resource_type', 'prophecy')
            ->whereNotNull('resource_id')
            ->groupBy('resource_id')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();

        $prophecyIds = $rows->pluck('resource_id')->all();
        $prophecies = Prophecy::whereIn('id', $prophecyIds)->get(['id', 'title', 'view_count', 'download_count', 'print_count']);

        return $rows->map(function ($row) use ($prophecies) {
            $p = $prophecies->firstWhere('id', $row->resource_id);
            return [
                'prophecy_id' => $row->resource_id,
                'title' => $p?->title ?? ('#' . $row->resource_id),
                'total' => (int) $row->total,
                'view_count' => $p?->view_count ?? 0,
                'download_count' => $p?->download_count ?? 0,
                'print_count' => $p?->print_count ?? 0,
            ];
        });
    }
}