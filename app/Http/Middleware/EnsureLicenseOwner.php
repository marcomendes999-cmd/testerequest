<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureLicenseOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless((int) $request->user()?->id === 1, 403, 'A gestão de licenças está reservada ao utilizador autorizado.');

        return $next($request);
    }
}
