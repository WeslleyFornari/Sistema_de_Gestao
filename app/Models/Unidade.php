<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unidade extends Model
{
    use HasFactory, Auditable;

    protected $fillable = ['nome_fantasia', 'razao_social', 'cnpj', 'bandeira_id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function bandeira()
    {
        return $this->hasOne(Bandeira::class, 'id', 'bandeira_id');
    }
}
