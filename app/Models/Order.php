<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'client',
        'description',
        'color',
        'date_exec',
        'value',
        'status',
        'user_id',
        'cart_item_id'
    ];
}
