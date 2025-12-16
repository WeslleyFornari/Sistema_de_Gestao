<?php

namespace App\Http\Controllers;

use App\Models\FluxoDeCaixaCategorias;
use App\Models\FluxoDeCaixaContas;
use App\Models\FluxoDeCaixaLancamentos;
use App\Models\Grupos;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

class FluxoDeCaixaLancamentosController extends Controller
{

    public function index()
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id;

        if (Auth::user()->role == 'admin') {

            $contas = FluxoDeCaixaContas::where('empresa_id', Auth::user()->empresa_id)->get();
            $grupos = Grupos::where('empresa_id', Auth::user()->empresa_id)->get(); 
            $categorias = FluxoDeCaixaCategorias::where('empresa_id', Auth::user()->empresa_id)->get();
            $lancamentos = FluxoDeCaixaLancamentos::where('empresa_id', Auth::user()->empresa_id)->orderBy('data_lancamento', 'asc')->paginate(10);
        }
        elseif(Auth::user()->role == 'master')
        {
            $contas = FluxoDeCaixaContas::all();
            $grupos = Grupos::all();
            $categorias = FluxoDeCaixaCategorias::all();
            $lancamentos = FluxoDeCaixaLancamentos::all();
            
        }
        elseif(Auth::user()->role == 'grupo')
        {
            $contas = FluxoDeCaixaContas::where('empresa_id', Auth::user()->empresa_id)->where('grupo_id', $grupo_id)->get();
            $grupos = Grupos::where('empresa_id', Auth::user()->empresa_id)->where('id', $grupo_id)->get(); 
            $categorias = FluxoDeCaixaCategorias::where('empresa_id', Auth::user()->empresa_id)->where('grupo_id', $grupo_id)->get();
            $lancamentos = FluxoDeCaixaLancamentos::where('empresa_id', Auth::user()->empresa_id)->where('grupo_id', $grupo_id)->orderBy('data_lancamento', 'asc')->paginate(10);
        }

        return view('fluxo_caixa.lancamentos.index', compact('lancamentos','categorias', 'contas'));
    }

    public function getItens(Request $request)
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id;
        
        $query = FluxoDeCaixaLancamentos::query();

        if (Auth::user()->role == 'admin') {

            $query->where('empresa_id', Auth::user()->empresa_id);
        }
        elseif(Auth::user()->role == 'grupo')
        {
            $query->where('empresa_id', Auth::user()->empresa_id)->where('grupo_id', $grupo_id);
        }

        if ($request->filled('data_inicio')) {
            $query->where('data_lancamento', '>=', $request->input('data_inicio'));
        }

        if ($request->filled('data_fim')) {
            $query->where('data_lancamento', '<=', $request->input('data_fim'));
        }

        if ($request->filled('descricao')) {
            $query->where('descricao', 'like', '%' . $request->input('descricao') . '%');
        }

        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->input('categoria'));
        }

        if ($request->filled('conta')) {
            $query->where('conta_id', $request->input('conta'));
        }

        if ($request->filled('valor_min')) {

            $valorMin = floatval(str_replace(['R$', '.', ','], ['', '', '.'], $request->input('valor_min')));
            $query->where('valor', '>=', $valorMin);
        }

        if ($request->filled('pago')) {
            $query->where('pago', $request->input('pago'));
        }

        $lancamentos = $query->orderBy('data_lancamento', 'asc')->paginate(10); 
        // Ordenação e obtenção dos lançamentos
      
        $totalEntradas = $lancamentos->where('tipo', 'entrada')->sum('valor');
        $totalSaidas = $lancamentos->where('tipo', 'saida')->sum('valor');
        $diferenca = $totalEntradas - $totalSaidas;

        if ($request->ajax()) {
            $html = view('fluxo_caixa.lancamentos._list', compact('lancamentos', 'totalEntradas', 'totalSaidas', 'diferenca'))->render();
            $pagination = $lancamentos->links('pagination::bootstrap-4')->render();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination
            ]);
        }

        return view('fluxo_caixa.lancamentos._list', compact('lancamentos', 'totalEntradas', 'totalSaidas', 'diferenca'));
    }


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'descricao' => 'required|string',
    //         'conta_id' => 'required|integer',
    //         'categoria_id' => 'required|integer',
    //         'tipo' => 'required|in:entrada,saida',
    //         'pago' => 'required|in:s,n',
    //         'valor' => 'required|numeric',
    //         'parcela' => 'required|string',
    //         'data_lancamento' => 'required|date',
    //         'data_pagamento' => 'nullable|date',
    //     ]);

    //     $valor = $request->tipo === 'saida' ? -abs($request->valor) : abs($request->valor);

    //     $lancamento = FluxoDeCaixaLancamentos::create([
    //         'empresa_id' => Auth::user()->empresa_id,
    //         'descricao' => $request->descricao,
    //         'conta_id' => $request->conta_id,
    //         'categoria_id' => $request->categoria_id,
    //         'tipo' => $request->tipo,
    //         'pago' => $request->pago,
    //         'valor' => $valor,
    //         'parcela' => $request->parcela,
    //         'data_lancamento' => $request->data_lancamento,
    //         'data_pagamento' => $request->data_pagamento,
    //     ]);

    //     return response()->json($lancamento, 201);
    // }

    // public function update(Request $request, $id)
    // {
    //     $lancamento = FluxoDeCaixaLancamentos::where('id', $id)
    //         ->where('empresa_id', Auth::user()->empresa_id)
    //         ->firstOrFail();

    //     $dados = $request->all();
    //     $dados['valor'] = $dados['tipo'] === 'saida' ? -abs($dados['valor']) : abs($dados['valor']);

    //     $lancamento->update($dados);
    //     return response()->json($lancamento);
    // }

    // public function destroy($id)
    // {
    //     $lancamento = FluxoDeCaixaLancamentos::where('id', $id)
    //         ->where('empresa_id', Auth::user()->empresa_id)
    //         ->firstOrFail();

    //     $lancamento->delete();
    //     return response()->json(['message' => 'Lançamento deletado com sucesso.']);
    // }
}
