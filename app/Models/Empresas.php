<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresas extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'empresas';

    protected $fillable = [      
        'id',
        'nome',
        'nome_contato',
        'telefone',
        'whatsapp',
        'cnpj',
        'cep',
        'endereco',
        'numero',
        'complemento',
        'cidade',
        'estado',
        'email',
        'status',
    ];
}
