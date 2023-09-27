<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSeo extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 
        'meta_name',
        'meta_keyword', 
        'meta_description', 
        'slug',
        'product_id', 
        'user_id'
    ];
}
