<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdutoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            "descricao"=> $this->descricao ?? null,
            "valor"=> $this->valor ?? null,
            'status' =>   $this->status ?? null,
            'grupo' =>   [
                "id"=> $this->grupo->id ?? null,
                "descricao"=> $this->grupo->descricao ?? null,
            ],
            "categoria"=> [
                "id"=> $this->categoria->id ?? null,
                "descricao"=> $this->categoria->descricao ?? null,
            ]
        ];
    }
}
