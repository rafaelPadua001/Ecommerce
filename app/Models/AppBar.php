<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppBar extends Model
{
    use HasFactory;
    protected $fillable = ['colors', 'user_id', 'store_id'];
}
