<?php

namespace App\Http\Controllers;

use App\Models\Categorias;
use App\Models\Grupos;
use Illuminate\Http\Request;
use Auth;

class CategoriasController extends Controller
{
    public function index(Request $request)
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id;
        $grupos = Grupos::where('empresa_id', Auth::user()->empresa_id)->distinct()->get();

        if (Auth::user()->role == 'admin') {
            $categorias = Categorias::where('empresa_id', $empresa_id)->paginate();
        }
        elseif(Auth::user()->role == 'master')
        {
            $categorias = Categorias::paginate();
        }
        elseif(Auth::user()->role == 'grupo') {

            $categorias = Categorias::where('empresa_id', $empresa_id)->where('grupo_id', $grupo_id)->paginate();
        }


        $categorias = Categorias::paginate();
        return view('categorias.index', compact('categorias', 'grupos'));
    }

    public function getItens(Request $request)
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id;
        $grupos = Grupos::where('empresa_id', Auth::user()->empresa_id)->distinct()->get();

        $query = Categorias::query();

        if (Auth::user()->role == 'admin') {

            $query->where('empresa_id', $empresa_id);
        }
        elseif(Auth::user()->role == 'grupo')
        {
            $query->where('empresa_id', $empresa_id)->where('grupo_id', $grupo_id);
        }

        if ($request->filled('descricao')) {
            $query->where('descricao', 'like', '%' . $request->input('descricao') . '%');
        }
        if ($request->filled('grupo')) {
            $query->whereHas('grupo', function ($q) use ($request) {
                $q->where('descricao', 'like', '%' . $request->input('grupo') . '%');
            });
        }

        $categorias = $query->paginate(10);

        if ($request->ajax()) {
            $html = view('categorias._list-categorias', compact('categorias', 'grupos'))->render();
            $pagination = $categorias->links('pagination::bootstrap-4')->render();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination
            ]);
        }

        return view('categorias.index', compact('categorias', 'grupos'));
    }

    public function create()
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id;
        $grupos = Grupos::where('empresa_id', Auth::user()->empresa_id)->distinct()->get();

        if (Auth::user()->role == 'admin') {
            $grupos = Grupos::where('empresa_id', $empresa_id)->get();
        }
        elseif(Auth::user()->role == 'master')
        {
            $categorias = Categorias::paginate();
        }
        else{
            $grupos = Grupos::where('empresa_id', $empresa_id)->where('id', $grupo_id)->get();
        }
        return view('categorias.create', compact('grupos'));
    }

    // SALVAR
    public function store(Request $request)
    {
        $empresa_id = Auth::user()->empresa_id;
        $data = $request->all();

        Categorias::create([
            'empresa_id' => $empresa_id,
            'grupo_id' => $data['grupo'] ?? null,
            'descricao' => $data['descricao']
        ]);

        return response()->json([
            'message' => 'Categoria criada com sucesso!'
        ], 200);
    }

    // EDIT
    public function edit(Request $request, $id)
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id;
      
        $categoria = Categorias::find($id);

        if (Auth::user()->role == 'admin') {
            $grupos = Grupos::where('empresa_id', $empresa_id)->get();
        }
        elseif(Auth::user()->role == 'master')
        {
            $categorias = Categorias::paginate();
        }
        else{
            $grupos = Grupos::where('empresa_id', $empresa_id)->where('id', $grupo_id)->get();
        }

        return view('categorias.edit', compact('categoria', 'grupos'));
    }

    //UPDATE   
    public function update(Request $request, $id)
    {
        $data = $request->except('_token');

        Categorias::where('id', $id)->update([

            'descricao' => $data['descricao'],
            'grupo_id' => $data['grupo'] ?? null,
        ]);

        return response()->json([
            'message' => 'Categoria atualizada com sucesso!'
        ], 200);
    }

    public function destroy(string $id)
    {
        $categoria = Categorias::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoria não encontrada.'], 404);
        }

        $categoria->delete();
        return response()->json(['message' => 'Categoria excluída com sucesso!']);
    }

    public function toggleStatus($id)
    {
        $categoria = Categorias::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoria não encontrada'], 404);
        }
        $categoria->status = $categoria->status == 'ativo' ? 'inativo' : 'ativo';
        $categoria->save();

        return response()->json([
            'status' => $categoria->status === 'ativo' ? 'ativo' : 'inativo',
            'message' => 'Status alterado com sucesso'
        ]);
    }
}
