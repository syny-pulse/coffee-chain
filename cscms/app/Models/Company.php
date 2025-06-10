<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $primaryKey = 'company_id';

    protected $fillable = [
        'company_name',
        'email',
        'company_type',
        'phone',
        'address',
        'registration_number',
        'acceptance_status',
        'financial_risk_rating',
        'reputational_risk_rating',
        'compliance_risk_rating',
    ];

    protected $casts = [
        'financial_risk_rating' => 'decimal:1',
        'reputational_risk_rating' => 'decimal:1',
        'compliance_risk_rating' => 'decimal:1',
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function isAccepted()
    {
        return $this->acceptance_status === 'accepted';
    }

    public function isPending()
    {
        return $this->acceptance_status === 'pending';
    }
}