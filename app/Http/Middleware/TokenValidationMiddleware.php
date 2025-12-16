<?php

namespace App\Http\Middleware;

use App\Models\Empresas;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TokenValidationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');


        if (!$token) {

            return response()->json(['error' => 'Acesso não encontrado'], 401);
        }else{
            $empresa =  Empresas::where('token',$token)->first();
            if(!$empresa){
                return response()->json([
                    "message" => "Error"
                ], 404);
            }
            $request->attributes->add(['empresa' => $empresa]);
        }

        // Continue o pipeline se o token estiver presente
        return $next($request);
    }
}
