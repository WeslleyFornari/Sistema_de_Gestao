<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FluxoDeCaixaContas extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'empresa_id',
        'grupo_id',
        'descricao',
        'saldo_inicial',
        'status'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function grupo(){

        return $this->hasOne(Grupos::class,'id','grupo_id');
    }
}

