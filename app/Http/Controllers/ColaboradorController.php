<?php

namespace App\Http\Controllers;

use App\Exports\ColaboradoresExport;
use App\Models\Bandeira;
use App\Models\Colaborador;
use App\Models\GrupoEconomico;
use App\Models\Unidade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class ColaboradorController extends Controller
{
    public function index(Request $request)
    {
        $colaboradores = Colaborador::orderBy('nome', 'asc')->paginate();
        $grupos = GrupoEconomico::orderBy('nome', 'asc')->get();
        $bandeiras = Bandeira::orderBy('nome', 'asc')->get();
        $unidades = Unidade::orderBy('nome_fantasia', 'asc')->get();

        return view('colaboradores.index', compact('colaboradores', 'grupos', 'bandeiras', 'unidades'));
    }

    public function getItens(Request $request)
    {
        $grupos = GrupoEconomico::orderBy('nome', 'asc')->get();
        $query = Colaborador::query();

        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }
        if ($request->filled('cpf')) {
            $query->where('cpf', 'like', '%' . $request->input('cpf') . '%');
        }

        if ($request->filled('unidade_id')) {
            $query->where('unidade_id', $request->input('unidade_id'));
        } elseif ($request->filled('bandeira_id')) {
            $query->whereHas('unidade', function ($q) use ($request) {
                $q->where('bandeira_id', $request->input('bandeira_id'));
            });
        } elseif ($request->filled('grupo_id')) {
            $query->whereHas('unidade.bandeira', function ($q) use ($request) {
                $q->where('grupo_economico_id', $request->input('grupo_id'));
            });
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

        return view('colaboradores.index', compact('colaboradores', 'grupos'));
    }

    public function create()
    {
        $unidades = Unidade::all();
        return view('colaboradores.create', compact('unidades'));
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');

        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'cpf' => 'required|string|max:14|unique:colaboradors,cpf',
            'unidade_id' => 'required'
        ]);

        $colaborador = Colaborador::create($data);
        $colaborador->refresh();
        User::create([
            'colaborador_id' => $colaborador->id,
            'email' => $colaborador->email,
            'password' => Hash::make('password'),
            'role' => $request->role

        ]);

        return response()->json([
            'message' => 'Colaborador criado com sucesso!'
        ], 200);
    }

    public function edit(Request $request, $id)
    {
        $colaborador = Colaborador::with('user')->find($id);
        $unidades = Unidade::all();
        return view('colaboradores.edit', compact('colaborador', 'unidades'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->except('_token');

        $request->validate([
            'nome' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'password.confirmed' => 'Senha não corresponde.'
        ]);

        $colaborador = Colaborador::with('user')->findOrFail($id);
        $colaborador->update([
            'nome' => $data['nome']
        ]);

        if ($request->filled('password')) {
            $colaborador->user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return response()->json([
            'message' => 'Colaborador atualizado com sucesso!'
        ], 200);
    }

    public function destroy(string $id)
    {
        $colaborador = Colaborador::with('user')->findOrFail($id);

        if ($colaborador->user) {
            $colaborador->user->delete();
        }

        $colaborador->delete();
        return response()->json(['message' => 'Colaborador excluído com sucesso!']);
    }

    public function export(Request $request)
    {
        $query = Colaborador::with('unidade');

        if ($request->filled('name')) {
            $query->where('nome', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }
        if ($request->filled('cpf')) {
            $query->where('cpf', 'like', '%' . $request->input('cpf') . '%');
        }

        if ($request->filled('unidade_id')) {
            $query->where('unidade_id', $request->input('unidade_id'));
        } elseif ($request->filled('bandeira_id')) {
            $query->whereHas('unidade', function ($q) use ($request) {
                $q->where('bandeira_id', $request->input('bandeira_id'));
            });
        } elseif ($request->filled('grupo_id')) {
            $query->whereHas('unidade.bandeira', function ($q) use ($request) {
                $q->where('grupo_economico_id', $request->input('grupo_id'));
            });
        }

        $query->orderBy('nome', 'asc');

        return \Maatwebsite\Excel\Facades\Excel::download(
            new ColaboradoresExport($query),
            'relatorio_colaboradores_' . date('d_m_Y') . '.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $query = Colaborador::query()->with('unidade');

        if ($request->filled('name')) {
            $query->where('nome', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }
        if ($request->filled('cpf')) {
            $query->where('cpf', 'like', '%' . $request->input('cpf') . '%');
        }

        if ($request->filled('unidade_id')) {
            $query->where('unidade_id', $request->input('unidade_id'));
        } elseif ($request->filled('bandeira_id')) {
            $query->whereHas('unidade', function ($q) use ($request) {
                $q->where('bandeira_id', $request->input('bandeira_id'));
            });
        } elseif ($request->filled('grupo_id')) {
            $query->whereHas('unidade.bandeira', function ($q) use ($request) {
                $q->where('grupo_economico_id', $request->input('grupo_id'));
            });
        }

        $query->orderBy('nome', 'asc');
        $colaboradores = $query->get();

        // Carrega a view e passa os dados
        $pdf = Pdf::loadView('colaboradores.report', compact('colaboradores'));

        // (Opcional) Configura o papel para A4
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('relatorio_colaboradores.pdf');
    }
}
