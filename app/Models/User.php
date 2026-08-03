<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Account Status Constants
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_DEACTIVATED = 'deactivated';
    public const STATUS_REJECTED = 'rejected';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'business_name',
        'nic',
        'phone',
        'email',
        'address',
        'district',
        'province',
        'password',
        'status',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user account status is approved or active.
     */
    public function isApproved(): bool
    {
        return in_array($this->status, [self::STATUS_APPROVED, self::STATUS_ACTIVE], true);
    }

    /**
     * Check if user account status is active.
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE || $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if user account status is deactivated.
     */
    public function isDeactivated(): bool
    {
        return $this->status === self::STATUS_DEACTIVATED;
    }

    /**
     * Check if user account status is pending.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if user account status is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Get full name attribute.
     */
    public function getFullNameAttribute(): string
    {
        if ($this->first_name && $this->last_name) {
            return "{$this->first_name} {$this->last_name}";
        }

        return $this->name ?? 'User';
    }

    /**
     * Accessor for profile photo URL.
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo_path && Storage::disk('public')->exists($this->profile_photo_path)) {
            return Storage::url($this->profile_photo_path);
        }

        // Return ui-avatars placeholder fallback
        $name = urlencode($this->full_name);
        return "https://ui-avatars.com/api/?name={$name}&color=10b981&background=020617";
    }

    /**
     * Send custom queued email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new \App\Notifications\CustomVerifyEmailNotification());
    }

    /**
     * Send custom queued password reset notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\CustomResetPasswordNotification($token));
    }
}
