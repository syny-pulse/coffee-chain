<?php

namespace App\Farmers\Models;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        'company_id',
        'processor_id',
        'content',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function processor()
    {
        return $this->belongsTo(Company::class, 'processor_id', 'company_id');
    }
}