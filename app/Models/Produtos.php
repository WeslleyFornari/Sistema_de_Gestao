<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produtos extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'produtos';

    protected $fillable = [

        'empresa_id',
        'categoria_id',
        'grupo_id',
        'descricao',
        'valor',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function categoria(){
        return $this->hasOne(Categorias::class,'id','categoria_id');
    }
    public function grupo(){
        return $this->hasOne(Grupos::class,'id','grupo_id');
    }
}
