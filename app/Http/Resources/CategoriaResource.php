<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoriaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            //'Categoria' => $this->descricao ?? null,
            'Grupo' => $this->grupo->descricao ?? null,
            'status' =>   $this->status ?? null,
            'Produtos' => ProdutoResource::collection($this->produtos)
        ];
    }
}
