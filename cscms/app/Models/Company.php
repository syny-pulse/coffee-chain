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
        'pdf_path',
        'financial_risk_rating',
        'reputational_risk_rating',
        'compliance_risk_rating',
    ];

    protected $casts = [
        'company_type' => 'string',
        'acceptance_status' => 'string',
        'financial_risk_rating' => 'decimal:1',
        'reputational_risk_rating' => 'decimal:1',
        'compliance_risk_rating' => 'decimal:1',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class, 'company_id', 'company_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_company_id', 'company_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_company_id', 'company_id');
    }

    public function isAccepted()
    {
        return $this->acceptance_status === 'accepted';
    }

    public function isPending()
    {
        return $this->acceptance_status === 'pending';
    }

    /**
     * Get the pricings for the company.
     */
    public function pricings()
    {
        return $this->hasMany(\App\Models\Pricing::class, 'company_id');
    }
}
