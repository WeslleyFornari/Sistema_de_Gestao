<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PagamentoItemResource;
use App\Models\PagamentosProdutos;
use Illuminate\Http\Request;
use Auth;

class PagamentoItemApiController extends Controller
{
    public function detalhes($id)
    {
        $itens = PagamentosProdutos::find($id);
        
        return new PagamentoItemResource($itens);
    }

    public function lista()
    {
        $empresa_id = Auth::user()->empresa_id ?? null;
       // $pagamentos = Pagamentos::where('empresa_id', $empresa_id);
       $itens = PagamentosProdutos::all();

        return PagamentoItemResource::collection($itens);
    }
}
