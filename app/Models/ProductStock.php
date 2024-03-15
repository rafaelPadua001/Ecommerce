<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use HasFactory;
    protected $fillable = [
     'name',
     'stock_quantity',
     'product_size',
     'size_qty',
     'product_colors',
     'color_qty',
     'product_id',
     'user_id',
    ];
}
