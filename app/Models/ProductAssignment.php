<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ProductAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_number',
        'client_id',
        'product_id',
        'assigned_by',
        'quantity',
        'dealer_price',
        'selling_price',
        'total_dealer_amount',
        'notes',
        'assigned_at',
    ];

    protected $casts = [
        'dealer_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'total_dealer_amount' => 'decimal:2',
        'quantity' => 'integer',
        'assigned_at' => 'datetime',
    ];

    /**
     * Client relationship.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Product relationship.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Administrator who assigned the product.
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Auto generate unique assignment number.
     */
    public static function generateAssignmentNumber(): string
    {
        $prefix = 'PAS-' . date('Ymd') . '-';
        $latest = static::where('assignment_number', 'like', $prefix . '%')
            ->latest('id')
            ->first();

        if (! $latest) {
            return $prefix . '0001';
        }

        $sequence = (int) substr($latest->assignment_number, -4);
        return $prefix . str_pad($sequence + 1, 4, '0', STR_PAD_LEFT);
    }
}
