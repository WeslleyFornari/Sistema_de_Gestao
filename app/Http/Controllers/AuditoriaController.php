<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use Illuminate\Http\Request;

class AuditoriaController extends Controller
{
   public function index(Request $request)
    {
        $query = Auditoria::query();

        if (auth()->user()->role !== 'admin') {
            $query->where('colaborador_id', auth()->user()->colaborador_id);
        }

        $auditorias = $query->orderBy('created_at', 'desc')->get();
        return view('auditorias.index', compact('auditorias'));
    }

    public function getItens(Request $request)
    {
        $query = Auditoria::with('colaborador');

        if (auth()->user()->role !== 'admin') {
            $query->where('colaborador_id', auth()->user()->colaborador_id);
        }

        $auditorias = $query->orderBy('created_at', 'desc')->paginate();

        if ($request->ajax()) {
            $html = view('auditorias._list-auditorias', compact('auditorias'))->render();
            $pagination = $auditorias->links('pagination::bootstrap-4')->render();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination
            ]);
        }

        return view('auditorias.index', compact('auditorias'));
    }
}
