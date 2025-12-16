<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Contracts\ProductsRepositoryInterface;
use App\Models\Empresas;
use App\Models\Filas;
use App\Models\FormasPagamentos;
use App\Models\GatewaysPagamento;
use App\Models\Grupos;
use App\Models\HistoricoTaxas;
use App\Models\Pagamentos;
use App\Models\PagamentosProdutos;
use App\Models\Produtos;

class ApiController extends Controller
{
    public function login(Request $request){
        $token = $request->only('token');
        
        $empresa = Empresas::where('token', $token)->first();
        if($empresa){
            return response()->json([
                'id'=> $empresa->id,
                'status'=>$empresa->status,
                'descricao' => $empresa->nome
            ], 200);
        }
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
    public function getProductsByGroup(Request $request,$id){
        // if(!$request->has('groupId')){
        //     return response()->json([
        //         'message'   =>  'the group id is required.' 
        //     ], 400);
        // }

        $companyInfo = $request->empresa;
        $products = Produtos::where('id_grupo', $id)
        ->where('id_empresa', $companyInfo->id)
        // ->with('categoria')
        ->get();

        if(!$products){
            return response()->json([
                'message'   =>  'no products registered for this group.'
            ], 204);
        }

        $groupedProducts = $products->groupBy('categoria.descricao');
        $formattedProducts = $groupedProducts->map(function($group, $key) {
            return [
                'category' => $key,
                'products' => $group 
            ];
        })->values();

        return response()->json(['data' =>  $formattedProducts], 200);
    }

    public function getAllGroupsByCompany(Request $request){
        $companyInfo = $request->empresa;
        $groups = Grupos::where('id_empresa', $companyInfo->id)
        ->get();

        if(!$groups){
            return response()->json([
                'message'   =>  'no groups registered for this company.'
            ], 204);
        }
        return response()->json(['data' =>  $groups], 200);
    }

    public function createPayment(Request $request){
        $data = $request->all();
        // $FormaPagamento = FormasPagamentos::where('descricao', $data['bandeira'])
        // ->where('tipo', $data['tipo_operacao'])
        // ->where('id_empresa', $data['id_empresa'])
        // ->where('status', 'ativo')
        // ->get();
        // $gatewayPagamento = GatewaysPagamento::where('descricao', $data['gateway_pagamento'])->first();

        $pagamento = Pagamentos::create([
            'id_empresa'            =>  $request->empresa->id,
            'transacao_key'         =>  $data['id_transacao'],
            // 'id_geteway'         =>  $gatewayPagamento->id,
            'valor'                 =>  $data['valor'],
            // 'id_forma_pagamento' =>  $FormaPagamento->id,
            'bandeira'              =>  $data['bandeira'],
            'status'                =>  'pago',
            // 'taxa'               => 0, 
            // 'valor_liquido'      => 0
        ]);

        foreach($data['produtos'] as $k => $v){
            PagamentosProdutos::create([
                'id_pagamento'=>$pagamento->id,
                'id_produto'=>$v['id'],
                'qtd'=>$v['qtd'],
                'valor'=>$v['valor'],
            ]);
        }

        //$fila = Filas::create(['id_pagamento'    =>  $pagamento->id, 'status'   =>  'pendente']);

        return response()->json(['message'  =>  'ok'], 200);
    }

    private function calculateTaxValue($value, $paymentForm, $dcaTax) {
        $taxes = [];
        // Função anônima que calcula a taxa com base na taxa percentual e no valor fixo,
        // utilizando o valor original fornecido como parâmetro. Se a taxa percentual não for
        // nula, calcula a porcentagem da taxa em relação ao valor original e adiciona a ela
        // o valor da taxa fixa. Retorna o total da taxa a ser descontada do valor original.
        $calculateTax = function ($taxRate, $taxAmount) use ($value) {
            return $taxRate !== null ? $value * ($taxRate / 100) + $taxAmount : 0;
        };
    
        $taxes['taxa_gateway'] = $calculateTax($paymentForm->taxa_porc, $paymentForm->taxa_real);
        $taxes['taxa_dca'] = $calculateTax($dcaTax['taxa_porc'], $dcaTax['taxa_real']);
    
        return $taxes;
    }

    private function taxHistory($valueGateway,$valueDca, $idPayment){
        HistoricoTaxas::create([
            'valor_gateway' =>  $valueGateway, 
            'valor_dca' =>  $valueDca, 
            'id_pagamento'  =>  $idPayment
        ]);
    }
}
