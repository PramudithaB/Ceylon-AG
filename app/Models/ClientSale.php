<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_number',
        'client_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_amount',
        'customer_name',
        'notes',
        'sold_at',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'quantity' => 'integer',
        'sold_at' => 'date',
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
     * Auto generate unique sale sequence number.
     */
    public static function generateSaleNumber(): string
    {
        $prefix = 'SLS-' . date('Ymd') . '-';
        $latest = static::where('sale_number', 'like', $prefix . '%')
            ->latest('id')
            ->first();

        if (! $latest) {
            return $prefix . '0001';
        }

        $sequence = (int) substr($latest->sale_number, -4);
        return $prefix . str_pad($sequence + 1, 4, '0', STR_PAD_LEFT);
    }
}
