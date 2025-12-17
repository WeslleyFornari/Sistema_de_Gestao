<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bandeira extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $fillable = ['nome', 'grupo_economico_id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function grupo()
    {
        return $this->hasOne(GrupoEconomico::class, 'id', 'grupo_economico_id');
    }
}
