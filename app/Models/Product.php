<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category_id',
        'subcategory_id',
        'description',
        'images',
        'platform',
        'video_links',
        'colors',
        'color_qty',
        'size',
        'size_qty',
        'unity',
        'manufacturer',
        'price',
        'discount_id',
        'stock_quantity',
        'sku',
        'weight',
        'height',
        'width',
        'length',
        'availabity',
        'slug',
        'status',
        'launch',
        'highlight',
        'user_id'
        
    ];
}
