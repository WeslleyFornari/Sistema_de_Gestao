<?php

namespace App\Http\Controllers;

use App\Models\Categorias;
use App\Models\Empresas;
use App\Models\FormasPagamentos;
use App\Models\Grupos;
use App\Models\Pagamentos;
use App\Models\PagamentosProdutos;
use App\Models\Produtos;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $empresa_id = Auth::user()->empresa_id; 
        $grupo_id = Auth::user()->grupo_id;

        if (Auth::user()->role == 'admin') {

            $pedidos_qtd = Pagamentos::where('empresa_id', $empresa_id)->count();
            $clientes_qtd = Pagamentos::where('empresa_id', $empresa_id)->whereNull('user_id')->count();
            $faturamento = Pagamentos::where('empresa_id', $empresa_id)->where('status', 'pago')->sum('valor'); //dd($faturamento);
        } 
        elseif (Auth::user()->role == 'master') {
            $pedidos_qtd = Pagamentos::count();
            $clientes_qtd = Pagamentos::whereNull('user_id')->count();
            $faturamento = Pagamentos::where('status', 'pago')->sum('valor');
        }
        elseif (Auth::user()->role == 'grupo') {
            $pedidos_qtd = Pagamentos::where('empresa_id', $empresa_id)->where('grupo_id', $grupo_id)->count();
            $clientes_qtd = Pagamentos::where('empresa_id', $empresa_id)->where('grupo_id', $grupo_id)->whereNull('user_id')->count();
            $faturamento = Pagamentos::where('empresa_id', $empresa_id)->where('grupo_id', $grupo_id)->where('status', 'pago')->sum('valor');
        }

        $formaPagamento = $this->formaPagamento($empresa_id, $grupo_id);
        $quantidadePagamentos = $this->quantidadePagamentos($empresa_id, $grupo_id);
        $produtosMaisVendidos = $this->produtosMaisVendidos($empresa_id, $grupo_id);
        $faturamentoDiario = $this->faturamentoDiario($empresa_id, $grupo_id);
        $faturamentoAnual = $this->faturamentoAnual($empresa_id, $grupo_id);

        return view('dashboard.index', [
            'pedidos_qtd' => $pedidos_qtd,
            'clientes_qtd' => $clientes_qtd,
            'faturamento' => $faturamento,
            'formaPagamento' => $formaPagamento,
            'produtosMaisVendidos' => $produtosMaisVendidos,
            'faturamentoDiario' => $faturamentoDiario,
            'faturamentoAnual' => $faturamentoAnual,
            'quantidadePagamentos' => $quantidadePagamentos['dados'], // Para exibição no Blade
            'graficoPagamentos' => $quantidadePagamentos['grafico'] // Para o gráfico
        ]);
    }

// Grafico de Barra
    public function formaPagamento($empresa_id, $grupo_id)
    {

        if (Auth::user()->role == 'admin') {

            $pedidosForma = Pagamentos::with('formaPagamento')
                ->where('empresa_id', $empresa_id)
                ->where('status', 'pago')
                ->selectRaw('id_forma_pagamento, SUM(valor) as total')
                ->groupBy('id_forma_pagamento')
                ->get();
        }
        elseif(Auth::user()->role == 'master') {

            $pedidosForma = Pagamentos::with('formaPagamento')
                ->where('status', 'pago')
                ->selectRaw('id_forma_pagamento, SUM(valor) as total')
                ->groupBy('id_forma_pagamento')
                ->get();
        }
        elseif(Auth::user()->role == 'grupo') {

            $pedidosForma = Pagamentos::with('formaPagamento')
                ->where('empresa_id', $empresa_id)
                ->where('grupo_id', $grupo_id)
                ->where('status', 'pago')
                ->selectRaw('id_forma_pagamento, SUM(valor) as total')
                ->groupBy('id_forma_pagamento')
                ->get();
        }

            $formaPagamento = $pedidosForma->map(function ($pagamento) {
                return [
                    'y' => (float) $pagamento->total,
                    'label' => ucfirst($pagamento->formaPagamento->tipo)
                ];
            })->toArray();

            return $formaPagamento;
    }


    public function quantidadePagamentos($empresa_id, $grupo_id)
    {
        if (Auth::user()->role == 'admin') {

            $pedidosForma = Pagamentos::with('formaPagamento')
                ->where('empresa_id', $empresa_id)
                ->where('status', 'pago')
                ->selectRaw('id_forma_pagamento, COUNT(*) as quantidade, SUM(valor) as total_valor') // Soma dos valores
                ->groupBy('id_forma_pagamento')
                ->get();
        }
        elseif(Auth::user()->role == 'master'){

            $pedidosForma = Pagamentos::with('formaPagamento')
                ->where('status', 'pago')
                ->selectRaw('id_forma_pagamento, COUNT(*) as quantidade, SUM(valor) as total_valor') // Soma dos valores
                ->groupBy('id_forma_pagamento')
                ->get();
        }
        elseif(Auth::user()->role == 'grupo'){

            $pedidosForma = Pagamentos::with('formaPagamento')
                ->where('empresa_id', $empresa_id)
                ->where('grupo_id', $grupo_id)
                ->where('status', 'pago')
                ->selectRaw('id_forma_pagamento, COUNT(*) as quantidade, SUM(valor) as total_valor') // Soma dos valores
                ->groupBy('id_forma_pagamento')
                ->get();
        }

        $quantidadePagamentos = $pedidosForma->map(function ($pagamento) {
            return [
                'tipo' => $pagamento->formaPagamento->tipo,
                'quantidade' => (int) $pagamento->quantidade,
                'total_valor' => (float) $pagamento->total_valor
            ];
        });
    
        // Conversão dos dados para o formato do gráfico
        $graficoData = $quantidadePagamentos->map(function ($pagamento) {
            return [$pagamento['tipo'], $pagamento['quantidade']];
        })->toArray();
    
        return [
            'dados' => $quantidadePagamentos->toArray(), // Dados para exibição no Blade
            'grafico' => $graficoData // Dados formatados para o Google Charts
        ];
    }

    public function produtosMaisVendidos($empresa_id, $grupo_id)
    {
        if (Auth::user()->role == 'admin') {

            $produtosVendidos = PagamentosProdutos::with('produto')
                ->whereHas('pagamento', function ($query) use ($empresa_id) {
                    $query->where('empresa_id', $empresa_id);
                })
                ->selectRaw('id_produto, COUNT(id_produto) as total_quantidade, SUM(valor) as total_valor')
                ->groupBy('id_produto')
                ->orderByDesc('total_quantidade')
                ->limit(5)
                ->get();
        }
        elseif(Auth::user()->role == 'master'){

            $produtosVendidos = PagamentosProdutos::with('produto')
                ->selectRaw('id_produto, COUNT(id_produto) as total_quantidade, SUM(valor) as total_valor')
                ->groupBy('id_produto')
                ->orderByDesc('total_quantidade')
                ->limit(5)
                ->get(); 
        }
        elseif(Auth::user()->role == 'grupo'){

            $produtosVendidos = PagamentosProdutos::with('produto')
                ->whereHas('pagamento', function ($query) use ($empresa_id, $grupo_id) {
                    $query->where('empresa_id', $empresa_id);
                    $query->where('grupo_id', $grupo_id);
                })
                ->selectRaw('id_produto, COUNT(id_produto) as total_quantidade, SUM(valor) as total_valor')
                ->groupBy('id_produto')
                ->orderByDesc('total_quantidade')
                ->limit(5)
                ->get(); 
        }
        $dataPoints  = [['Produto', 'quantidade']];

        foreach ($produtosVendidos as $item) {
         
            $dataPoints[] = [
                $item->produto->descricao ?? 'Produto não encontrado',
                (int) $item->total_quantidade
            ];
        }

        return $dataPoints;
    }

    public function faturamentoDiario($empresa_id, $grupo_id)
    {
        if (Auth::user()->role == 'admin') {

            $faturamento = Pagamentos::selectRaw('DAYOFWEEK(created_at) as dia, SUM(valor) as total')
                ->where('empresa_id', $empresa_id)
                ->where('status', 'pago')
                ->groupBy('dia')
                ->orderBy('dia')
                ->get();
        }
        elseif(Auth::user()->role == 'master'){

            $faturamento = Pagamentos::selectRaw('DAYOFWEEK(created_at) as dia, SUM(valor) as total')
                ->where('status', 'pago')
                ->groupBy('dia')
                ->orderBy('dia')
                ->get();
        }
        elseif(Auth::user()->role == 'grupo'){

            $faturamento = Pagamentos::selectRaw('DAYOFWEEK(created_at) as dia, SUM(valor) as total')
                ->where('empresa_id', $empresa_id)
                ->where('grupo_id', $grupo_id)
                ->where('status', 'pago')
                ->groupBy('dia')
                ->orderBy('dia')
                ->get();
        }

        $diasDaSemana = [
            1 => 'Domingo',
            2 => 'Segunda',
            3 => 'Terça',
            4 => 'Quarta',
            5 => 'Quinta',
            6 => 'Sexta',
            7 => 'Sábado'
        ];

        $faturamentoDiario = $faturamento->map(function ($item) use ($diasDaSemana) {
            return [
                'label' => $diasDaSemana[$item->dia], // Traduz os dias para português
                'y' => (float) $item->total
            ];
        });

        return $faturamentoDiario;
    }

    public function faturamentoAnual($empresa_id, $grupo_id)
    {
        $anoAtual = date('Y');

        if (Auth::user()->role == 'admin') {
            $faturamento = Pagamentos::selectRaw('MONTH(created_at) as mes, SUM(valor) as total')
                ->where('empresa_id', $empresa_id)
                ->where('status', 'pago')
                ->whereYear('created_at', $anoAtual)
                ->groupBy('mes')
                ->orderBy('mes')
                ->get();
                
        } 
        elseif (Auth::user()->role == 'master') {
            $faturamento = Pagamentos::selectRaw('MONTH(created_at) as mes, SUM(valor) as total')
                ->where('status', 'pago')
                ->whereYear('created_at', $anoAtual)
                ->groupBy('mes')
                ->orderBy('mes')
                ->get();
        }
        elseif (Auth::user()->role == 'grupo') {
            $faturamento = Pagamentos::selectRaw('MONTH(created_at) as mes, SUM(valor) as total')
                ->where('empresa_id', $empresa_id)
                ->where('grupo_id', $grupo_id)
                ->where('status', 'pago')
                ->whereYear('created_at', $anoAtual)
                ->groupBy('mes')
                ->orderBy('mes')
                ->get();
        }
    
        $mesesDoAno = [
            1 => 'JAN', 2 => 'FEV', 3 => 'MAR', 4 => 'ABR',
            5 => 'MAI', 6 => 'JUN', 7 => 'JUL', 8 => 'AGO',
            9 => 'SET', 10 => 'OUT', 11 => 'NOV', 12 => 'DEZ'
        ];
    
        $faturamentoAnual = collect($mesesDoAno)->map(function ($nome, $numero) use ($faturamento) {
            $mes = $faturamento->firstWhere('mes', $numero);
            return [
                'label' => $nome,
                'y' => $mes ? (float) $mes->total : 0
            ];
        });

        // dd([
        //     'dados_brutos' => $faturamento->toArray(),
        //     'ano_filtrado' => $anoAtual,
        //     'faturamento_anual_formatado' => $faturamentoAnual->toArray(),
        // ]);
  
        return $faturamentoAnual;
    }
}
