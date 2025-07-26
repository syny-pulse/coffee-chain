<?php

namespace App\Farmers\Models;

use App\Models\Company;
use App\Models\Farmer\FarmerHarvest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmerOrder extends Model
{
    use HasFactory;

    protected $table = 'farmer_orders';
    protected $primaryKey = 'order_id';

    protected $fillable = [
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
        'reserved_quantity_kg'
    ];

    protected $casts = [
        'coffee_variety' => 'string',
        'processing_method' => 'string',
        'grade' => 'string',
        'order_status' => 'string',
        'expected_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
        'quantity_kg' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'reserved_quantity_kg' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'farmer_company_id', 'company_id');
    }

    public function processor()
    {
        return $this->belongsTo(Company::class, 'processor_company_id', 'company_id');
    }

    public function harvests()
    {
        return $this->belongsToMany(FarmerHarvest::class, 'farmer_order_harvest', 'order_id', 'harvest_id')
                    ->withPivot('allocated_quantity_kg')
                    ->withTimestamps();
    }

}
