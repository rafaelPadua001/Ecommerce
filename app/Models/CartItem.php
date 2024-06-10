<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'cart_id',
        'product_id',
        'user_id',
        'quantity',
        'price',
        'colors',
        'size',
        'delivery_price',
        'total_price',
        'cart_item_active',
        
    ];
}
