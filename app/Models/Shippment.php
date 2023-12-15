<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shippment extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'company',
        'price',
        'deadline',
        'product_id',
        'quantity',
        'user_id',
        'cart_id',
        'cart_item_id',
        'delivery_id',
    ];

}
