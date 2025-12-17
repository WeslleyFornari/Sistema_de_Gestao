<?php

namespace App\Http\Controllers;

use App\Models\GrupoEconomico;
use Illuminate\Http\Request;

class GrupoEconomicoController extends Controller
{
    public function index(Request $request)
    {
        $grupos = GrupoEconomico::orderBy('nome', 'asc')->paginate();
        return view('grupo_economico.index', compact('grupos'));
    }

    public function getItens(Request $request)
    {

        $query = GrupoEconomico::query();

        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->input('nome') . '%');
        }

        $grupos = $query->orderBy('nome', 'asc')->paginate();
        // dd($grupos);

        if ($request->ajax()) {
            $html = view('grupo_economico._list-grupos', compact('grupos'))->render();
            $pagination = $grupos->links('pagination::bootstrap-4')->render();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination
            ]);
        }

        return view('grupo_economico.index', compact('grupos'));
    }

    public function create()
    {
        return view('grupo_economico.create');
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        GrupoEconomico::create($data);

        return response()->json([
            'message' => 'Grupo criado com sucesso!'
        ], 200);
    }

    public function edit(Request $request, $id)
    {
        $grupo = GrupoEconomico::find($id);
        return view('grupo_economico.edit', compact('grupo'));
    }
  
    public function update(Request $request, $id)
    {
        $data = $request->except('_token');
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);
        
        $grupo = GrupoEconomico::findOrFail($id);
        $grupo->update($data);

        return response()->json([
            'message' => 'Grupo atualizado com sucesso!'
        ], 200);
    }

    public function destroy(string $id)
    {
        $grupo = GrupoEconomico::find($id);

        if (!$grupo) {
            return response()->json(['message' => 'Grupo não encontrado.'], 404);
        }

        $grupo->delete();
        return response()->json(['message' => 'Grupo excluído com sucesso!']);
    }
}
