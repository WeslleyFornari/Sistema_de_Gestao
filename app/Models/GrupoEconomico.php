<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrupoEconomico extends Model
{
    use HasFactory, Auditable;

    protected $fillable = ['nome'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function bandeiras()
    {
        return $this->hasMany(Bandeira::class, 'grupo_id');
    }
}
