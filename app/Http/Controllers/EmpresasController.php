<?php

namespace App\Http\Controllers;

use App\Models\Empresas;
use Illuminate\Http\Request;
use Auth;

class EmpresasController extends Controller
{
    public function index()
    {
        $empresa_id = Auth::user()->empresa_id; 

        if (Auth::user()->role == 'master') {

            $empresas = Empresas::paginate();

        } elseif (Auth::user()->role == 'admin') {

            $empresas = Empresas::where('id', $empresa_id)->get();
        }

        return view('empresas.index', compact('empresas'));
    }

    public function getItens(Request $request)
    {

        $empresa_id = Auth::user()->empresa_id; 
        $role = Auth::user()->role;

        if (Auth::user()->role == 'master') {
            $query = Empresas::query();
        } elseif (Auth::user()->role == 'admin') {

            $query = Empresas::query()->where('id', $empresa_id);
        }

        if ($request->has('search') && $request->search) {
            $query->where('nome', 'like', '%' . $request->search . '%');
            $query->orWhere('nome_contato', 'like', '%' . $request->search . '%');
        }

        if ($request->has('field') && $request->has('value') && in_array($request->field, ['nome', 'nome_contato'])) {
            $query->where($request->field, 'like', '%' . $request->value . '%');
        }

        $empresas = $query->paginate(10);

        if ($request->ajax()) {
            $html = view('empresas._list', compact('empresas'))->render();
            $pagination = $empresas->links('pagination::bootstrap-4')->render();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination
            ]);
        }

        return view('empresas.index', compact('empresas'));
    }

    public function new()
    {

        return view('empresas.new');
    }
    public function store(Request $request)
    {

        $request->except('_token');
        $request->validate([
            'nome' => 'required|string|max:255',
            'nome_contato' => 'required|string|max:255',
            'telefone' => 'required|string|min:11',
            'cnpj' => 'required|string|unique:empresas',
            'cep' => 'required',
            'endereco' => 'required',
            'numero' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'email' => 'required|email|unique:empresas',
        ]);

        $empresa = new Empresas;
        $empresa->nome = $request->input('nome');
        $empresa->nome_contato = $request->input('nome_contato');
        $empresa->telefone = $request->input('telefone');
        $empresa->cnpj = $request->input('cnpj');
        $empresa->cep = $request->input('cep');
        $empresa->endereco = $request->input('endereco');
        $empresa->numero = $request->input('numero');
        $empresa->cidade = $request->input('cidade');
        $empresa->estado = $request->input('estado');
        $empresa->email = $request->input('email');
        $empresa->status = $request->input('status');

        if ($request->filled('status') == false) {
            $empresa->status = 'inativo';
        } else {
            $empresa->status = 'ativo';
        }

        if ($request->filled('complemento')) {
            $empresa->complemento = $request->input('complemento');
        }

        $empresa->save();

        return response()->json([
            'message' => 'Empresa cadastrada com sucesso!'
        ], 200);
    }

    public function empresa(Request $require)
    {
        $empresa = Auth::user()->empresa;
        return view('empresas.edit', compact('empresa'));
    }

    public function edit(Request $require, $id)
    {
        $empresa = Empresas::find($id);
        return view('empresas.edit', compact('empresa'));
    }

    public function show(Request $require, $id)
    {
        $empresa = Empresas::find($id);
        return response()->json($empresa);
    }

    public function update(Request $request, $id)
    {

        $request->except('_token');
        $request->validate([
            'nome' => 'required|string|max:255',
            'nome_contato' => 'required|string|max:255',
            'telefone' => 'required|string|min:11',
            'cep' => 'required',
            'endereco' => 'required',
            'numero' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
        ]);

        $empresa = Empresas::find($id);
        $empresa->nome = $request->input('nome');
        $empresa->nome_contato = $request->input('nome_contato');
        $empresa->telefone = $request->input('telefone');
        $empresa->cep = $request->input('cep');
        $empresa->endereco = $request->input('endereco');
        $empresa->numero = $request->input('numero');
        $empresa->cidade = $request->input('cidade');
        $empresa->estado = $request->input('estado');
        $empresa->status = $request->input('status');

        if ($request->filled('complemento')) {
            $empresa->complemento = $request->input('complemento');
        }

        if ($request->filled('status') == false) {
            $empresa->status = 'inativo';
        } else {
            $empresa->status = 'ativo';
        }

        if ($empresa->cnpj != $request->input('cnpj')) {
            $request->validate([
                'cnpj' => 'required|string|unique:empresas',
            ]);
            $empresa->cnpj = $request->input('cnpj');
        }

        if ($empresa->email != $request->input('email')) {
            $request->validate([
                'email' => 'required|email|unique:empresas',
            ]);
            $empresa->email = $request->input('email');
        }

        $empresa->save();

        return response()->json([
            'message' => 'Empresa atualizada com sucesso!'
        ], 200);

        // return response()->json([
        //     'status'=>'ok',
        //     'url'=>route('app.empresas.index'),
        // ]);
    }

    public function destroy(string $id)
    {
        $empresa = Empresas::find($id);

        if (!$empresa) {
            return response()->json(['message' => 'Empresa não encontrada.'], 404);
        }

        $empresa->delete();
        return response()->json(['message' => 'Empresa excluída com sucesso!']);
    }

    public function toggleStatus($id)
    {
        $empresa = Empresas::find($id);

        if (!$empresa) {
            return response()->json(['message' => 'Empresa não encontrada'], 404);
        }
        $empresa->status = $empresa->status == 'ativo' ? 'inativo' : 'ativo';
        $empresa->save();

        return response()->json([
            'status' => $empresa->status === 'ativo' ? 'ativo' : 'inativo',
            'message' => 'Status alterado com sucesso'
        ]);
    }
}
