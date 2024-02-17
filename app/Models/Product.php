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
        'size',
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
        'featured',
        'highlight',
        'user_id'
        
    ];
}
