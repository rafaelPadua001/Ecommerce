<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable = [
        'endereco',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'UF',
        'pais',
        'telefone',
        'user_id'
    ];
}
