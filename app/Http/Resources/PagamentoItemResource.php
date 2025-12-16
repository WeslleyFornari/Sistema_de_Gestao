<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PagamentoItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'produto' => $this->produto->descricao ?? null,
            'quantidade' => 1,
            'preço' => getMoney($this->valor) ?? null,
        ];
    }
}
