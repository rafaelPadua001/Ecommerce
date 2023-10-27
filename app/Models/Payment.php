<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['transcation_id', 
        'status',
        'quantity',
        'transaction_amount',
        'description',
        'payment_method_id',
        'payer',
        'user_id'
    ];
}
