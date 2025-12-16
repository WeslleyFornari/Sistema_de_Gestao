<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriaResource;
use App\Models\Categorias;
use Database\Seeders\CategoriaSeeder;
use Illuminate\Http\Request;

class CategoriaApiController extends Controller
{
    public function detalhes($id)
    {
        $categoria = Categorias::find($id);

        return new CategoriaResource($categoria);
    }

    /**
     * Method lista
     *
     * @return void
     */
    public function lista(Request $request)
    {
        $companyInfo = $request->get('empresa');
        $categorias = Categorias::where('empresa_id', $companyInfo->id)->get();

        return CategoriaResource::collection($categorias);
    }
}
