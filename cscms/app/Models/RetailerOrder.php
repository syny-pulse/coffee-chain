<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RetailerOrder extends Model
{
    protected $table = 'retailer_orders';
    protected $primaryKey = 'order_id';
    protected $fillable = [
        'order_number',
        'processor_company_id',
        'employee_id',
        'total_amount',
        'expected_delivery_date',
        'actual_delivery_date',
        'order_status',
        'shipping_address',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'expected_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
        'order_status' => 'string',
    ];

    public function processor(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'processor_company_id', 'company_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(RetailerOrderItem::class, 'order_id', 'order_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
