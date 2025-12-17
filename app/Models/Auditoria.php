<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    use HasFactory;

protected $table = 'auditorias';

protected $fillable = ['model', 'auditable_id', 'event', 'old_values', 'new_values', 'colaborador_id'];
protected $casts = ['old_values' => 'json', 'new_values' => 'json'];

}
