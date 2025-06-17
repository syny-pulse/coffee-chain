<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $primaryKey = 'message_id';

    protected $fillable = [
        'sender_user_id',
        'receiver_user_id',
        'sender_company_id',
        'receiver_company_id',
        'subject',
        'message_body',
        'message_type',
        'priority',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_user_id');
    }

    public function senderCompany()
    {
        return $this->belongsTo(Company::class, 'sender_company_id', 'company_id');
    }

    public function receiverCompany()
    {
        return $this->belongsTo(Company::class, 'receiver_company_id', 'company_id');
    }
}