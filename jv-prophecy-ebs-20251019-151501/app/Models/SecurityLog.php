<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SecurityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_type',
        'resource_type',
        'resource_id',
        'ip_address',
        'user_agent',
        'metadata',
        'severity',
        'event_time',
    ];

    protected $casts = [
        'metadata' => 'array',
        'event_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that triggered this security event.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who reviewed this log.
     */
    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scope for unreviewed logs.
     */
    public function scopeUnreviewed($query)
    {
        return $query->where('is_reviewed', false);
    }

    /**
     * Scope for critical severity logs.
     */
    public function scopeCritical($query)
    {
        return $query->where('severity', 'critical');
    }

    /**
     * Scope for high severity logs.
     */
    public function scopeHigh($query)
    {
        return $query->where('severity', 'high');
    }

    /**
     * Scope for today's logs.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Get severity badge class.
     */
    public function getSeverityBadgeAttribute()
    {
        return match($this->severity) {
            'critical' => 'intel-badge-error',
            'high' => 'intel-badge-warning',
            'medium' => 'intel-badge-info',
            'low' => 'intel-badge-gray',
            default => 'intel-badge-gray',
        };
    }

    /**
     * Get event type icon.
     */
    public function getEventIconAttribute()
    {
        return match($this->event_type) {
            'login' => 'fas fa-sign-in-alt',
            'logout' => 'fas fa-sign-out-alt',
            'failed_login' => 'fas fa-times-circle',
            'password_change' => 'fas fa-key',
            'profile_update' => 'fas fa-user-edit',
            'permission_denied' => 'fas fa-ban',
            'suspicious_activity' => 'fas fa-exclamation-triangle',
            'data_access' => 'fas fa-database',
            'file_upload' => 'fas fa-upload',
            'file_download' => 'fas fa-download',
            'system_error' => 'fas fa-bug',
            default => 'fas fa-info-circle',
        };
    }

    /**
     * Create a security log entry.
     */
    public static function logEvent($eventType, $severity, $description, $userId = null, $additionalData = [])
    {
        return self::create([
            'user_id' => $userId ?: auth()->id(),
            'event_type' => $eventType,
            'severity' => $severity,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'additional_data' => $additionalData,
        ]);
    }

    /**
     * Log a login event.
     */
    public static function logLogin($userId, $successful = true)
    {
        $eventType = $successful ? 'login' : 'failed_login';
        $severity = $successful ? 'low' : 'medium';
        $description = $successful ? 'User logged in successfully' : 'Failed login attempt';

        return self::logEvent($eventType, $severity, $description, $userId);
    }

    /**
     * Log a logout event.
     */
    public static function logLogout($userId)
    {
        return self::logEvent('logout', 'low', 'User logged out', $userId);
    }

    /**
     * Log a permission denied event.
     */
    public static function logPermissionDenied($userId, $action)
    {
        return self::logEvent(
            'permission_denied', 
            'medium', 
            "Permission denied for action: {$action}", 
            $userId,
            ['action' => $action]
        );
    }

    /**
     * Log suspicious activity.
     */
    public static function logSuspiciousActivity($description, $userId = null, $additionalData = [])
    {
        return self::logEvent('suspicious_activity', 'high', $description, $userId, $additionalData);
    }
}