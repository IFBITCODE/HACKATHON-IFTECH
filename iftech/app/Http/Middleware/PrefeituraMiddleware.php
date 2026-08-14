<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PrefeituraMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return response()->json([
                'message' => 'Usuário não autenticado.'
            ], 401);
        }

        if ($request->user()->role !== 'prefeito') {
            return response()->json([
                'message' => 'Acesso negado. Apenas usuários da prefeitura podem acessar este recurso.'
            ], 403);
        }
            return $next($request);
    }
}
