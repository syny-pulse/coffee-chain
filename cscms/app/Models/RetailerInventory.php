<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetailerInventory extends Model
{
    use HasFactory;

    protected $table = 'retailer_inventory';

    protected $fillable = [
        'product_type',
        'coffee_breed',
        'roast_grade',
        'quantity',
    ];
} 