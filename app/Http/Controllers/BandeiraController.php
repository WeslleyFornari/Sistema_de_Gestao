<?php

namespace App\Http\Controllers;

use App\Models\Bandeira;
use App\Models\GrupoEconomico;
use Illuminate\Http\Request;

class BandeiraController extends Controller
{
    public function index(Request $request)
    {
        $bandeiras = Bandeira::orderBy('nome', 'asc')->paginate();
        return view('bandeiras.index', compact('bandeiras'));
    }

    public function getItens(Request $request)
    {
        $query = Bandeira::query();

        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->input('nome') . '%');
        }

        $bandeiras = $query->orderBy('nome', 'asc')->paginate();

        if ($request->ajax()) {
            $html = view('bandeiras._list-bandeiras', compact('bandeiras'))->render();
            $pagination = $bandeiras->links('pagination::bootstrap-4')->render();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination
            ]);
        }

        return view('bandeiras.index', compact('bandeiras'));
    }

    public function create()
    {
        $grupos = GrupoEconomico::all();
        return view('bandeiras.create', compact('grupos'));
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');
        $request->validate([
            'nome' => 'required|string|max:255',
            'grupo_economico_id'=> 'required'
        ]);

        Bandeira::create($data);

        return response()->json([
            'message' => 'Bandeira criada com sucesso!'
        ], 200);
    }

    public function edit(Request $request, $id)
    {
        $bandeira = Bandeira::find($id);
        $grupos = GrupoEconomico::all();
        return view('bandeiras.edit', compact('bandeira', 'grupos'));
    }
  
    public function update(Request $request, $id)
    {
        $data = $request->except('_token');
        $request->validate([
            'nome' => 'required|string|max:255',
            'grupo_economico_id'=> 'required'
        ]);

        $bandeira = Bandeira::findOrFail($id);
        $bandeira->update($data);

        return response()->json([
            'message' => 'Bandeira atualizada com sucesso!'
        ], 200);
    }

    public function destroy(string $id)
    {
        $bandeira = Bandeira::find($id);

        if (!$bandeira) {
            return response()->json(['message' => 'Bandeira não encontrada.'], 404);
        }

        $bandeira->delete();
        return response()->json(['message' => 'Bandeira excluída com sucesso!']);
    }
}
