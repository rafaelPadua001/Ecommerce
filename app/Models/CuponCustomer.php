<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuponCustomer extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'coupon_id',
        'coupon_name',
        'discount_percentage',
        'init_date',
        'end_date',
        'is_used'
    ];
}
