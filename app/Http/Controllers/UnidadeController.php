<?php

namespace App\Http\Controllers;

use App\Models\Bandeira;
use App\Models\Unidade;
use Illuminate\Http\Request;

class UnidadeController extends Controller
{
    public function index(Request $request)
    {
        $unidades = Unidade::orderBy('nome_fantasia', 'asc')->paginate();
        return view('unidades.index', compact('unidades'));
    }

    public function getItens(Request $request)
    {
        $query = Unidade::query();

        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->input('nome') . '%');
        }

        $unidades = $query->orderBy('nome_fantasia', 'asc')->paginate();

        if ($request->ajax()) {
            $html = view('unidades._list-unidades', compact('unidades'))->render();
            $pagination = $unidades->links('pagination::bootstrap-4')->render();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination
            ]);
        }

        return view('unidades.index', compact('unidades'));
    }

    public function create()
    {
        $bandeiras = Bandeira::all();
        return view('unidades.create', compact('bandeiras'));
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');
        $request->validate(
            [
                'nome_fantasia' => 'required|string|max:255',
                'razao_social'  => 'required|string|max:255',
                'cnpj'          => 'required|string|min:18|unique:unidades,cnpj',
                'bandeira_id'   => 'required|exists:bandeiras,id'
            ],
            [
                'cnpj.min' => 'formato do CNPJ está inválido',
                'cnpj.unique' => 'Este CNPJ já está cadastrado em outra unidade.'
            ]
        );

        Unidade::create($data);

        return response()->json([
            'message' => 'Unidade criada com sucesso!'
        ], 200);
    }

    public function edit(Request $request, $id)
    {
        $unidade = Unidade::find($id);
        $bandeiras = Bandeira::all();
        return view('unidades.edit', compact('unidade', 'bandeiras'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->except('_token');
        $request->validate(
            [
                'nome_fantasia' => 'required|string|max:255',
                'razao_social'  => 'required|string|max:255',
                'cnpj'          => 'required|string|min:18',
                'bandeira_id'   => 'required|exists:bandeiras,id'
            ],
            [
                'cnpj.min' => 'formato do CNPJ está inválido',
            ]
        );

        $unidade = Unidade::findOrFail($id);
        $unidade->update($data);

        return response()->json([
            'message' => 'Unidade atualizada com sucesso!'
        ], 200);
    }

    public function destroy(string $id)
    {
        $unidade = Unidade::find($id);

        if (!$unidade) {
            return response()->json(['message' => 'Unidade não encontrada.'], 404);
        }

        $unidade->delete();
        return response()->json(['message' => 'Unidade excluída com sucesso!']);
    }
}
