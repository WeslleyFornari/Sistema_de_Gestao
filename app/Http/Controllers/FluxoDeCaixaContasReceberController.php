<?php

namespace App\Http\Controllers;

use App\Models\FluxoDeCaixaCategorias;
use App\Models\FluxoDeCaixaContas;
use App\Models\FluxoDeCaixaContasReceber;
use App\Models\Grupos;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class FluxoDeCaixaContasReceberController extends Controller
{
    private function validateRequest(Request $request)
    {
        $request->validate([
            'descricao' => 'required|string',
            'conta_id' => 'required|integer',
            'grupo_id' => 'nullable|integer',
            'categoria_id' => 'required|integer',
            'tipo' => 'required|in:entrada',
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
            $categorias = FluxoDeCaixaCategorias::where('empresa_id', Auth::user()->empresa_id)->where('tipo', 'entrada')->get();
            $contas_receber = FluxoDeCaixaContasReceber::where('empresa_id', Auth::user()->empresa_id)->where('tipo', 'entrada')->get();    
        }
        elseif(Auth::user()->role == 'master')
        {
            $contas = FluxoDeCaixaContas::all();
            $grupos = Grupos::all();
            $categorias = FluxoDeCaixaCategorias::all();
            $contas_receber = FluxoDeCaixaContasReceber::all();
        }
        elseif(Auth::user()->role == 'grupo')
        {
            $contas = FluxoDeCaixaContas::where('empresa_id', Auth::user()->empresa_id)->where('grupo_id', $grupo_id)->get();
            $grupos = Grupos::where('empresa_id', Auth::user()->empresa_id)->where('id', $grupo_id)->get(); 
            $categorias = FluxoDeCaixaCategorias::where('empresa_id', Auth::user()->empresa_id)->where('tipo', 'entrada')->where('grupo_id', $grupo_id)->get();
            $contas_receber = FluxoDeCaixaContasReceber::where('empresa_id', Auth::user()->empresa_id)->where('grupo_id', $grupo_id)->where('tipo', 'entrada')->get();  
        }

        return view('fluxo_caixa.contas_receber.index', compact('categorias', 'contas_receber', 'contas'));
    }

    public function getItens(Request $request)
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id;
        $query = FluxoDeCaixaContasReceber::query()->where('tipo', 'entrada');

        if (Auth::user()->role == 'admin') {

            $query->where('empresa_id', Auth::user()->empresa_id)->where('tipo', 'entrada');    
        }
        elseif(Auth::user()->role == 'grupo')
        {
            $query->where('empresa_id', Auth::user()->empresa_id)->where('grupo_id', $grupo_id)->where('tipo', 'entrada');  
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

        $contas_receber = $query->orderBy('data_lancamento', 'asc')->paginate(10);
        $totalEntradas = $contas_receber->where('tipo', 'entrada')->sum('valor');

        if ($request->ajax()) {
            $html = view('fluxo_caixa.contas_receber._list-contas', compact('contas_receber', 'totalEntradas'))->render();
            $pagination = $contas_receber->links('pagination::bootstrap-4')->render();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination
            ]);
        }

        return view('fluxo_caixa.contas_receber._list-contas', compact('contas_receber', 'totalEntradas'));
    }

    public function create()
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id;

        if (Auth::user()->role == 'admin') {

            $contas = FluxoDeCaixaContas::where('empresa_id', Auth::user()->empresa_id)->get();
            $grupos = Grupos::where('empresa_id', Auth::user()->empresa_id)->get(); 
            $categorias = FluxoDeCaixaCategorias::where('empresa_id', Auth::user()->empresa_id)->where('tipo', 'entrada')->get(); 
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
            $categorias = FluxoDeCaixaCategorias::where('empresa_id', Auth::user()->empresa_id)->where('tipo', 'entrada')->where('grupo_id', $grupo_id)->get();
          
        }
       

        return view('fluxo_caixa.contas_receber._form', compact('categorias', 'contas', 'grupos'));
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);

        $valor = saveMoney($request->valor);
        $numParcelas = $request->parcela ?? 1;

        $lancamentos = [];
        $idParent = null;

        for ($i = 1; $i <= $numParcelas; $i++) {
            $parcelaAtual = "{$i}/{$numParcelas}";

            $lancamento = FluxoDeCaixaContasReceber::create([
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

        if (Auth::user()->role == 'admin') {

            $contas = FluxoDeCaixaContas::where('empresa_id', Auth::user()->empresa_id)->get();
            $grupos = Grupos::where('empresa_id', Auth::user()->empresa_id)->get(); 
            $categorias = FluxoDeCaixaCategorias::where('empresa_id', Auth::user()->empresa_id)->where('tipo', 'entrada')->get(); 
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
            $categorias = FluxoDeCaixaCategorias::where('empresa_id', Auth::user()->empresa_id)->where('tipo', 'entrada')->where('grupo_id', $grupo_id)->get();
          
        }
        $receber = FluxoDeCaixaContasReceber::where('id', $id)
            ->where('empresa_id', Auth::user()->empresa_id)
            ->firstOrFail();


        return view('fluxo_caixa.contas_receber._form', compact('categorias', 'receber', 'contas', 'grupos'));
    }

    public function update(Request $request, $id)
    {
        $receber = FluxoDeCaixaContasReceber::where('id', $id)
            ->where('empresa_id', Auth::user()->empresa_id)
            ->firstOrFail();

        $this->validateRequest($request);
        $dados = $request->all(); // dd($dados);

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

        $receber->update($camposParaAtualizar);

        if ($request->input('apenas_este') === 'false' && $request->apenas_este) {
            $lancamentosFuturos = FluxoDeCaixaContasReceber::where('empresa_id', Auth::user()->empresa_id)
                ->where('id_parent', $receber->id_parent ?? $receber->id)
                ->where('id', '>', $receber->id)
                ->get();

            foreach ($lancamentosFuturos as $futuro) {
                $novaData = $this->ajustarDataParaMesesFuturos($receber->data_lancamento, $dados['data_lancamento'] ?? $receber->data_lancamento);
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
            'lancamento' => $receber,
        ], 201);
    }

    public function destroy($id)
    {
        $lancamento = FluxoDeCaixaContasReceber::where('id', $id)
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
        $lancamento = FluxoDeCaixaContasReceber::findOrFail($id);

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
