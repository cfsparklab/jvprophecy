<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SecurityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = SecurityLog::with('user')->orderBy('created_at', 'desc');

        // Filter by severity
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        // Filter by event type
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search in description or IP address
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('user_agent', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(20);

        // Statistics
        $stats = [
            'total_events' => SecurityLog::count(),
            'today_events' => SecurityLog::whereDate('created_at', today())->count(),
            'critical_events' => SecurityLog::where('severity', 'critical')->count(),
            'high_events' => SecurityLog::where('severity', 'high')->count(),
            'failed_logins' => SecurityLog::where('event_type', 'failed_login')->whereDate('created_at', today())->count(),
            'successful_logins' => SecurityLog::where('event_type', 'successful_login')->whereDate('created_at', today())->count(),
        ];

        // Event types for filter
        $eventTypes = SecurityLog::distinct('event_type')->pluck('event_type')->filter();

        return view('admin.security-logs.index', compact('logs', 'stats', 'eventTypes'));
    }

    public function show(SecurityLog $securityLog)
    {
        $securityLog->load('user');
        return view('admin.security-logs.show', compact('securityLog'));
    }

    public function markReviewed(SecurityLog $securityLog)
    {
        $securityLog->update([
            'is_reviewed' => true,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Security log marked as reviewed.');
    }

    public function bulkMarkReviewed(Request $request)
    {
        $request->validate([
            'log_ids' => 'required|array',
            'log_ids.*' => 'exists:security_logs,id',
        ]);

        SecurityLog::whereIn('id', $request->log_ids)->update([
            'is_reviewed' => true,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Selected security logs marked as reviewed.');
    }

    public function destroy(SecurityLog $securityLog)
    {
        $securityLog->delete();
        return back()->with('success', 'Security log deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'log_ids' => 'required|array',
            'log_ids.*' => 'exists:security_logs,id',
        ]);

        SecurityLog::whereIn('id', $request->log_ids)->delete();

        return back()->with('success', 'Selected security logs deleted successfully.');
    }

    public function export(Request $request)
    {
        $query = SecurityLog::with('user')->orderBy('created_at', 'desc');

        // Apply same filters as index
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->get();

        $filename = 'security_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'Event Type',
                'Severity',
                'Description',
                'User',
                'IP Address',
                'User Agent',
                'Created At',
                'Reviewed',
                'Reviewed By',
                'Reviewed At'
            ]);

            // CSV data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->event_type,
                    $log->severity,
                    $log->description,
                    $log->user ? $log->user->name : 'System',
                    $log->ip_address,
                    $log->user_agent,
                    $log->created_at->format('d/m/Y H:i:s'),
                    $log->is_reviewed ? 'Yes' : 'No',
                    $log->reviewedBy ? $log->reviewedBy->name : '',
                    $log->reviewed_at ? $log->reviewed_at->format('d/m/Y H:i:s') : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
