<?php

namespace App\Http\Controllers;

use App\Models\Categorias;
use App\Models\Empresas;
use App\Models\FormasPagamentos;
use App\Models\Grupos;
use App\Models\Pagamentos;
use App\Models\PagamentosProdutos;
use App\Models\Produtos;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
       return view('dashboard.index');
           
    }

}
