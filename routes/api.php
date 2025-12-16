<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\CategoriaApiController;
use App\Http\Controllers\Api\GrupoApiController;
use App\Http\Controllers\Api\PagamentoApiController;
use App\Http\Controllers\Api\PagamentoItemApiController;
use App\Http\Controllers\Api\ProdutoApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/login', [ApiController::class, 'login'])->name('login-api');


Route::name('api.')->middleware('token', 'throttle:500,1')->group(function () {

Route::name('pagamentos.')->prefix('pagamentos')->controller(
    PagamentoApiController::class
)->group(
    function () {
        Route::get('/lista', 'lista')->name('lista');
        Route::get('/gateways', 'gateways')->name('gateways');
        Route::get('/formas-pagamento/{id}', 'formasPagamento')->name('formasPagamento');
        Route::get('/detalhes/{id}', 'detalhes')->name('detalhes');
        Route::post('/store', 'store')->name('store');
    }
);

Route::name('itens.')->prefix('itens')->controller(
    PagamentoItemApiController::class
)->group(
    function () {
        Route::get('/lista', 'lista')->name('lista');
        Route::get('/detalhes/{id}', 'detalhes')->name('detalhes');
        Route::post('/store', 'store')->name('store');
    }
);

Route::name('grupos.')->prefix('grupos')->controller(
    GrupoApiController::class
)->group(
    function () {
        Route::get('/lista', 'lista')->name('lista');
        Route::get('/detalhes/{id}', 'detalhes')->name('detalhes');
    }
);


Route::name('categorias.')->prefix('categorias')->controller(
    CategoriaApiController::class
)->group(
    function () {
        Route::get('/lista', 'lista')->name('lista');
        Route::get('/detalhes/{id}', 'detalhes')->name('detalhes');
    }
);

Route::name('produtos.')->prefix('produtos')->controller(
    ProdutoApiController::class
)->group(
    function () {
        Route::get('/lista/{id?}', 'lista')->name('lista');
        Route::get('/detalhes/{id}', 'detalhes')->name('detalhes');
    }
);




    Route::prefix('events')->group(function () {
        Route::get('/list-products/{id}', [ApiController::class, 'getProductsByGroup'])->name('getProductsByGroup');
        Route::get('/list-groups', [ApiController::class, 'getAllGroupsByCompany'])->name('getAllGroupsByCompany');
        Route::post('/createPayment', [ApiController::class, 'createPayment'])->name('createPayment');
    });


});
