<?php

namespace App\Farmers\Models;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmerHarvest extends Model
{
    use HasFactory;

    protected $table = 'farmer_harvest';
    protected $primaryKey = 'harvest_id';

    protected $fillable = [
        'company_id',
        'coffee_variety',
        'processing_method',
        'grade',
        'quantity_kg',
        'available_quantity_kg',
        'harvest_date',
        'availability_status',
        'quality_notes',
    ];

    protected $casts = [
        'coffee_variety' => 'string',
        'processing_method' => 'string',
        'grade' => 'string',
        'availability_status' => 'string',
        'harvest_date' => 'date',
        'quantity_kg' => 'decimal:2',
        'available_quantity_kg' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
}