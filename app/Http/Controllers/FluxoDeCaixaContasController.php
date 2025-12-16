<?php

namespace App\Http\Controllers;

use App\Models\FluxoDeCaixaContas;
use App\Models\FluxoDeCaixaContasReceber;
use App\Models\Grupos;
use Illuminate\Http\Request;
use Auth;

class FluxoDeCaixaContasController extends Controller
{
    public function index()
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id;

        if (Auth::user()->role == 'admin') {

            $contas = FluxoDeCaixaContas::where('empresa_id', Auth::user()->empresa_id)->get();
        }
        elseif(Auth::user()->role == 'master')
        {
            $contas = FluxoDeCaixaContas::all();
        }
        elseif(Auth::user()->role == 'grupo')
        {
            $contas = FluxoDeCaixaContas::where('empresa_id', Auth::user()->empresa_id)->where('grupo_id', $grupo_id)->get();   
        }

        return view('fluxo_caixa.contas.index', compact('contas'));
    }

    public function getItens(Request $request)
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id; 

        $query = FluxoDeCaixaContas::query();

        if (Auth::user()->role == 'admin') {

            $query = FluxoDeCaixaContas::where('empresa_id', Auth::user()->empresa_id);
        }
        elseif(Auth::user()->role == 'grupo')
        {
            $query = FluxoDeCaixaContas::where('empresa_id', Auth::user()->empresa_id)->where('grupo_id', $grupo_id);   
        }

        if ($request->filled('descricao')) {
            $query->where('descricao', 'like', '%' . $request->input('descricao') . '%');
        }

        if ($request->filled('valor_min')) {

            $valorMin = floatval(str_replace(['R$', '.', ','], ['', '', '.'], $request->input('valor_min')));
            $query->where('saldo_inicial', '>=', $valorMin);
        }

        $contas = $query->paginate(10);

        if ($request->ajax()) {
            $html = view('fluxo_caixa.contas._list-contas', compact('contas'))->render();
            $pagination = $contas->links('pagination::bootstrap-4')->render();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination
            ]);
        }

        return view('fluxo_caixa.contas._list-contas', compact('contas'));
    }

    public function create()
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id;

        if (Auth::user()->role == 'admin') {

            $grupos = Grupos::where('empresa_id', Auth::user()->empresa_id)->get(); 
        }
        elseif(Auth::user()->role == 'grupo')
        {
            $grupos = Grupos::where('id', $grupo_id)->get();   
        }

      
        return view('fluxo_caixa.contas.create', compact('grupos'));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'descricao' => 'required|string',
            'saldo_inicial' => 'nullable',
        ]);
    
        $saldo_inicial = $request->saldo_inicial ?? 0;
    
        FluxoDeCaixaContas::create([
            'empresa_id' => Auth::user()->empresa_id,
            'grupo_id' => $request->grupo_id ?? null,
            'descricao' => $request->descricao,
            'saldo_inicial' => saveMoney($saldo_inicial), 
        ]);
    
        return response()->json([
            'message' => 'Conta criada com sucesso!'
        ], 201);
    }

    public function edit(Request $request, $id)
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id;
        $conta = FluxoDeCaixaContas::where('id', $id)->first();

        if (Auth::user()->role == 'admin') {

            $grupos = Grupos::where('empresa_id', Auth::user()->empresa_id)->get(); 
        }
        elseif(Auth::user()->role == 'grupo')
        {
            $grupos = Grupos::where('id', $grupo_id)->get();   
        }
      
        return view('fluxo_caixa.contas.edit', compact('conta', 'grupos'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->except('_token');
    
        $data['saldo_inicial'] = saveMoney($data['saldo_inicial']);

        FluxoDeCaixaContas::where('id', $id)->update($data);     
        
        return response()->json([
            'message' => 'Conta atualizada com sucesso!'
        ], 201);
    }

    public function destroy(string $id)
    {
        $conta = FluxoDeCaixaContas::find($id);

        if (!$conta) {
            return response()->json(['message' => 'Conta não encontrada.'], 404);
        }

        $conta->delete();
        return response()->json(['message' => 'Conta excluída com sucesso!']);
    }

    public function toggleStatus($id)
    {
        $conta = FluxoDeCaixaContas::find($id);

        if (!$conta) {
            return response()->json(['message' => 'Conta não encontrada'], 404);
        }
        $conta->status = $conta->status == 'ativo' ? 'inativo' : 'ativo';
        $conta->save();

        return response()->json([
            'status' => $conta->status === 'ativo' ? 'ativo' : 'inativo',
            'message' => 'Status alterado com sucesso'
        ]);
    }
}
