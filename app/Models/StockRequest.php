<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockRequest extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'request_number',
        'client_id',
        'product_id',
        'requested_quantity',
        'notes',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'requested_quantity' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

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

    public static function generateRequestNumber(): string
    {
        $prefix = 'STK-' . date('Ymd') . '-';
        $latest = self::where('request_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (! $latest) {
            return $prefix . '0001';
        }

        $sequence = (int) substr($latest->request_number, -4);
        return $prefix . str_pad($sequence + 1, 4, '0', STR_PAD_LEFT);
    }
}
