<?php

namespace App\Http\Controllers;

use App\Models\Grupos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GrupoController extends Controller
{

    // LISTA
    public function index(Request $request)
    {
        $empresa_id = Auth::user()->empresa_id;
        // $grupos = Grupos::where('empresa_id', $empresa_id)->get();
        $grupos = Grupos::paginate();

        return view('grupos.index', compact('grupos'));
    }

    public function getItens(Request $request)
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id;

        $query = Grupos::query();

        if ($request->has('search') && $request->search) {
            $query->where('descricao', 'like', '%' . $request->search . '%');
        }

        if ($request->has('field') && $request->has('value') && in_array($request->field, ['descricao'])) {
            $query->where($request->field, 'like', '%' . $request->value . '%');
        }

        if (Auth::user()->role == 'admin') {
            
            $query->where('empresa_id', $empresa_id)->paginate(10);
        }
        else{
            
            $query->where('empresa_id', $empresa_id)->where('grupo_id', $grupo_id)->paginate(10);
        }

        $grupos = $query->paginate(10);

        if ($request->ajax()) {
            $html = view('grupos._list-grupos', compact('grupos'))->render();
            $pagination = $grupos->links('pagination::bootstrap-4')->render();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination
            ]);
        }

        return view('grupos.index', compact('grupos'));
    }

    public function create()
    {
        return view('grupos.create');
    }

    // SALVAR
    public function store(Request $request)
    {
        $data = $request->all(); //dd($data);

        Grupos::create([
            'empresa_id' => Auth::user()->empresa_id,
            'descricao' => $data['descricao']
        ]);

      //  $grupos = Grupos::where('empresa_id', Auth::user()->empresa_id)->get();
        $grupos = Grupos::paginate();
        return response()->json([
            'message' => 'Grupo criado com sucesso!'
        ], 200);
    }

    // EDIT
    public function edit(Request $request, $id)
    {
        $grupo = Grupos::find($id); 

        return view('grupos.edit', compact('grupo'));
    }

    //UPDATE   
    public function update(Request $request, $id)
    {
        $data = $request->except('_token');

        Grupos::where('id', $id)->update([
            'descricao' => $data['descricao']
        ]);
       // $grupos = Grupos::where('empresa_id', Auth::user()->empresa_id)->get();

       return response()->json([
        'message' => 'Grupo atualizado com sucesso!'
    ], 200);
    }

    public function destroy(string $id)
    {
        $grupo = Grupos::find($id);

        if (!$grupo) {
            return response()->json(['message' => 'Grupo não encontrado.'], 404);
        }

        $grupo->delete();
        return response()->json(['message' => 'Grupo excluído com sucesso!']);
    }

    
    public function toggleStatus($id)
    {
        $grupo = Grupos::find($id);

        if (!$grupo) {
            return response()->json(['message' => 'Grupo não encontrada'], 404);
        }
        $grupo->status = $grupo->status == 'ativo' ? 'inativo' : 'ativo';
        $grupo->save();

        return response()->json([
            'status' => $grupo->status === 'ativo' ? 'ativo' : 'inativo',
            'message' => 'Status alterado com sucesso'
        ]);
    }
}
