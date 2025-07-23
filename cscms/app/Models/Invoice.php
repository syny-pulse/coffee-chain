<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'number', 'date', 'customer', 'amount', 'status', 'due_date', 'paid_at'
    ];
    protected $dates = ['date', 'due_date', 'paid_at'];
} 