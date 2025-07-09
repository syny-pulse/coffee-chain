<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessorRawMaterialInventory extends Model
{
    protected $table = 'processor_raw_material_inventory';
    protected $primaryKey = 'inventory_id';
    public $timestamps = false;

    protected $fillable = [
        'processor_company_id',
        'coffee_variety',
        'processing_method',
        'grade',
        'current_stock_kg',
        'reserved_stock_kg',
        'available_stock_kg',
        'average_cost_per_kg',
        'last_updated',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'processor_company_id', 'company_id');
    }
} 