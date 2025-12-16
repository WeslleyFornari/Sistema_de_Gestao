<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PagamentoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'Pedido' => $this->numero ?? null,
            'Cliente' => $this->usuario->name ?? null,
            'Grupo' => $this->grupo->descricao ?? null,
            'Categoria' => $this->categoria->descricao ?? null,
            'transacao_key' => $this->transacao_key ?? null,
            'Gateway' => $this->geteway->descricao ?? null,
            'Valor'  => $this->valor ?? null,
            'Forma de Pagamento'  => $this->formaPagamento->tipo ?? null,
            'Bandeira'  => $this->bandeira->nome ?? null,
            'Taxa'  => $this->taxa ?? null,
            'Valor liquido' => $this->valor_liquido ?? null,
            'status' =>   $this->status ?? null,

            'itens' => PagamentoItemResource::collection($this->produtos)

        ];
    }
}
