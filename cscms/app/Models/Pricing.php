<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    protected $table = 'pricings';
    protected $fillable = [
        'company_id',
        'coffee_variety',
        'grade',
        'unit_price',
        'processing_method',
    ];
} 