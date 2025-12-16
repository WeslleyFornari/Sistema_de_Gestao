<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DCATaxas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dca_taxas';

    protected $fillable = [      
        'id_formas_pagamento',
        'taxa_porc',
        'taxa_real',
    ];

    public function forma_pagamento(){
        return $this->hasOne(FormasPagamentos::class,'id','id_formas_pagamento');
    }
}
