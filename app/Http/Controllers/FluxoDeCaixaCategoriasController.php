<?php

namespace App\Http\Controllers;

use App\Models\FluxoDeCaixaCategorias;
use App\Models\Grupos;
use Illuminate\Http\Request;
use Auth;

class FluxoDeCaixaCategoriasController extends Controller
{
    public function index()
    {
        $grupo_id = Auth::user()->grupo_id;
        $tipo_categorias = FluxoDeCaixaCategorias::where('empresa_id', Auth::user()->empresa_id)->select('tipo')->distinct()->get();

        if (Auth::user()->role == 'admin') {

            $categorias = FluxoDeCaixaCategorias::where('empresa_id', Auth::user()->empresa_id)->get();
        }
        elseif(Auth::user()->role == 'master')
        {
            $categorias = FluxoDeCaixaCategorias::all();
        }
        elseif(Auth::user()->role == 'grupo')
        {
            $categorias = FluxoDeCaixaCategorias::where('empresa_id', Auth::user()->empresa_id)->where('grupo_id', $grupo_id)->get();  
        }
      
        return view('fluxo_caixa.categorias.index', compact('categorias', 'tipo_categorias'));
    }

    public function getItens(Request $request)
    {
        $grupo_id = Auth::user()->grupo_id;
        $query = FluxoDeCaixaCategorias::query();

        if (Auth::user()->role == 'admin') {

            $query = FluxoDeCaixaCategorias::where('empresa_id', Auth::user()->empresa_id);
        }
        elseif(Auth::user()->role == 'grupo')
        {
            $query = FluxoDeCaixaCategorias::where('empresa_id', Auth::user()->empresa_id)->where('grupo_id', $grupo_id);  
        }

        if ($request->filled('descricao')) {
            $query->where('descricao', 'like', '%' . $request->input('descricao') . '%');
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->input('tipo'));
        }

        $categorias = $query->paginate(10);

        if ($request->ajax()) {
            $html = view('fluxo_caixa.categorias._list-categorias', compact('categorias'))->render();
            $pagination = $categorias->links('pagination::bootstrap-4')->render();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination
            ]);
        }

        return view('fluxo_caixa.categorias._list-categorias', compact('categorias'));
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

        return view('fluxo_caixa.categorias.create', compact('grupos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descricao' => 'required|string',
            'tipo' => 'nullable',
        ]);
        
        FluxoDeCaixaCategorias::create([
            'empresa_id' => Auth::user()->empresa_id,
            'grupo_id' => $request->grupo_id ?? null,
            'descricao' => $request->descricao,
            'tipo' => $request->tipo, 
        ]);
    
        return response()->json([
            'message' => 'Categoria criada com sucesso!'
        ], 201);
    }

    public function edit(Request $request, $id)
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id;
        $categoria = FluxoDeCaixaCategorias::where('id', $id)->first();

        if (Auth::user()->role == 'admin') {

            $grupos = Grupos::where('empresa_id', Auth::user()->empresa_id)->get(); 
        }
        elseif(Auth::user()->role == 'grupo')
        {
            $grupos = Grupos::where('id', $grupo_id)->get();  
             
        }
        return view('fluxo_caixa.categorias.edit', compact('categoria', 'grupos'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->except('_token');

        FluxoDeCaixaCategorias::where('id', $id)->update($data);     
        
        return response()->json([
            'message' => 'Categoria atualizada com sucesso!'
        ], 201);
    }

    public function destroy(string $id)
    {
        $categoria = FluxoDeCaixaCategorias::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'categoria não encontrada.'], 404);
        }

        $categoria->delete();
        return response()->json(['message' => 'categoria excluída com sucesso!']);
    }

    public function toggleStatus($id)
    {
        $categoria = FluxoDeCaixaCategorias::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'categoria não encontrada'], 404);
        }
        $categoria->status = $categoria->status == 'ativo' ? 'inativo' : 'ativo';
        $categoria->save();

        return response()->json([
            'status' => $categoria->status === 'ativo' ? 'ativo' : 'inativo',
            'message' => 'Status alterado com sucesso'
        ]);
    }
}
