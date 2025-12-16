<?php

use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\DCATaxasController;
use App\Http\Controllers\EmpresasController;
use App\Http\Controllers\FluxoDeCaixaCategoriasController;
use App\Http\Controllers\FluxoDeCaixaContasController;
use App\Http\Controllers\FluxoDeCaixaContasPagarController;
use App\Http\Controllers\FluxoDeCaixaContasReceberController;
use App\Http\Controllers\FluxoDeCaixaLancamentosController;
use App\Http\Controllers\FormasPagamentoController;
use App\Http\Controllers\GatewaysController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\PagamentosController;
use App\Http\Controllers\ProdutosController;
use App\Http\Controllers\UsuarioController;
use App\Models\FluxoDeCaixaContasReceber;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Auth::routes();
Route::name('app.')->middleware(['auth'])->group(function () {

    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::name('dash.')->prefix('dash')->controller(HomeController::class)->group(function () {
        Route::get('/index', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
        Route::get('/ultimosPagamentos/{id?}', [App\Http\Controllers\HomeController::class, 'ultimosPagamentos'])->name('ultimosPagamentos');
        Route::get('/graficoCategorias/{id?}', [App\Http\Controllers\HomeController::class, 'graficoCategorias'])->name('graficoCategorias');
        Route::get('/graficoProdutos/{id?}', [App\Http\Controllers\HomeController::class, 'graficoProdutos'])->name('graficoProdutos');
        Route::get('/graficoPagamentos/{id?}', [App\Http\Controllers\HomeController::class, 'graficoPagamentos'])->name('graficoPagamentos');
        Route::get('/graficoValores/{id?}', [App\Http\Controllers\HomeController::class, 'graficoValores'])->name('graficoValores');
        Route::get('/buscar/{id?}', [App\Http\Controllers\HomeController::class, 'buscar'])->name('buscar');
    });
    Route::name('empresas.')->prefix('empresas')->controller(EmpresasController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/empresa', 'empresa')->name('empresa');

        Route::get('/get-itens', 'getItens')->name('getItens');
        Route::get('/new', 'new')->name('new');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::post('/toggle-status/{id}', 'toggleStatus')->name('toggleStatus');
        Route::get('/destroy/{id}', 'destroy')->name('destroy');
    });
    Route::name('formas_pagamentos.')->prefix('formas_pagamentos')->controller(FormasPagamentoController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/new', 'new')->name('new');
        Route::post('/store', 'store')->name('store');
        Route::post('/storeBandeira', 'storeBandeira')->name('storeBandeira');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::post('/uploadProfile', 'uploadProfile')->name('uploadProfile');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

    Route::name('gateways.')->prefix('gateways')->controller(GatewaysController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

    Route::name('dca_taxas.')->prefix('dca_taxas')->controller(DCATaxasController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

    Route::name('usuarios.')->prefix('usuarios')->controller(UsuarioController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/get-itens', 'getItens')->name('getItens');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/destroy/{id}', 'destroy')->name('destroy');
        Route::post('/toggle-status/{id}', 'toggleStatus')->name('toggleStatus');
    });

    Route::name('grupos.')->prefix('grupos')->controller(GrupoController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/get-itens', 'getItens')->name('getItens');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/destroy/{id}', 'destroy')->name('destroy');
        Route::post('/toggle-status/{id}', 'toggleStatus')->name('toggleStatus');
    });

    Route::name('categorias.')->prefix('categorias')->controller(CategoriasController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/get-itens', 'getItens')->name('getItens');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/destroy/{id}', 'destroy')->name('destroy');
        Route::post('/toggle-status/{id}', 'toggleStatus')->name('toggleStatus');
    });

    Route::name('produtos.')->prefix('produtos')->controller(ProdutosController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/get-itens', 'getItens')->name('getItens');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/destroy/{id}', 'destroy')->name('destroy');
        Route::post('/toggle-status/{id}', 'toggleStatus')->name('toggleStatus');
        Route::get('/select_grupo/{grupoId?}', 'selectGrupo')->name('selectGrupo');
    });

    Route::name('pagamentos.')->prefix('pagamentos')->controller(PagamentosController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/get-itens', 'getItens')->name('getItens');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::get('/visualizar/{id}', 'visualizar')->name('visualizar');
        Route::get('/detalhes/{id}', 'detalhes')->name('detalhes');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/destroy/{id}', 'destroy')->name('destroy');
        Route::post('/toggle-status/{id}', 'toggleStatus')->name('toggleStatus');
        Route::get('/select_grupo/{grupoId}', 'selectGrupo')->name('selectGrupo');

        Route::get('/lista_geral', 'geral')->name('geral');
    });

    Route::prefix('fluxo-caixa')->name('fluxo-caixa.')->group(function () {

        Route::name('contas.')->prefix('contas')->controller(FluxoDeCaixaContasController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/get-itens', 'getItens')->name('getItens');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/update/{id}', 'update')->name('update');
            Route::get('/destroy/{id}', 'destroy')->name('destroy');
            Route::post('/toggle-status/{id}', 'toggleStatus')->name('toggleStatus');
        });

        Route::name('categorias.')->prefix('categorias')->controller(FluxoDeCaixaCategoriasController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/get-itens', 'getItens')->name('getItens');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/update/{id}', 'update')->name('update');
            Route::get('/destroy/{id}', 'destroy')->name('destroy');
            Route::post('/toggle-status/{id}', 'toggleStatus')->name('toggleStatus');
        });

        Route::name('contas-receber.')->prefix('contas-receber')->controller(FluxoDeCaixaContasReceberController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/get-itens', 'getItens')->name('getItens');
            Route::post('/store', 'store')->name('store');
            Route::put('/update/{id}', 'update')->name('update');
            Route::get('/create', 'create')->name('create');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::get('/destroy/{id}', 'destroy')->name('destroy');
            Route::post('/toggle-status/{id}', 'toggleStatus')->name('toggleStatus');
            Route::get('/toggle-payment/{id}', 'togglePayment')->name('togglePayment');
        });

        Route::name('contas-pagar.')->prefix('contas-pagar')->controller(FluxoDeCaixaContasPagarController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/get-itens', 'getItens')->name('getItens');
            Route::post('/store', 'store')->name('store');
            Route::put('/update/{id}', 'update')->name('update');
            Route::get('/create', 'create')->name('create');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::get('/destroy/{id}', 'destroy')->name('destroy');
            Route::post('/toggle-status/{id}', 'toggleStatus')->name('toggleStatus');
            Route::get('/toggle-payment/{id}', 'togglePayment')->name('togglePayment');
        });

        Route::name('lancamentos.')->prefix('lancamentos')->controller(FluxoDeCaixaLancamentosController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/get-itens', 'getItens')->name('getItens');
        });
    });
});
