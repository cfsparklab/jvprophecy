<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = $this->getSettings();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_description' => 'nullable|string',
            'app_version' => 'required|string|max:50',
            'app_build' => 'required|string|max:50',
            'default_language' => 'required|string|in:en,ta,kn,te,ml,hi',
            'timezone' => 'required|string',
            'date_format' => 'required|string',
            'time_format' => 'required|string',
            'items_per_page' => 'required|integer|min:5|max:100',
            'enable_registration' => 'boolean',
            'enable_email_verification' => 'boolean',
            'enable_maintenance_mode' => 'boolean',
            'maintenance_message' => 'nullable|string',
            'enable_security_logging' => 'boolean',
            'enable_watermarks' => 'boolean',
            'watermark_text' => 'nullable|string|max:255',
            'max_file_size' => 'required|integer|min:1|max:10240',
            'allowed_file_types' => 'required|string',
        ]);

        $settings = [
            'app_name' => $request->app_name,
            'app_description' => $request->app_description,
            'app_version' => $request->app_version,
            'app_build' => $request->app_build,
            'default_language' => $request->default_language,
            'timezone' => $request->timezone,
            'date_format' => $request->date_format,
            'time_format' => $request->time_format,
            'items_per_page' => $request->items_per_page,
            'enable_registration' => $request->boolean('enable_registration'),
            'enable_email_verification' => $request->boolean('enable_email_verification'),
            'enable_maintenance_mode' => $request->boolean('enable_maintenance_mode'),
            'maintenance_message' => $request->maintenance_message,
            'enable_security_logging' => $request->boolean('enable_security_logging'),
            'enable_watermarks' => $request->boolean('enable_watermarks'),
            'watermark_text' => $request->watermark_text,
            'max_file_size' => $request->max_file_size,
            'allowed_file_types' => $request->allowed_file_types,
            'updated_at' => now()->toISOString(),
        ];

        // Save settings to file
        Storage::put('settings.json', json_encode($settings, JSON_PRETTY_PRINT));
        
        // Clear cache
        Cache::forget('app_settings');

        return back()->with('success', 'Settings updated successfully.');
    }

    public function backup()
    {
        // Create database backup (placeholder)
        $backupData = [
            'created_at' => now()->toISOString(),
            'version' => $this->getSettings()['app_version'] ?? '1.0.0.0',
            'build' => $this->getSettings()['app_build'] ?? '00001',
            'tables' => [
                'users_count' => \App\Models\User::count(),
                'prophecies_count' => \App\Models\Prophecy::count(),
                'categories_count' => \App\Models\Category::count(),
                'roles_count' => \App\Models\Role::count(),
            ]
        ];

        $filename = 'backup_' . now()->format('Y_m_d_H_i_s') . '.json';
        Storage::put('backups/' . $filename, json_encode($backupData, JSON_PRETTY_PRINT));

        return back()->with('success', 'Backup created successfully: ' . $filename);
    }

    public function clearCache()
    {
        Cache::flush();
        return back()->with('success', 'Cache cleared successfully.');
    }

    private function getSettings()
    {
        return Cache::remember('app_settings', 3600, function () {
            if (Storage::exists('settings.json')) {
                return json_decode(Storage::get('settings.json'), true);
            }

            // Default settings
            return [
                'app_name' => 'JV Prophecy Manager',
                'app_description' => 'Jebikalam Vaanga Prophecy',
                'app_version' => '1.0.0.0',
                'app_build' => '00004',
                'default_language' => 'en',
                'timezone' => 'Asia/Kolkata',
                'date_format' => 'd/m/Y',
                'time_format' => 'H:i:s',
                'items_per_page' => 15,
                'enable_registration' => true,
                'enable_email_verification' => true,
                'enable_maintenance_mode' => false,
                'maintenance_message' => 'System is under maintenance. Please check back later.',
                'enable_security_logging' => true,
                'enable_watermarks' => true,
                'watermark_text' => 'JV PROPHECY MANAGER',
                'max_file_size' => 2048,
                'allowed_file_types' => 'jpg,jpeg,png,gif,pdf',
            ];
        });
    }
}
