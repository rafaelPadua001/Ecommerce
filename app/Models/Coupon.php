<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'discount_percentage',
        'init_date',
        'end_date',
        'init_hour',
        'end_hour',
        'user_id',
        'is_used',
        'is_displayed'
    ];
}
