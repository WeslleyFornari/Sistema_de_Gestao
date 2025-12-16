<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Empresa;
use App\Models\Empresas;
use Illuminate\Support\Facades\Mail;

class VerifyAuthorization
{
    public function handle($request, Closure $next)
    {
        if (!$request->header('Authorization')) {
            return response()->json([
                'message' => 'Token not found'
            ], 401);
        }

        $companyInfo = Empresas::where('token', $request->header('Authorization'))->first();

        if (!$companyInfo) {
            $message = "O token de autorização não foi encontrado.";

            Mail::raw($message, function ($message) {
                $message->to('master@dca.com.br')
                    ->subject('Tentativa de acesso com token não encontrado na base');
            });

            return response()->json([
                'message' => 'Token not found'
            ], 401);
        }
        $request->merge(['empresa' => $companyInfo]);

        return $next($request);
    }
}
