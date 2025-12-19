<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class RelatoriosController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::query();

        if (auth()->user()->role !== 'admin') {
            $query->where('colaborador_id','!=', auth()->user()->colaborador_id);
        }

        $relatorios = $query->orderBy('created_at', 'desc')->get();
        return view('relatorios.index', compact('relatorios'));
    }

    public function getItens(Request $request)
    {
        $query = Report::with('colaborador');

        if (auth()->user()->role !== 'admin') {
            $query->where('colaborador_id', auth()->user()->colaborador_id);
        }

        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->input('name') . '%');
        }



        $relatorios = $query->orderBy('created_at', 'desc')->paginate();

        if ($request->ajax()) {
            $html = view('relatorios._list-relatorios', compact('relatorios'))->render();
            $pagination = $relatorios->links('pagination::bootstrap-4')->render();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination
            ]);
        }

        return view('relatorios.index', compact('relatorios'));
    }
}
