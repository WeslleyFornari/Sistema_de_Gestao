<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\FluxoDeCaixaCategorias;
use App\Models\FluxoDeCaixaContas;
use App\Models\FluxoDeCaixaContasPagar;
use App\Models\Grupos;

class FluxoDeCaixaContasPagarController extends Controller
{
    private function validateRequest(Request $request)
    {
        $request->validate([
            'descricao' => 'required|string',
            'conta_id' => 'required|integer',
            'grupo_id' => 'nullable|integer',
            'categoria_id' => 'required|integer',
            'tipo' => 'required|in:saida',
            'pago' => 'required|in:sim,nao',
            'valor' => 'required',
            'parcela' => 'nullable|string',
            'data_lancamento' => 'required|date',
            'data_pagamento' => 'nullable|date',
        ]);
    }

    public function index()
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id;

        if (Auth::user()->role == 'admin') {

            $contas = FluxoDeCaixaContas::where('empresa_id', Auth::user()->empresa_id)->get();
            $grupos = Grupos::where('empresa_id', Auth::user()->empresa_id)->get(); 
            $categorias = FluxoDeCaixaCategorias::where('empresa_id', Auth::user()->empresa_id)->where('tipo', 'saida')->get();
            $contas_pagar = FluxoDeCaixaContasPagar::where('empresa_id', $empresa_id)->where('tipo', 'saida')->get(); 
        }
        elseif(Auth::user()->role == 'master')
        {
            $contas = FluxoDeCaixaContas::all();
            $grupos = Grupos::all();
            $categorias = FluxoDeCaixaCategorias::all();
            $contas_pagar = FluxoDeCaixaContasPagar::all();
        }
        elseif(Auth::user()->role == 'grupo')
        {
            $contas = FluxoDeCaixaContas::where('empresa_id', Auth::user()->empresa_id)->where('grupo_id', $grupo_id)->get();
            $grupos = Grupos::where('empresa_id', Auth::user()->empresa_id)->where('id', $grupo_id)->get(); 
            $categorias = FluxoDeCaixaCategorias::where('empresa_id', Auth::user()->empresa_id)->where('tipo', 'saida')->where('grupo_id', $grupo_id)->get();
            $contas_pagar = FluxoDeCaixaContasPagar::where('empresa_id', Auth::user()->empresa_id)->where('grupo_id', $grupo_id)->where('tipo', 'saida')->get();  
        }

        return view('fluxo_caixa.contas_pagar.index', compact('categorias', 'contas_pagar', 'contas'));
    }

    public function getItens(Request $request)
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id;
        $query = FluxoDeCaixaContasPagar::query()->where('tipo', 'saida');

        if (Auth::user()->role == 'admin') {

            $query->where('empresa_id', Auth::user()->empresa_id); 
            
        }
        elseif(Auth::user()->role == 'grupo')
        {
            $query->where('empresa_id', Auth::user()->empresa_id)->where('grupo_id', $grupo_id)->where('tipo', 'saida');   
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

        $contas_pagar = $query->orderBy('data_lancamento', 'asc')->paginate(10);
        $totalSaidas = $contas_pagar->where('tipo', 'saida')->sum('valor');

        if ($request->ajax()) {
            $html = view('fluxo_caixa.contas_pagar._list-contas', compact('contas_pagar', 'totalSaidas'))->render();
            $pagination = $contas_pagar->links('pagination::bootstrap-4')->render();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination
            ]);
        }

        return view('fluxo_caixa.contas_pagar._list-contas', compact('contas_pagar', 'totalSaidas'));
    }
       
    public function create()
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id; 

        if (Auth::user()->role == 'admin') {

            $contas = FluxoDeCaixaContas::where('empresa_id', Auth::user()->empresa_id)->get();
            $grupos = Grupos::where('empresa_id', Auth::user()->empresa_id)->get(); 
            $categorias = FluxoDeCaixaCategorias::where('empresa_id', Auth::user()->empresa_id)->where('tipo', 'saida')->get(); 
        }
        elseif(Auth::user()->role == 'master')
        {
            $contas = FluxoDeCaixaContas::all();
            $grupos = Grupos::all();
        }
        elseif(Auth::user()->role == 'grupo')
        {
            $contas = FluxoDeCaixaContas::where('empresa_id', Auth::user()->empresa_id)->where('grupo_id', $grupo_id)->get();
            $grupos = Grupos::where('empresa_id', Auth::user()->empresa_id)->where('id', $grupo_id)->get(); 
            $categorias = FluxoDeCaixaCategorias::where('empresa_id', Auth::user()->empresa_id)->where('tipo', 'saida')->where('grupo_id', $grupo_id)->get();
          
        }

        return view('fluxo_caixa.contas_pagar._form', compact('categorias', 'contas', 'grupos'));
    }

    
    public function store(Request $request)
    {
        $this->validateRequest($request); //dd($request);

        $valor = saveMoney($request->valor);
        $numParcelas = $request->parcela ?? 1;
    
        $lancamentos = [];
        $idParent = null;
    
        for ($i = 1; $i <= $numParcelas; $i++) {
            $parcelaAtual = "{$i}/{$numParcelas}";
    
            $lancamento = FluxoDeCaixaContasPagar::create([
                'empresa_id' => Auth::user()->empresa_id,
                'grupo_id' => $request->grupo_id ?? null,
                'descricao' => $request->descricao,
                'conta_id' => $request->conta_id,
                'categoria_id' => $request->categoria_id,
                'tipo' => $request->tipo,
                'pago' => $request->pago,
                'valor' => $valor,
                'parcela' => $parcelaAtual,
                'data_lancamento' => $request->data_lancamento ?? null,
                'data_pagamento' => $request->data_pagamento ?? null,
                'id_parent' => $i === 1 ? null : $idParent, 
            ]);
    
            if ($i === 1) {
                $idParent = $lancamento->id;
            }
    
            $lancamentos[] = $lancamento;
        }
    
        return response()->json($lancamentos, 201);
    }

    public function edit($id)
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id;

        $pagar = FluxoDeCaixaContaspagar::where('id', $id)
            ->where('empresa_id', Auth::user()->empresa_id)
            ->firstOrFail();

        if (Auth::user()->role == 'admin') {

            $contas = FluxoDeCaixaContas::where('empresa_id', Auth::user()->empresa_id)->get();
            $grupos = Grupos::where('empresa_id', Auth::user()->empresa_id)->get(); 
            $categorias = FluxoDeCaixaCategorias::where('empresa_id', Auth::user()->empresa_id)->where('tipo', 'saida')->get(); 
        }
        elseif(Auth::user()->role == 'master')
        {
            $contas = FluxoDeCaixaContas::all();
            $grupos = Grupos::all();
        }
        elseif(Auth::user()->role == 'grupo')
        {
            $contas = FluxoDeCaixaContas::where('empresa_id', Auth::user()->empresa_id)->where('grupo_id', $grupo_id)->get();
            $grupos = Grupos::where('empresa_id', Auth::user()->empresa_id)->where('id', $grupo_id)->get(); 
            $categorias = FluxoDeCaixaCategorias::where('empresa_id', Auth::user()->empresa_id)->where('tipo', 'saida')->where('grupo_id', $grupo_id)->get();
            
        }
     
        $categorias = FluxoDeCaixaCategorias::where('empresa_id', Auth::user()->empresa_id)->where('tipo', 'saida')->get();
        $contas = FluxoDeCaixaContas::where('empresa_id', Auth::user()->empresa_id)->get();

        return view('fluxo_caixa.contas_pagar._form', compact('categorias', 'pagar', 'contas', 'grupos'));
    }

    public function update(Request $request, $id)
    {
        $pagar = FluxoDeCaixaContasPagar::where('id', $id)
            ->where('empresa_id', Auth::user()->empresa_id)
            ->firstOrFail(); 

        $this->validateRequest($request);
        $dados = $request->all(); //dd($dados);

        $valor = saveMoney($request->valor);
   
        $camposParaAtualizar = [];

        $camposParaAtualizar['grupo_id'] = $dados['grupo_id'] ?? null;
        
        if ($request->filled('descricao')) {
            $camposParaAtualizar['descricao'] = $dados['descricao'];
        }
        if ($request->filled('id_conta')) {
            $camposParaAtualizar['conta_id'] = $dados['conta_id'];
        }
        if ($request->filled('id_categoria')) {
            $camposParaAtualizar['categoria_id'] = $dados['categoria_id'];
        }
        if ($request->filled('pago')) {
            $camposParaAtualizar['pago'] = $dados['pago'];
        }
        if ($request->filled('valor')) {
            $camposParaAtualizar['valor'] = $valor;
        }
        if ($request->filled('data_lancamento')) {
            $camposParaAtualizar['data_lancamento'] = $dados['data_lancamento'];
        }
        if ($request->filled('data_pagamento')) {
            $camposParaAtualizar['data_pagamento'] = $dados['data_pagamento'];
        }
    
        $pagar->update($camposParaAtualizar);
    
        if ($request->input('apenas_este') === 'false' && $request->apenas_este) {
            $lancamentosFuturos = FluxoDeCaixaContasPagar::where('empresa_id', Auth::user()->empresa_id)
                ->where('id_parent', $pagar->id_parent ?? $pagar->id)
                ->where('id', '>', $pagar->id)
                ->get();
    
            foreach ($lancamentosFuturos as $futuro) {
                $novaData = $this->ajustarDataParaMesesFuturos($pagar->data_lancamento, $dados['data_lancamento'] ?? $pagar->data_lancamento);
                $futuro->update(array_merge($camposParaAtualizar, [
                    'data_lancamento' => $novaData, 
                ]));
            }
    
            return response()->json([
                'message' => 'Lançamento e futuros atualizados com sucesso!',
                'lancamentos' => $lancamentosFuturos,
            ], 201);
        }
    
        return response()->json([
            'message' => 'Lançamento atualizado com sucesso!',
            'lancamento' => $pagar,
        ], 201);
    }
    
    public function destroy($id)
    {
        $lancamento = FluxoDeCaixaContasPagar::where('id', $id)
            ->where('empresa_id', Auth::user()->empresa_id)
            ->firstOrFail();

        $lancamento->delete();
        return response()->json(['message' => 'Lançamento deletado com sucesso.']);
    }

   
    public function togglePayment($id)
    {
        $lancamento = $this->togglePaymentStatus($id); 

        return response()->json([
            'message' => $lancamento->pago 
                ? 'Lançamento marcado como pago.' 
                : 'Pagamento removido.',
            'lancamento' => $lancamento,
        ], 200);
    }

    public function togglePaymentStatus($id)
    {
        $lancamento = FluxoDeCaixaContasPagar::findOrFail($id); 

        $novoStatus = $lancamento->pago === 'sim' ? 'nao' : 'sim';
        $novaDataPagamento = $novoStatus === 'sim' ? now() : null;

        $lancamento->update([
            'pago' => $novoStatus,
            'data_pagamento' => $novaDataPagamento,
        ]);

        return $lancamento;
    }

    private function ajustarDataParaMesesFuturos($dataOriginal, $novaData)
    {
        $dataOriginalCarbon = Carbon::parse($dataOriginal);
        $novaDataCarbon = Carbon::parse($novaData);
    
        $mesesDeDiferenca = $novaDataCarbon->diffInMonths($dataOriginalCarbon);
    
        $novaDataFutura = $dataOriginalCarbon->addMonths($mesesDeDiferenca);
    
        if ($novaDataFutura->day > $novaDataFutura->daysInMonth) {
            $novaDataFutura->endOfMonth();
        }
    
        return $novaDataFutura;
    }
}
