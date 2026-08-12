<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\License;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\Urgency;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Exibe indicadores calculados a partir dos pedidos visíveis ao utilizador.
     */
    public function index()
    {
        $user = Auth::user();
        $activeLicense = License::active()->orderByDesc('expires_at')->first();
        $activeSessionCount = DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', now()->subMinutes(config('session.lifetime'))->getTimestamp())
            ->distinct()
            ->count('user_id');
        $tickets = Ticket::query();

        // A equipa de suporte vê a operação completa; os restantes utilizadores
        // veem apenas os pedidos que criaram.
        if (!$user->hasAnyRole(['admin', 'tecnico'])) {
            $tickets->where('userid', $user->id);
        }

        $closedStatusIds = Status::query()
            ->whereIn('name', ['Fechado', 'Resolvido'])
            ->pluck('id');

        $urgentIds = Urgency::query()
            ->whereIn('name', ['Alta', 'Crítica'])
            ->pluck('id');

        $activeTickets = (clone $tickets)
            ->whereNotIn('idestado', $closedStatusIds)
            ->count();
        $resolvedTickets = (clone $tickets)
            ->whereIn('idestado', $closedStatusIds)
            ->count();
        $urgentTickets = (clone $tickets)
            ->whereNotIn('idestado', $closedStatusIds)
            ->whereIn('idurgencia', $urgentIds)
            ->count();
        $overdueTickets = (clone $tickets)
            ->whereNotIn('idestado', $closedStatusIds)
            ->whereNotNull('prazo')
            ->whereDate('prazo', '<', today())
            ->count();

        $startOfMonth = now()->startOfMonth();
        $createdThisMonth = (clone $tickets)
            ->where('created_at', '>=', $startOfMonth)
            ->count();
        $resolvedThisMonth = (clone $tickets)
            ->whereIn('idestado', $closedStatusIds)
            ->where('updated_at', '>=', $startOfMonth)
            ->count();

        $ticketsWithDeadline = (clone $tickets)->whereNotNull('prazo');
        $deadlineCount = $ticketsWithDeadline->count();
        $withinDeadlineCount = (clone $tickets)
            ->whereNotNull('prazo')
            ->where(function ($query) use ($closedStatusIds) {
                $query->where(function ($query) use ($closedStatusIds) {
                    $query->whereIn('idestado', $closedStatusIds)
                        ->whereNotNull('datafecho')
                        ->whereColumn('datafecho', '<=', 'prazo');
                })->orWhere(function ($query) use ($closedStatusIds) {
                    $query->whereNotIn('idestado', $closedStatusIds)
                        ->whereDate('prazo', '>=', today());
                });
            })
            ->count();
        $deadlineCompliance = $deadlineCount > 0
            ? (int) round(($withinDeadlineCount / $deadlineCount) * 100)
            : 0;

        $months = collect(range(5, 0))->map(fn (int $offset) => now()->startOfMonth()->subMonths($offset));
        $firstMonth = $months->first()->copy();
        $monthTotals = (clone $tickets)
            ->where('created_at', '>=', $firstMonth)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->pluck('total', 'month');
        $resolvedMonthTotals = (clone $tickets)
            ->whereIn('idestado', $closedStatusIds)
            ->where('updated_at', '>=', $firstMonth)
            ->selectRaw("DATE_FORMAT(updated_at, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->pluck('total', 'month');

        $chartLabels = $months->map(fn ($month) => $month->translatedFormat('M'))->values();
        $createdSeries = $months->map(fn ($month) => (int) ($monthTotals[$month->format('Y-m')] ?? 0))->values();
        $resolvedSeries = $months->map(fn ($month) => (int) ($resolvedMonthTotals[$month->format('Y-m')] ?? 0))->values();

        $statusDistribution = Status::query()
            ->orderBy('ordem')
            ->get()
            ->map(fn (Status $status) => [
                'name' => $status->name,
                'total' => (clone $tickets)->where('idestado', $status->id)->count(),
            ]);

        $categories = (clone $tickets)
            ->selectRaw('idcategoria, COUNT(*) as total')
            ->with('categoria:id,name')
            ->groupBy('idcategoria')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        $urgencies = (clone $tickets)
            ->selectRaw('idurgencia, COUNT(*) as total')
            ->with('urgencia:id,name')
            ->groupBy('idurgencia')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $ticketIds = (clone $tickets)->select('id');
        $recentMessages = Message::query()
            ->with(['user:id,name', 'ticket:id,titulo'])
            ->whereIn('ticket_id', $ticketIds)
            ->latest()
            ->limit(5)
            ->get();
        $recentTickets = (clone $tickets)->latest()->limit(6)->get();

        return view('dashboard', compact(
            'activeTickets',
            'resolvedTickets',
            'urgentTickets',
            'overdueTickets',
            'createdThisMonth',
            'resolvedThisMonth',
            'deadlineCount',
            'withinDeadlineCount',
            'deadlineCompliance',
            'chartLabels',
            'createdSeries',
            'resolvedSeries',
            'statusDistribution',
            'categories',
            'urgencies',
            'recentMessages',
            'recentTickets',
            'activeLicense',
            'activeSessionCount'
        ));
    }
}
