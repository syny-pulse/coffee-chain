<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRecipe extends Model
{
    protected $table = 'product_recipes';
    protected $primaryKey = 'recipe_id';

    protected $fillable = [
        'product_name',
        'recipe_name',
        'coffee_variety',
        'processing_method',
        'required_grade',
        'percentage_composition',
    ];

    protected $casts = [
        'percentage_composition' => 'decimal:2',
    ];
} 