<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categorias extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categorias';

    protected $fillable = [
        'empresa_id',
        'grupo_id',
        'descricao',
        'status',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function grupo(){
        return $this->hasOne(Grupos::class,'id','grupo_id');
    }

    public function produtos()
    {
        return $this->hasMany(Produtos::class, 'categoria_id', 'id');
    }

}
