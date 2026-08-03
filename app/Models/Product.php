<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'description',
        'buying_price',
        'dealer_price',
        'selling_price',
        'stock_quantity',
        'minimum_stock',
        'image_path',
        'status',
    ];

    protected $casts = [
        'buying_price' => 'decimal:2',
        'dealer_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'minimum_stock' => 'integer',
    ];

    /**
     * Relationship to Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Stock status checks.
     */
    public function isOutOfStock(): bool
    {
        return $this->stock_quantity <= 0;
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity > 0 && $this->stock_quantity <= $this->minimum_stock;
    }

    public function isInStock(): bool
    {
        return $this->stock_quantity > $this->minimum_stock;
    }

    /**
     * Stock status label attribute.
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->isOutOfStock()) {
            return 'Out of Stock';
        }

        if ($this->isLowStock()) {
            return 'Low Stock Alert';
        }

        return 'In Stock';
    }

    /**
     * Profit Margin calculation.
     */
    public function getProfitMarginAttribute(): float
    {
        return (float) ($this->selling_price - $this->buying_price);
    }

    /**
     * Image URL accessor.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            return Storage::url($this->image_path);
        }

        // Return clean SVG / placeholder
        return "https://images.unsplash.com/photo-1595246140625-573b715d11dc?auto=format&fit=crop&w=400&q=80";
    }
}
