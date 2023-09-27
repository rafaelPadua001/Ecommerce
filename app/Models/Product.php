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
        'stock_quantity',
        'sku',
        'weight',
        'height',
        'width',
        'length',
        'availabity',
        'slug',
        'status',
        'highlight',
        'user_id'
        
    ];
}
