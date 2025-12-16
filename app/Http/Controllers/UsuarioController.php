<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioRequest;
use App\Models\Empresas;
use App\Models\Grupos;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id;

        if (Auth::user()->role == 'admin') {
            $usuarios = User::where('role', '!=' , 'master')->where('empresa_id', $empresa_id)->paginate();
        }
        elseif(Auth::user()->role == 'master')
        {
            $usuarios = User::paginate();
        }
        else {

            $usuarios = User::where('role', 'user')->where('empresa_id', $empresa_id)->where('grupo_id', $grupo_id)->paginate();
        }
      
        $usuarios = User::orderBy('name', 'asc')->paginate();

        return view('usuarios.index', compact('usuarios'));
    }

    public function getItens(Request $request)
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id;
        $query = User::query();

        if (Auth::user()->role == 'admin') {
            $query->where('role', '!=' , 'master')->where('empresa_id', $empresa_id);
        }
        elseif(Auth::user()->role == 'master')
        {
            $query = $query;
        }
        else { 

            $query = User::where('role', 'user')->where('empresa_id', $empresa_id)->where('grupo_id', $grupo_id);
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }

        $usuarios = $query->orderBy('name', 'asc')->paginate();

        if ($request->ajax()) {
            $html = view('usuarios._list-usuarios', compact('usuarios'))->render();
            $pagination = $usuarios->links('pagination::bootstrap-4')->render();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination
            ]);
        }

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $empresas = Empresas::all();
        $grupos = Grupos::where('empresa_id', Auth::user()->empresa_id)->get();
        
        return view('usuarios.create', compact('grupos', 'empresas'));
    }

    // SALVAR
    public function store(UsuarioRequest $request)
    {
        $data = $request->except('_token');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'empresa_id' => 'nullable|integer',
            'grupo_id' => 'nullable|integer',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.confirmed' => 'Senha não corresponde.'
        ]);

        User::create([

            'name' => $data['name'],
            'email' => $data['email'],
            'grupo_id' => $data['grupo_id'] ?? null,
            'empresa_id' => $data['empresa_id'] ?? null,
            'role' =>$data['role'],
            'password' => Hash::make($request->input('password')),
        ]);

        // Enviar email e senha para o novo usuario -> 'usuarios.bem-vindo.blade'
        $dataSender['sendMail'] = strtolower($data['email']);
        $dataSender['sendName'] = $data['name'];
        $dataSender['senha']  = $data['password'];
        $dataSender['url']  = 'https://dca.developers.com.br';

        Mail::send('usuarios.bem-vindo', $dataSender, function ($m) use ($dataSender) {
            $m->from('send@dvelopers.com.br', 'DCA - Devolução com Amor');
            $m->to($dataSender['sendMail'], $dataSender['sendName'])->subject('Bem Vindo ao DCA');
        });

        $usuarios = User::where('empresa_id', Auth::user()->empresa_id)->get();

        return response()->json([
            'message' => 'Usuario criado com sucesso!'
        ], 200);
    }

    // EDIT
    public function edit(Request $request, $id)
    {

        $empresas = Empresas::all();
        $usuario = User::find($id);
        $grupos = Grupos::where('empresa_id', Auth::user()->empresa_id)->get();

        return view('usuarios.edit', compact('usuario', 'grupos', 'empresas'));
    }

    //UPDATE   
    public function update(Request $request, $id)
    {
        $data = $request->except('_token');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'empresa_id' => 'nullable|integer',
            'grupo_id' => 'nullable|integer',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'password.confirmed' => 'Senha não corresponde.'
        ]);

        $user = User::findOrFail($id);

        $user->update([

           'name' => $data['name'],
            'email' => $data['email'],
            'grupo_id' => $data['grupo_id'] ?? null,
            'empresa_id' => $data['empresa_id'] ?? null,
            'role' =>$data['role'],
        ]);

        if (!empty($data['password']) && !empty($data['password_confirmation'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        return response()->json([
            'message' => 'Usuario atualizado com sucesso!'
        ], 200);
    }

    public function destroy(string $id)
    {
        $usuario = User::find($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuario não encontrado.'], 404);
        }

        $usuario->delete();
        return response()->json(['message' => 'Usuario excluído com sucesso!']);
    }

    public function toggleStatus($id)
    {
        $usuario = User::find($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuario não encontrada'], 404);
        }
        $usuario->status = $usuario->status == 'ativo' ? 'inativo' : 'ativo';
        $usuario->save();

        return response()->json([
            'status' => $usuario->status === 'ativo' ? 'ativo' : 'inativo',
            'message' => 'Status alterado com sucesso'
        ]);
    }
}
