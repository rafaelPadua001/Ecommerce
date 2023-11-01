<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchToAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'postal_code',
        'type_postal_code',
        'uf',
        'cidade',
        'bairro',
        'endereco',
        'complemento',
        'codigoIBGE',
        'user_id'
    ];
}
