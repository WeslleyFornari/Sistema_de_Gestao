<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormasPagamentos extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'formas_pagamentos';

    protected $fillable = [      
        'id_empresa',
        'id_gateway',
        'descricao',
        'tipo',
        'taxa_real',
        'taxa_porc',
        'id_bandeira',
        'status',
    ];

    public function bandeira(){
        return $this->hasOne(Bandeiras::class,'id','id_bandeira');
    }
}
