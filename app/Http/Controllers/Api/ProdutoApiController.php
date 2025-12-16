<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProdutoResource;
use App\Models\Produtos;
use Illuminate\Http\Request;

class ProdutoApiController extends Controller
{
    public function detalhes($id)
    {
        $produto = Produtos::find($id);

        return new ProdutoResource($produto);
    }

    /**
     * Method lista
     *
     * @return void
     */
    public function lista(Request $request,$id = null)  {

        $empresa = $request->get('empresa');
        if($id){
            $produtos = Produtos::whereHas('categoria')->where('grupo_id', $id)->where('empresa_id', $empresa->id)->where('status','ativo')->get();
        }else{
            $produtos = Produtos::whereHas('categoria')->where('empresa_id', $empresa->id)->where('status','ativo')->get();
        }

     
        

        return ProdutoResource::collection($produtos);
    }
}