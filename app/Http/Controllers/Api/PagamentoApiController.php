<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PagamentoResource;
use App\Models\FormasPagamentos;
use App\Models\Pagamentos;
use App\Models\PagamentosProdutos;
use App\Models\Produtos;
use Illuminate\Http\Request;
use Auth;

class PagamentoApiController extends Controller
{
    public function detalhes($id)
    {
        $pagamento = Pagamentos::find($id);
        
        return new PagamentoResource($pagamento);
    }

    public function lista()
    {
        $pagamentos = Pagamentos::all();

        return PagamentoResource::collection($pagamentos);
    }

    public function store(Request $request)
    {
       
        $data = $request->except('_token');
       
        $empresa = $request->get('empresa');
        $numero = $this->gerarCodigoAleatorio();

        if($data['status'] == 'cancelado'){
            Pagamentos::where('transacao_key', $data['id_transacao'])->update([
                'status' => 'cancelado'
            ]); 
            
            return response()->json([
                'message' => 'Pagamento cancelado com sucesso!'
            ], 200);
        }

        $pagamento = Pagamentos::create([
            'numero'                => $numero,
            'empresa_id'            => $empresa->id,
            'grupo_id'              => $data['grupo_id'] ?? null,
            'transacao_key'         => $data['id_transacao'] ?? null,
            'valor'                 => $data['valor'],
            'id_forma_pagamento'    => $data['id_forma_pagamento'] ?? null,
            'bandeira'              => $data['bandeira'] ?? null,
            'status'                => $data['status'] ?? 'pendente',
            // 'taxa'                  => $data['taxa'] ?? null,
            // 'valor_liquido'         => $data['valor_liquido']?? null,
        ]);
       
       

        foreach($data['produtos'] as $k => $v){
            PagamentosProdutos::create([
                'id_pagamento'      => $pagamento->id,
                'id_produto'        => $v['id'],
                'qtd'               => $v['qtd'],
                'valor'             => $v['valor'],
                'categoria_id'      => $v['categoria']['id'],
            ]);
        }

        return response()->json(['message' => 'Pagamento efetuado com sucesso'], 201);
    }

    function gerarCodigoAleatorio()
    {

        $letras = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4);
        $numeros = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        return "{$letras}-{$numeros}";
    }

   


    public function formasPagamento(Request $request,$id){
        $companyInfo = $request->get('empresa');
        $formasPagamento = FormasPagamentos::where('id_empresa', $companyInfo->id)
        ->where('status', 'ativo')
        ->get();
        return response()->json(['data' =>  $formasPagamento], 200);    
    }
}
