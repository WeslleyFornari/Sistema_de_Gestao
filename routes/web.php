<?php

use App\Http\Controllers\BandeiraController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\ColaboradorController;
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
use App\Http\Controllers\GrupoEconomicoController;
use App\Http\Controllers\PagamentosController;
use App\Http\Controllers\ProdutosController;
use App\Http\Controllers\UnidadeController;
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
        
    });

    Route::name('grupo_economico.')->prefix('grupo_economico')->controller(GrupoEconomicoController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/empresa', 'empresa')->name('empresa');
        Route::get('/get-itens', 'getItens')->name('getItens');
        Route::get('/create', 'create')->name('create');
     Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/destroy/{id}', 'destroy')->name('destroy');
    });
  
    Route::name('bandeiras.')->prefix('bandeiras')->controller(BandeiraController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/get-itens', 'getItens')->name('getItens');
        Route::get('/create', 'create')->name('create');
         Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/destroy/{id}', 'destroy')->name('destroy');
        Route::post('/toggle-status/{id}', 'toggleStatus')->name('toggleStatus');
    });

     Route::name('unidades.')->prefix('unidades')->controller(UnidadeController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/get-itens', 'getItens')->name('getItens');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/destroy/{id}', 'destroy')->name('destroy');
        Route::post('/toggle-status/{id}', 'toggleStatus')->name('toggleStatus');
    });

    Route::name('colaboradores.')->prefix('colaboradores')->controller(ColaboradorController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/get-itens', 'getItens')->name('getItens');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/destroy/{id}', 'destroy')->name('destroy');
        Route::get('/get-bandeiras/{grupo_id}','getBandeiras')->name('getBandeiras');
        Route::get('/get-unidades/{bandeira_id}', 'getUnidades')->name('getUnidades');
      
    });
  
});

