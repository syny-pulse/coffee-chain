<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRecipe extends Model
{
    protected $table = 'product_recipes';
    protected $primaryKey = 'recipe_id';
    public $timestamps = true;
    
    protected $fillable = [
        'product_name',
        'recipe_name',
        'coffee_variety',
        'processing_method',
        'required_grade',
        'percentage_composition',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'percentage_composition' => 'decimal:2'
    ];

    // Product name options
    const PRODUCT_NAMES = [
        'drinking_coffee' => 'Drinking Coffee',
        'roasted_coffee' => 'Roasted Coffee',
        'coffee_scents' => 'Coffee Scents',
        'coffee_soap' => 'Coffee Soap'
    ];

    // Coffee variety options
    const COFFEE_VARIETIES = [
        'arabica' => 'Arabica',
        'robusta' => 'Robusta'
    ];

    // Processing method options
    const PROCESSING_METHODS = [
        'natural' => 'Natural',
        'washed' => 'Washed',
        'honey' => 'Honey'
    ];

    // Required grade options
    const REQUIRED_GRADES = [
        'grade_1' => 'Grade 1',
        'grade_2' => 'Grade 2',
        'grade_3' => 'Grade 3',
        'grade_4' => 'Grade 4',
        'grade_5' => 'Grade 5'
    ];

    // Accessor for formatted product name
    public function getFormattedProductNameAttribute()
    {
        return self::PRODUCT_NAMES[$this->product_name] ?? $this->product_name;
    }

    // Accessor for formatted coffee variety
    public function getFormattedCoffeeVarietyAttribute()
    {
        return self::COFFEE_VARIETIES[$this->coffee_variety] ?? $this->coffee_variety;
    }

    // Accessor for formatted processing method
    public function getFormattedProcessingMethodAttribute()
    {
        return self::PROCESSING_METHODS[$this->processing_method] ?? $this->processing_method;
    }

    // Accessor for formatted required grade
    public function getFormattedRequiredGradeAttribute()
    {
        return self::REQUIRED_GRADES[$this->required_grade] ?? $this->required_grade;
    }
} 