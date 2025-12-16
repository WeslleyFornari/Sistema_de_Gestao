<?php

namespace App\Http\Controllers;

use App\Models\Categorias;
use App\Models\Grupos;
use App\Models\Produtos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdutosController extends Controller
{
    public function index(Request $request)
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id;

        if (Auth::user()->role == 'admin') {

            $produtos = Produtos::where('empresa_id', $empresa_id)->paginate();
            $grupos = Grupos::where('empresa_id', $empresa_id)->get();
            $categorias = Categorias::where('empresa_id', $empresa_id)->get();
        } elseif (Auth::user()->role == 'master') {
            $produtos = Produtos::paginate();
            $grupos = Grupos::all();
            $categorias = Categorias::all();
        } elseif (Auth::user()->role == 'grupo') {

            $produtos = Produtos::where('empresa_id', $empresa_id)->where('grupo_id', $grupo_id)->paginate();
            $grupos = Grupos::where('id', $grupo_id)->get();
            $categorias = Categorias::where('empresa_id', $empresa_id)->where('grupo_id', $grupo_id)->paginate();
        }

        return view('produtos.index', compact('produtos', 'grupos', 'categorias'));
    }

    public function getItens(Request $request)
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id;
        $grupos = Grupos::where('empresa_id', Auth::user()->empresa_id)->distinct()->get();

        $query = Produtos::query();

        if (Auth::user()->role == 'admin') {

            $query->where('empresa_id', $empresa_id);
        } elseif (Auth::user()->role == 'grupo') {

            $query->where('empresa_id', $empresa_id)->where('grupo_id', $grupo_id);
        }

        if ($request->filled('descricao')) {

            $query->where('descricao', 'like', '%' . $request->input('descricao') . '%');
        }
        if ($request->filled('categoria')) {

            $query->where('categoria_id', $request->input('categoria'));
        }
        if ($request->filled('grupo')) {

            $query->where('grupo_id', $request->input('grupo'));
        }

        $produtos = $query->orderBy('descricao', 'asc')->paginate();

        if ($request->ajax()) {
            $html = view('produtos._list-produtos', compact('produtos'))->render();
            $pagination = $produtos->links('pagination::bootstrap-4')->render();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination
            ]);
        }

        return view('produtos.index', compact('produtos', 'grupos', 'categorias'));
    }

    public function create()
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id;

        if (Auth::user()->role == 'admin') {

            $grupos = Grupos::where('empresa_id', $empresa_id)->distinct()->get();
            $categorias = Categorias::where('empresa_id', $empresa_id)->distinct()->get();
            
        } elseif (Auth::user()->role == 'master') {
            $grupos = Grupos::all();
            $categorias = Categorias::all();
        } else {
            $grupos = Grupos::where('empresa_id', $empresa_id)->where('id', $grupo_id)->distinct()->get();
            $categorias = Categorias::where('grupo_id', $grupo_id)->distinct()->get();
        }

        return view('produtos.create', compact('grupos', 'categorias'));
    }

    // SALVAR
    public function store(Request $request)
    {
        $empresa_id = Auth::user()->empresa_id;
        $data = $request->all();

        Produtos::create([

            'empresa_id' => $empresa_id,
            'descricao' => $data['descricao'],
            'grupo_id' => $data['grupo'],
            'categoria_id' => $data['categoria'],
            'valor' => saveMoney($data['valor'])
        ]);

        return response()->json([
            'message' => 'Produto criado com sucesso!'
        ], 200);
    }

    // EDIT
    public function edit(Request $request, $id)
    {
        $produto = Produtos::find($id);
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id;

        if (Auth::user()->role == 'admin') {
            $grupos = Grupos::where('empresa_id', $empresa_id)->distinct()->get();
        } elseif (Auth::user()->role == 'master') {
            $grupos = Grupos::paginate();
        } else {
            $grupos = Grupos::where('empresa_id', $empresa_id)->where('id', $grupo_id)->distinct()->get();
        }

        if (Auth::user()->role == 'admin') {
            $grupos = Grupos::where('empresa_id', $empresa_id)->get();
            $categorias_grupo = Categorias::where('empresa_id', $empresa_id)->get();
        } else {
            $grupos = Grupos::where('empresa_id', $empresa_id)->where('id', $grupo_id)->get();
            $categorias_grupo = Categorias::where('grupo_id', $produto->grupo_id)->get();
        }

        return view('produtos.edit', compact('categorias_grupo', 'produto', 'grupos'));
    }

    //UPDATE   
    public function update(Request $request, $id)
    {
        $data = $request->except('_token');

        Produtos::where('id', $id)->update([
            'descricao' => $data['descricao'],
            'grupo_id' => $data['grupo'],
            'categoria_id' => $data['categoria'],
            'valor' => saveMoney($data['valor'])
        ]);

        return response()->json([
            'message' => 'Produto atualizado com sucesso!'
        ], 200);
    }

    public function destroy(string $id)
    {
        $produto = Produtos::find($id);

        if (!$produto) {
            return response()->json(['message' => 'Produto não encontrado.'], 404);
        }

        $produto->delete();
        return response()->json(['message' => 'Produto excluído com sucesso!']);
    }

    public function toggleStatus($id)
    {
        $produto = Produtos::find($id);

        if (!$produto) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }
        $produto->status = $produto->status == 'ativo' ? 'inativo' : 'ativo';
        $produto->save();

        return response()->json([
            'status' => $produto->status === 'ativo' ? 'ativo' : 'inativo',
            'message' => 'Status alterado com sucesso'
        ]);
    }

    // SELECT GRUPO
    public function selectGrupo($grupoId)
    {
        $categorias = Categorias::where('grupo_id', $grupoId)->get();
        return response()->json($categorias);
    }
}
