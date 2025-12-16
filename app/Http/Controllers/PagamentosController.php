<?php

namespace App\Http\Controllers;

use App\Exports\PagamentoExport;
use App\Models\FormasPagamentos;
use App\Models\Grupos;
use App\Models\Pagamentos;
use App\Models\PagamentosProdutos;
use Illuminate\Http\Request;
use Auth;
use Maatwebsite\Excel\Facades\Excel;

class PagamentosController extends Controller
{
    public function index(Request $request)
    {
        $empresa_id = Auth::user()->empresa_id;
        $grupo_id = Auth::user()->grupo_id;
        $grupos = Grupos::where('empresa_id', $empresa_id)->select('descricao')->distinct()->get();
        $forma_pagto = FormasPagamentos::where('id_empresa', $empresa_id)->get();

        if (Auth::user()->role == 'admin') {
            
            $pagamentos = Pagamentos::where('empresa_id', $empresa_id)->paginate(10);
        }
        else
        {
            $pagamentos = Pagamentos::where('empresa_id', $empresa_id)->where('grupo_id', $grupo_id)->paginate(10);
        }

        return view('pagamentos.index', compact('pagamentos', 'grupos', 'forma_pagto'));
    }

    public function getItens(Request $request)
    {
        $data = $request->all();
        $grupo_id = Auth::user()->grupo_id;
        $empresa_id = Auth::user()->empresa_id;

        $query = Pagamentos::query();

        if (Auth::user()->role == 'admin') {
            
            $query->where('empresa_id', $empresa_id);
        }
        elseif(Auth::user()->role == 'grupo'){
            
            $query->where('empresa_id', $empresa_id)->where('grupo_id', $grupo_id);
        }
      
        if ($request->filled('numero')) {
            $query->where('numero', 'like', '%' . $request->input('numero') . '%');
        }

        if ($request->filled('cliente')) {
            $query->whereHas('usuario', function ($q) use ($request) {
                 $q->where('name', 'like', '%' . $request->input('cliente') . '%');
            });
        }

        if ($request->filled('forma')) {
            $query->whereHas('formaPagamento', function ($q) use ($request) {
                $q->where('tipo', $request->input('forma'));
            });
        }
        
        if ($request->filled('grupo')) {
            $query->whereHas('grupo', function ($q) use ($request) {
                $q->where('descricao', $request->input('grupo'));
            });
        }

        if ($request->filled('valor_min')) {

            $valorMin = floatval(str_replace(['R$', '.', ','], ['', '', '.'], $request->input('valor_min')));
            $query->where('valor_liquido', '>=', $valorMin);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $pagamentos = $query->orderBy('created_at', 'desc')->paginate();

        if ($request->ajax()) {
            $html = view('pagamentos._list-pagamentos', compact('pagamentos'))->render();
            $pagination = $pagamentos->links('pagination::bootstrap-4')->render();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination
            ]);
        }

        return view('pagamentos.index', compact('pagamentos'));
    }
// SHOW 2
    public function visualizar(Request $request, $id)
    {
        $pagamento = Pagamentos::find($id);
        $compras = PagamentosProdutos::where('id_pagamento', $pagamento->id)->get();
        $html = view('pagamentos.visualizar', compact('pagamento', 'compras'))->render();

        return response()->json([
            'html' => $html,
            'numero' => $pagamento->numero
        ]);  
    }

    public function detalhes(Request $request, $id)
    {
        $pagamento = Pagamentos::find($id);
        $compras = PagamentosProdutos::where('id_pagamento', $pagamento->id)->get();

        return view('pagamentos.detalhes', compact('pagamento', 'compras'));
    }

    public function geral()
    {
        // Excel::store(new PagamentoExport, 'lista_geral.xlsx', 'public');
        return Excel::download(new PagamentoExport, 'lista_geral.xlsx');

        return 'ok';
    }

    public function destroy(Request $request, $id)
    {
        $pagamento = Pagamentos::find($id);
        $pagamento->delete();

        return 'Deletado com sucesso';
    }
}
