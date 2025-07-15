<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmerOrder extends Model
{
    protected $table = 'farmer_orders';
    protected $primaryKey = 'order_id';
    protected $fillable = [
        'processor_company_id',
        'farmer_company_id',
        'employee_id',
        'coffee_variety',
        'processing_method',
        'grade',
        'quantity_kg',
        'unit_price',
        'total_amount',
        'expected_delivery_date',
        'actual_delivery_date',
        'order_status',
        'notes',
    ];

    protected $casts = [
        'coffee_variety' => 'string',
        'processing_method' => 'string',
        'grade' => 'string',
        'quantity_kg' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'expected_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
        'order_status' => 'string',
    ];

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'farmer_company_id', 'company_id');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'processor_company_id', 'company_id');
    }

     public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    /**
     * Get the route key name for Laravel.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'order_id';
    }
}
