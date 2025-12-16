<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamentos extends Model
{
    use HasFactory;
    
    protected $table = 'pagamentos';

    protected $fillable = [
        'numero',
        'empresa_id',
        'grupo_id',
        'categoria_id',
        'user_id',
        'transacao_key',
        'id_geteway',
        'valor',
        'id_forma_pagamento',
        'bandeira',
        'status',
        'taxa',
        'valor_liquido',
    ];

    public function usuario()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function grupo()
    {
        return $this->hasOne(Grupos::class, 'id', 'grupo_id');
    }
    public function categoria()
    {
        return $this->hasOne(Categorias::class, 'id', 'categoria_id');
    }
    public function produtos()
    {
        return $this->hasMany(PagamentosProdutos::class, 'id_pagamento', 'id');
    }
    public function formaPagamento()
    {
        return $this->hasOne(FormasPagamentos::class, 'id', 'id_forma_pagamento');
    }
    public function geteway()
    {
        return $this->hasOne(GatewaysPagamento::class, 'id', 'id_geteway');
    }
    public function flag()
    {
        return $this->hasOne(Bandeiras::class, 'id', 'bandeira');
    }
   
}
