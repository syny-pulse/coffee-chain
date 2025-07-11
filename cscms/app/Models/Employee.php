<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $primaryKey = 'employee_id';

    protected $fillable = [
        'processor_company_id',
        'employee_name',
        'employee_code',
        'skill_set',
        'primary_station',
        'current_station',
        'availability_status',
        'shift_schedule',
        'hourly_rate',
        'hire_date',
        'status',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'hourly_rate' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processor_company_id');
    }

    public function processorRetailerOrders()
    {
        return $this->hasMany(RetailerOrder::class, 'employee_id', 'employee_id');
    }

    public function farmerOrders()
    {
        return $this->hasMany(FarmerOrder::class, 'employee_id', 'employee_id');
    }
}
