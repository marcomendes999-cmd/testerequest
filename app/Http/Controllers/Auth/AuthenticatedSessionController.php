<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\License;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $license = License::active()->orderByDesc('expires_at')->first();

        $request->authenticate();

        // Regenerate session id antes de gravar coisas na sessão
        $request->session()->regenerate();

        if (! $license && Auth::id() !== 1) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Não existe uma licença ativa para aceder à plataforma.',
            ]);
        }

        // 🔎 Verifica a licença (mantém a tua lógica)
        if ($license && Auth::id() !== 1) {
            $threshold = now()->subMinutes(config('session.lifetime'))->getTimestamp();

            $activeUserIds = DB::table('sessions')
                ->whereNotNull('user_id')
                ->where('last_activity', '>=', $threshold)
                ->pluck('user_id')
                ->unique()
                ->toArray();

            $activeCount = count($activeUserIds);
            $currentUserId = Auth::id();

            if ($activeCount >= $license->max_users && !in_array($currentUserId, $activeUserIds, true)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => 'Limite de Licenças ativos atingido.'
                ]);
            }
        }

        // Marca a sessão atual com o user_id (para a tabela sessions)
        DB::table('sessions')
            ->where('id', $request->session()->getId())
            ->update(['user_id' => Auth::id()]);

        // Insere o histórico de login e guarda o id do registo na sessão
        $loginHistoryId = DB::table('user_login_history')->insertGetId([
            'user_id'    => Auth::id(),
            'login_at'   => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Guarda o id do histórico na sessão para o atualizar ao logout
        $request->session()->put('login_history_id', $loginHistoryId);

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Captura o user_id antes do logout (Auth::id() fica null depois do logout)
        $userId = Auth::id();

        // tenta obter e remover (pull) o login_history_id que guardámos na sessão
        $loginHistoryId = $request->session()->pull('login_history_id');

        // Faz logout e invalida sessão
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Obtém o registo correto para atualizar (prioriza o id guardado)
        if ($loginHistoryId) {
            $lastLogin = DB::table('user_login_history')->where('id', $loginHistoryId)->first();
        } else {
            // fallback: procura o último registo aberto para este user (caso a sessão não tivesse o id)
            $lastLogin = null;
            if ($userId) {
                $lastLogin = DB::table('user_login_history')
                    ->where('user_id', $userId)
                    ->whereNull('logout_at')
                    ->orderByDesc('login_at')
                    ->first();
            }
        }

        if ($lastLogin) {
            $loginAt = Carbon::parse($lastLogin->login_at);
            $duration = now()->diffInSeconds($loginAt);

            DB::table('user_login_history')
                ->where('id', $lastLogin->id)
                ->update([
                    'logout_at' => now(),
                    'duration'  => $duration,
                    'updated_at' => now(),
                ]);
        }

        return redirect('/login');
    }
}
