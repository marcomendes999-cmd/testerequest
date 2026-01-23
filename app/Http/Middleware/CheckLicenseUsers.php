<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\License;
use Illuminate\Support\Facades\DB;  

class CheckLicenseUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $license = DB::table('licenses')->first();

        if ($license) {
            $activeUsers = DB::table('sessions')
                ->whereNotNull('user_id')
                ->distinct()
                ->count('user_id');

            // só bloqueia se ainda não estiver autenticado
            if ($activeUsers >= $license->max_users && !Auth::check()) {
                abort(403, 'Limite de utilizadores ativos atingido. Aguarde que alguém saia.');
            }
        }

        return $next($request);
    }

}
