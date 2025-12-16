<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FluxoDeCaixaLancamentos extends Model
{
    use HasFactory;

    protected $table = 'fc_lancamentos';

    protected $fillable = [
        'empresa_id',
        'grupo_id',
        'descricao',
        'conta_id',
        'categoria_id',
        'tipo',
        'pago',
        'valor',
        'parcela',
        'data_lancamento',
        'data_pagamento',
        'id_parent'
    ];

    protected $dates = [
        'data_lancamento' => 'date',
        'data_pagamento' => 'date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function conta()
    {
        return $this->belongsTo(FluxoDeCaixaContas::class, 'conta_id');
    }

    public function categoria()
    {
        return $this->belongsTo(FluxoDeCaixaCategorias::class, 'categoria_id');
    }

    public function grupo()
    {
        return $this->hasOne(Grupos::class,'id', 'grupo_id');
    }
}
