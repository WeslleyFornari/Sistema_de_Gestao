<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grupos extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'grupos';
    protected $appends = ['url'];
    
    protected $fillable = [
        'empresa_id',
        'descricao',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getUrlAttribute($value)
    {
        return route('getProductsByGroup',['id'=>$this->id]);
    }

    public function categorias()
    {
        return $this->hasMany(Categorias::class, 'grupo_id', 'id');
    }

}
