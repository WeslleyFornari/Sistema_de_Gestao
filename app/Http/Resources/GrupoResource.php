<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GrupoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

       
        'id' => $this->id ?? null,
        'grupo' => $this->descricao ?? null,
        'status' =>   $this->status ?? null,
       // 'Categorias' => CategoriaResource::collection($this->categorias)

    ];
    }
}
