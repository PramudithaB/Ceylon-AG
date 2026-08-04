<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Quotation extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_SENT = 'sent';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'quotation_number',
        'client_id',
        'created_by',
        'customer_name',
        'business_name',
        'address',
        'phone',
        'email',
        'district',
        'quotation_date',
        'expiry_date',
        'status',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'grand_total',
        'bank_name',
        'bank_branch',
        'account_name',
        'account_number',
        'swift_code',
        'notes',
        'terms_conditions',
        'delivery_period',
        'prepared_by',
        'approved_by',
        'sent_at',
    ];

    protected $casts = [
        'quotation_date' => 'date',
        'expiry_date' => 'date',
        'sent_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    /**
     * Relationship to client user.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Relationship to creator admin user.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship to quotation line items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class, 'quotation_id');
    }

    /**
     * Generate auto sequential quotation number in format QTN-2026-000001
     */
    public static function generateQuotationNumber(): string
    {
        $year = date('Y');
        $prefix = "QTN-{$year}-";

        $latest = self::where('quotation_number', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        if ($latest) {
            $lastNumber = (int) substr($latest->quotation_number, -6);
            $nextNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '000001';
        }

        return "{$prefix}{$nextNumber}";
    }

    /**
     * Check if quotation is expired based on current date.
     */
    public function checkAndMarkExpired(): bool
    {
        if ($this->status !== self::STATUS_ACCEPTED && $this->expiry_date && Carbon::parse($this->expiry_date)->isPast()) {
            if ($this->status !== self::STATUS_EXPIRED) {
                $this->update(['status' => self::STATUS_EXPIRED]);
            }
            return true;
        }

        return false;
    }
}
