<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Colaborador extends Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'email',
        'cpf',
        'password', 
        'unidade_id',
    ];

      protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    //  public function grupo(){
    //     return $this->hasOne(GrupoEconomico::class, 'id', 'empresa_id');
    // }
}
