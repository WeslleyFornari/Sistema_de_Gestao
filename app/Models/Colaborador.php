<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Colaborador extends Authenticatable
{
    use HasFactory, SoftDeletes, Auditable;

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

    public function unidade()
    {
        return $this->hasOne(Unidade::class, 'id', 'unidade_id');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'colaborador_id');
    }
}
