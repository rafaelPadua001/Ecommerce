<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVideos extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'link' , 'plataform' , 'user_id', 'product_id'];
}
