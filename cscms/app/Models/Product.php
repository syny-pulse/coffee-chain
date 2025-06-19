<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'product_type',
        'origin_country',
        'processing_method',
        'roast_level',
        'quantity_kg',
        'price_per_kg',
        'quality_score',
        'harvest_date',
        'processing_date',
        'expiry_date',
        'description',
        'status',
    ];

    protected $casts = [
        'product_type' => 'string',
        'processing_method' => 'string',
        'roast_level' => 'string',
        'status' => 'string',
        'harvest_date' => 'date',
        'processing_date' => 'date',
        'expiry_date' => 'date',
        'quantity_kg' => 'decimal:2',
        'price_per_kg' => 'decimal:2',
        'quality_score' => 'decimal:1',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}