<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'client',
        'products',
        'color',
        'date_exec',
        'value',
        'status',
        'user_id',
        'address',
        'cep',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        'pais',
        'order_id'
    ];
}
