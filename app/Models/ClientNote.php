<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'ref_id',
        'content',
    ];

    /**
     * Client relationship.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Sales Representative (Ref) relationship.
     */
    public function refUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ref_id');
    }
}
