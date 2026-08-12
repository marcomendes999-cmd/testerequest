@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8 text-gray-900 dark:bg-gray-900 dark:text-gray-100 md:py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold md:text-4xl">Olá, {{ Auth::user()->name }} 👋</h1>
                    <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">
                        Indicadores atualizados a partir dos pedidos registados no sistema.
                    </p>
                </div>
            </div>


            <div class="mb-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-md dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-sm font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Pedidos ativos</p>
                    <p class="mt-2 text-4xl font-bold">{{ $activeTickets }}</p>
                    <p class="mt-2 text-sm text-indigo-600 dark:text-indigo-400">{{ $createdThisMonth }} criados este mês</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-md dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-sm font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Fora de prazo</p>
                    <p class="mt-2 text-4xl font-bold text-red-600 dark:text-red-400">{{ $overdueTickets }}</p>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Pedidos ativos com prazo ultrapassado</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-md dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-sm font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Urgentes</p>
                    <p class="mt-2 text-4xl font-bold text-amber-600 dark:text-amber-400">{{ $urgentTickets }}</p>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Ativos com urgência alta ou crítica</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-md dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-sm font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Resolvidos</p>
                    <p class="mt-2 text-4xl font-bold text-emerald-600 dark:text-emerald-400">{{ $resolvedTickets }}</p>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $resolvedThisMonth }} atualizados este mês</p>
                </div>
            </div>

            <div class="mb-12 grid grid-cols-1 gap-8 lg:grid-cols-2">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-md dark:border-gray-700 dark:bg-gray-800">
                    <h2 class="mb-6 flex items-center gap-3 text-xl font-semibold"><i class="fas fa-stopwatch text-teal-600"></i>Cumprimento de prazos</h2>
                    <div class="flex flex-col items-center gap-8 md:flex-row">
                        <div class="relative h-40 w-40">
                            <div id="deadlineGauge" class="h-full w-full"></div>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-3xl font-bold text-teal-600">{{ $deadlineCompliance }}%</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">dentro do prazo</span>
                            </div>
                        </div>
                        <div class="space-y-3 text-center md:text-left">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Pedidos com prazo definido</p>
                            <p class="text-3xl font-bold">{{ $deadlineCount }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold text-emerald-600">{{ $withinDeadlineCount }}</span> dentro do prazo</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold text-red-600">{{ $overdueTickets }}</span> ativos fora de prazo</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-md dark:border-gray-700 dark:bg-gray-800">
                    <h2 class="mb-6 flex items-center gap-3 text-xl font-semibold"><i class="fas fa-layer-group text-purple-600"></i>Distribuição operacional</h2>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <p class="mb-3 text-sm font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Categorias mais frequentes</p>
                            <ul class="space-y-3">
                                @forelse($categories as $category)
                                    <li class="flex items-center justify-between gap-3"><span class="truncate">{{ $category->categoria?->name ?? 'Sem categoria' }}</span><span class="rounded-full bg-indigo-100 px-2.5 py-1 text-xs font-semibold text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300">{{ $category->total }}</span></li>
                                @empty
                                    <li class="text-sm text-gray-500">Ainda não existem pedidos.</li>
                                @endforelse
                            </ul>
                        </div>
                        <div>
                            <p class="mb-3 text-sm font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Por urgência</p>
                            <ul class="space-y-3">
                                @forelse($urgencies as $urgency)
                                    <li class="flex items-center justify-between gap-3"><span class="truncate">{{ $urgency->urgencia?->name ?? 'Sem urgência' }}</span><span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">{{ $urgency->total }}</span></li>
                                @empty
                                    <li class="text-sm text-gray-500">Ainda não existem pedidos.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-12 grid grid-cols-1 gap-8 lg:grid-cols-2">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-md dark:border-gray-700 dark:bg-gray-800">
                    <h2 class="mb-6 flex items-center gap-3 text-xl font-semibold"><i class="fas fa-chart-line text-indigo-600"></i>Pedidos nos últimos 6 meses</h2>
                    <div id="ticketsLineChart" class="h-80"></div>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-md dark:border-gray-700 dark:bg-gray-800">
                    <h2 class="mb-6 flex items-center gap-3 text-xl font-semibold"><i class="fas fa-chart-pie text-purple-600"></i>Distribuição por estado</h2>
                    <div id="ticketsPieChart" class="h-80"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <section class="space-y-4 lg:col-span-2">
                    <h2 class="flex items-center gap-3 text-2xl font-bold"><i class="fas fa-envelope text-indigo-600"></i>Mensagens recentes</h2>
                    @forelse($recentMessages as $message)
                        <a href="{{ route('tickets.show', $message->ticket_id) }}" class="block rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-indigo-300 hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
                            <div class="mb-2 flex items-center justify-between gap-4"><span class="font-semibold">{{ $message->user?->name ?? 'Utilizador' }}</span><span class="whitespace-nowrap text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</span></div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $message->ticket?->titulo ?? 'Pedido removido' }}</p>
                            <p class="mt-2 line-clamp-2 text-gray-700 dark:text-gray-300">{{ $message->content }}</p>
                        </a>
                    @empty
                        <div class="rounded-xl border border-dashed border-gray-300 p-6 text-center text-gray-500 dark:border-gray-600">Ainda não existem mensagens nos pedidos visíveis.</div>
                    @endforelse
                </section>

                <section class="space-y-4">
                    <h2 class="flex items-center gap-3 text-2xl font-bold"><i class="fas fa-stream text-indigo-600"></i>Pedidos recentes</h2>
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <ul class="space-y-4">
                            @forelse($recentTickets as $ticket)
                                <li class="border-b border-gray-100 pb-4 last:border-0 last:pb-0 dark:border-gray-700">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="font-medium hover:text-indigo-600">#{{ $ticket->id }} — {{ $ticket->titulo }}</a>
                                    <p class="mt-1 text-xs text-gray-500">Criado {{ $ticket->created_at->diffForHumans() }}</p>
                                </li>
                            @empty
                                <li class="text-sm text-gray-500">Ainda não existem pedidos.</li>
                            @endforelse
                        </ul>
                    </div>
                </section>
            </div>
            <section class="mt-12 mb-10 overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-violet-800 p-6 text-white shadow-xl md:p-8">
                <div class="flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-start gap-4">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/15 text-2xl ring-1 ring-inset ring-white/20">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium uppercase tracking-[0.18em] text-indigo-100">Licença da plataforma</p>
                            @if($activeLicense)
                                <h2 class="mt-1 text-2xl font-bold md:text-3xl">{{ $activeLicense->name }}</h2>
                                <p class="mt-2 text-sm text-indigo-100">Ativa até {{ $activeLicense->expires_at->format('d/m/Y') }}</p>
                            @else
                                <h2 class="mt-1 text-2xl font-bold md:text-3xl">Sem licença ativa</h2>
                                <p class="mt-2 text-sm text-indigo-100">Contacte o administrador da plataforma.</p>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 sm:gap-5">
                        <div class="rounded-xl bg-white/10 px-5 py-4 ring-1 ring-inset ring-white/15">
                            <p class="text-xs font-medium uppercase tracking-wide text-indigo-100">Utilizadores ativos</p>
                            <p class="mt-1 text-3xl font-bold">{{ $activeSessionCount }}</p>
                            <p class="mt-1 text-xs text-indigo-100">neste momento</p>
                        </div>
                        <div class="rounded-xl bg-white/10 px-5 py-4 ring-1 ring-inset ring-white/15">
                            <p class="text-xs font-medium uppercase tracking-wide text-indigo-100">Limite da licença</p>
                            <p class="mt-1 text-3xl font-bold">{{ $activeLicense?->max_users ?? '—' }}</p>
                            <p class="mt-1 text-xs text-indigo-100">sessões simultâneas</p>
                        </div>
                    </div>
                </div>

                @if($activeLicense)
                    <div class="mt-6">
                        <div class="mb-2 flex justify-between text-xs font-medium text-indigo-100">
                            <span>Ocupação da licença</span>
                            <span>{{ min(100, (int) round(($activeSessionCount / max(1, $activeLicense->max_users)) * 100)) }}%</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-indigo-950/30">
                            <div class="h-full rounded-full bg-gradient-to-r from-cyan-300 to-emerald-300" style="width: {{ min(100, (int) round(($activeSessionCount / max(1, $activeLicense->max_users)) * 100)) }}%"></div>
                        </div>
                    </div>
                @endif
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        const chartTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        const labels = @json($chartLabels);
        const statusLabels = @json($statusDistribution->pluck('name')->values());
        const statusTotals = @json($statusDistribution->pluck('total')->values());

        new ApexCharts(document.querySelector('#ticketsLineChart'), {
            chart: { type: 'area', height: 320, toolbar: { show: false }, zoom: { enabled: false } },
            series: [
                { name: 'Criados', data: @json($createdSeries) },
                { name: 'Resolvidos', data: @json($resolvedSeries) }
            ],
            xaxis: { categories: labels },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            colors: ['#6366f1', '#10b981'],
            theme: { mode: chartTheme },
            noData: { text: 'Sem dados para apresentar' }
        }).render();

        new ApexCharts(document.querySelector('#ticketsPieChart'), {
            chart: { type: 'donut', height: 320 },
            series: statusTotals,
            labels: statusLabels,
            colors: ['#f59e0b', '#6366f1', '#10b981', '#6b7280', '#ef4444'],
            legend: { position: 'bottom' },
            theme: { mode: chartTheme },
            noData: { text: 'Sem dados para apresentar' }
        }).render();

        new ApexCharts(document.querySelector('#deadlineGauge'), {
            chart: { type: 'radialBar', height: 160 },
            series: [{{ $deadlineCompliance }}],
            colors: ['#14b8a6'],
            plotOptions: { radialBar: { hollow: { size: '70%' }, dataLabels: { show: false } } },
            theme: { mode: chartTheme }
        }).render();
    </script>
@endsection
