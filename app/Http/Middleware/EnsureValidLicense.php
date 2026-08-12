<?php

namespace App\Http\Middleware;

use App\Models\License;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureValidLicense
{
    public function handle(Request $request, Closure $next): Response
    {
        // O utilizador proprietário mantém sempre acesso para conseguir gerir
        // ou corrigir licenças, mesmo quando não existe uma licença válida.
        if ((int) $request->user()?->id === 1) {
            return $next($request);
        }

        if (! License::active()->exists()) {
            abort(403, 'Não existe uma licença ativa para utilizar esta funcionalidade.');
        }

        return $next($request);
    }
}
