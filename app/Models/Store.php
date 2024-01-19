<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    protected $fillable = ['app_name', 'app_mail', 'app_logo', 'app_phone', 'app_address', 'user_id'];
}
