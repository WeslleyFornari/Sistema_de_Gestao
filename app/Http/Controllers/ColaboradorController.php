<?php

namespace App\Http\Controllers;

use App\Models\Colaborador;
use Illuminate\Http\Request;

class ColaboradorController extends Controller
{
    public function index(Request $request)
    {

        $colaboradores = Colaborador::orderBy('nome', 'asc')->paginate();

        return view('colaboradores.index', compact('colaboradores'));
    }

    public function getItens(Request $request)
    {
        $query = Colaborador::query();

        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }

        $colaboradores = $query->orderBy('nome', 'asc')->paginate();

        if ($request->ajax()) {
            $html = view('colaboradores._list-colaboradores', compact('colaboradores'))->render();
            $pagination = $colaboradores->links('pagination::bootstrap-4')->render();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination
            ]);
        }

        return view('colaboradores.index', compact('colaboradores'));
    }

    public function create()
    {
        return view('colaboradores.create');
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        Colaborador::create($data);

        return response()->json([
            'message' => 'Colaborador criado com sucesso!'
        ], 200);
    }

    public function edit(Request $request, $id)
    {
        $colaborador = Colaborador::find($id);
        return view('colaboradores.edit', compact('colaborador'));
    }
 
    public function update(Request $request, $id)
    {
        $data = $request->except('_token');
        $colaborador = Colaborador::findOrFail($id);
        $colaborador->update($data);

        return response()->json([
            'message' => 'Colaborador atualizado com sucesso!'
        ], 200);
    }

    public function destroy(string $id)
    {
        $colaborador = Colaborador::find($id);

        if (!$colaborador) {
            return response()->json(['message' => 'Colaborador não encontrado.'], 404);
        }

        $colaborador->delete();
        return response()->json(['message' => 'Colaborador excluído com sucesso!']);
    }
}
