<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prophecy;
use Illuminate\Http\Request;

class ProphecyController extends Controller
{
    /**
     * Increment view count for a prophecy.
     */
    public function incrementView(Request $request, $id)
    {
        $prophecy = Prophecy::findOrFail($id);
        $prophecy->incrementViewCount();
        
        return response()->json([
            'success' => true,
            'view_count' => $prophecy->view_count
        ]);
    }
    
    /**
     * Log user activity.
     */
    public function logActivity(Request $request)
    {
        try {
            $validated = $request->validate([
                'action' => 'required|string|max:50',
                'details' => 'nullable|array',
            ]);

            // Create security log entry
            \App\Models\SecurityLog::create([
                'user_id' => auth()->id(),
                'event_type' => $validated['action'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'severity' => 'low',
                'metadata' => $validated['details'] ?? [],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Activity logged successfully'
            ]);

        } catch (\Exception $e) {
            // Log the error but don't fail the request
            \Log::error('Activity logging failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Activity logging failed'
            ], 500);
        }
    }
}
