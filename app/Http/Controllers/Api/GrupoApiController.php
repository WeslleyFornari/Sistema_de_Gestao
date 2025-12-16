<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GrupoResource;
use App\Models\Grupos;
use Database\Seeders\GrupoSeeder;
use Illuminate\Http\Request;

class GrupoApiController extends Controller
{
    public function detalhes(Request $request,$id)
    {
        $empresa = $request->get('empresa');
        $grupo = Grupos::where('empresa_id', $empresa->id)->where('status','ativo')->where('id',$id)->first();

        return new GrupoResource($grupo);
    }

    /**
     * Method lista
     *
     * @return void
     */
    public function lista(Request $request) 
    {

        $empresa = $request->get('empresa');


        $grupos = Grupos::where('empresa_id', $empresa->id)->where('status','ativo')->get();

        return GrupoResource::collection($grupos);
    }
}
