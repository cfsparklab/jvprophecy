<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use App\Notifications\EmailVerificationNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'status',
        'preferred_language',
        'google_id',
        'avatar',
        'email_verified_at',
        'email_verification_token',
        'email_verification_code',
        'verification_code_expires_at',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verification_token',
        'email_verification_code',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'mobile_verified_at' => 'datetime',
            'verification_code_expires_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the roles for the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * Get the prophecies created by the user.
     */
    public function prophecies()
    {
        return $this->hasMany(Prophecy::class, 'created_by');
    }

    /**
     * Get the security logs for the user.
     */
    public function securityLogs()
    {
        return $this->hasMany(SecurityLog::class);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole($roles)
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission($permission)
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission);
        })->exists();
    }

    /**
     * Generate email verification token and code
     */
    public function generateEmailVerification()
    {
        $this->email_verification_token = Str::random(60);
        $this->email_verification_code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->verification_code_expires_at = null; // No expiration
        $this->save();
    }

    /**
     * Check if email verification code is valid
     */
    public function isValidVerificationCode($code)
    {
        return $this->email_verification_code === $code;
    }

    /**
     * Mark email as verified
     */
    public function markEmailAsVerified()
    {
        $this->email_verified_at = now();
        $this->email_verification_token = null;
        $this->email_verification_code = null;
        $this->verification_code_expires_at = null;
        $this->is_active = true;
        $this->save();
    }

    /**
     * Check if user is verified and active
     */
    public function isVerifiedAndActive()
    {
        return $this->hasVerifiedEmail() && $this->is_active;
    }

    /**
     * Resend verification code
     */
    public function resendVerificationCode()
    {
        $this->email_verification_code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->verification_code_expires_at = null; // No expiration
        $this->save();
    }

    /**
     * Send the email verification notification using our custom notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new EmailVerificationNotification($this));
    }
}
