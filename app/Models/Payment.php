<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Payment extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'payment_number',
        'client_id',
        'amount',
        'payment_date',
        'bank_name',
        'reference_number',
        'payment_screenshot',
        'remarks',
        'status',
        'reviewed_by',
        'rejection_reason',
        'reviewed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Client relationship.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Reviewer admin relationship.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Accessor for full screenshot URL.
     */
    public function getReceiptUrlAttribute(): string
    {
        if (! $this->payment_screenshot) {
            return asset('images/placeholder-receipt.png');
        }

        return Storage::url($this->payment_screenshot);
    }

    /**
     * Helper to check status.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Auto generate unique payment sequence number.
     */
    public static function generatePaymentNumber(): string
    {
        $prefix = 'PAY-' . date('Ymd') . '-';
        $latest = static::where('payment_number', 'like', $prefix . '%')
            ->latest('id')
            ->first();

        if (! $latest) {
            return $prefix . '0001';
        }

        $sequence = (int) substr($latest->payment_number, -4);
        return $prefix . str_pad($sequence + 1, 4, '0', STR_PAD_LEFT);
    }
}
