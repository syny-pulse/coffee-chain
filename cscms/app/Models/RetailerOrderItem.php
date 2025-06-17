<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RetailerOrderItem extends Model
{
    protected $table = 'retailer_order_items';
    protected $primaryKey = 'item_id';
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_variant',
        'quantity_units',
        'unit_price',
        'line_total',
    ];

    protected $casts = [
        'product_name' => 'string',
        'quantity_units' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(RetailerOrder::class, 'order_id', 'order_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}