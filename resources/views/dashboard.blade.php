@extends('layouts.app')

@section('content')
    <div class="py-8 md:py-12 bg-gray-50 dark:bg-gray-900 min-h-screen text-gray-900 dark:text-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Boas-vindas + Botão Novo Pedido -->
            <div class="mb-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold">
                        Olá, {{ Auth::user()->name }} 👋
                    </h1>
                    <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">
                        Panorama completo dos pedidos e atividade recente.
                    </p>
                </div>

                <a href="{{ route('tickets.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <i class="fas fa-plus mr-2"></i>
                    Novo Pedido
                </a>
            </div>

            <!-- Cartões de métricas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-shadow">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Pedidos</p>
                    <p class="text-4xl font-bold mt-2">312</p>
                    <p class="mt-2 text-sm text-green-600 dark:text-green-400">+27 este mês</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-shadow">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Em Aberto</p>
                    <p class="text-4xl font-bold text-amber-600 dark:text-amber-400 mt-2">58</p>
                    <p class="mt-2 text-sm text-amber-600 dark:text-amber-400">14 com >5 dias</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-shadow">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Urgentes</p>
                    <p class="text-4xl font-bold text-red-600 dark:text-red-400 mt-2">11</p>
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">Prioridade imediata</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-shadow">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Resolvidos</p>
                    <p class="text-4xl font-bold text-emerald-600 dark:text-emerald-400 mt-2">143</p>
                    <p class="mt-2 text-sm text-emerald-600 dark:text-emerald-400">+31 este mês</p>
                </div>
            </div>

            <!-- Prioridade 1: SLA + Top Solicitantes / Departamentos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">

                <!-- Widget SLA / Tempo Médio -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-shadow">
                    <h3 class="text-xl font-semibold mb-6 flex items-center gap-3">
                        <i class="fas fa-stopwatch text-teal-600 dark:text-teal-400"></i>
                        Desempenho SLA
                    </h3>

                    <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                        <!-- Gauge Radial (SLA %) -->
                        <div class="relative w-40 h-40">
                            <div id="slaGauge" class="w-full h-full"></div>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-4xl font-bold text-teal-600 dark:text-teal-400">92%</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400 mt-1">SLA Cumprido</span>
                            </div>
                        </div>

                        <!-- Info complementar -->
                        <div class="flex-1 space-y-4 text-center md:text-left">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tempo Médio de Resolução</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">4.2 h</p>
                                <p class="text-sm text-green-600 dark:text-green-400">↓ 18 min vs mês passado</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Pedidos Fora de Prazo</p>
                                <p class="text-2xl font-bold text-red-600 dark:text-red-400">7</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Solicitantes / Departamentos -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-shadow">
                    <h3 class="text-xl font-semibold mb-6 flex items-center gap-3">
                        <i class="fas fa-trophy text-amber-600 dark:text-amber-400"></i>
                        Top Solicitantes & Departamentos
                    </h3>

                    <div class="space-y-5">
                        <!-- Top 5 Solicitantes -->
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-wide">Utilizadores Mais Ativos</p>
                            <ul class="space-y-3">
                                <li class="flex items-center gap-4">
                                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150" alt="Ana" class="w-10 h-10 rounded-full object-cover">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900 dark:text-white">Ana Costa</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">RH • 42 pedidos</p>
                                    </div>
                                    <span class="text-sm font-semibold text-amber-600 dark:text-amber-400">1º</span>
                                </li>
                                <li class="flex items-center gap-4">
                                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150" alt="Pedro" class="w-10 h-10 rounded-full object-cover">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900 dark:text-white">Pedro Almeida</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">TI • 35 pedidos</p>
                                    </div>
                                    <span class="text-sm font-semibold text-amber-600 dark:text-amber-400">2º</span>
                                </li>
                                <li class="flex items-center gap-4">
                                    <img src="https://images.unsplash.com/photo-1552058544-f2b08422138a?w=150" alt="Sofia" class="w-10 h-10 rounded-full object-cover">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900 dark:text-white">Sofia Ribeiro</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Compras • 28 pedidos</p>
                                    </div>
                                    <span class="text-sm font-semibold text-amber-600 dark:text-amber-400">3º</span>
                                </li>
                                <!-- Podes adicionar mais 2 se quiseres top 5 completo -->
                            </ul>
                        </div>

                        <!-- Top Departamentos (simples lista) -->
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-wide">Departamentos Mais Ativos</p>
                            <div class="flex flex-wrap gap-3">
                                <span class="inline-flex px-4 py-2 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300">
                                    TI (98)
                                </span>
                                <span class="inline-flex px-4 py-2 rounded-full text-sm font-medium bg-purple-100 dark:bg-purple-900/40 text-purple-800 dark:text-purple-300">
                                    RH (76)
                                </span>
                                <span class="inline-flex px-4 py-2 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300">
                                    Manutenção (54)
                                </span>
                                <span class="inline-flex px-4 py-2 rounded-full text-sm font-medium bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300">
                                    Compras (41)
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-xl font-semibold mb-6 flex items-center gap-3">
                        <i class="fas fa-chart-line text-indigo-600 dark:text-indigo-400"></i>
                        Pedidos ao Longo do Tempo
                    </h3>
                    <div id="pedidosLineChart" class="h-80"></div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-xl font-semibold mb-6 flex items-center gap-3">
                        <i class="fas fa-chart-pie text-purple-600 dark:text-purple-400"></i>
                        Distribuição por Estado
                    </h3>
                    <div id="pedidosPieChart" class="h-80"></div>
                </div>
            </div>

            <!-- Mensagens Não Lidas + Atividade Recente -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Mensagens Não Lidas -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                            <i class="fas fa-envelope text-indigo-600 dark:text-indigo-400"></i>
                            Mensagens Não Lidas (14)
                        </h2>
                        <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium text-sm">
                            Ver todas →
                        </a>
                    </div>

                    <!-- Mensagem 1 -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:border-indigo-200 dark:hover:border-indigo-600 transition-all duration-200">
                        <div class="flex items-start gap-5">
                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150" alt="Ana" class="w-14 h-14 rounded-full object-cover ring-2 ring-indigo-100 dark:ring-indigo-900">
                            <div class="flex-1">
                                <div class="flex justify-between mb-2">
                                    <p class="font-semibold text-gray-900 dark:text-white">Ana Costa – RH</p>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">há 38 min</span>
                                </div>
                                <p class="text-gray-700 dark:text-gray-300 line-clamp-2">
                                    Boa tarde, já enviei a documentação para ajuste salarial. Preciso de confirmação urgente para submeter ao conselho...
                                </p>
                                <div class="mt-3">
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/40 text-indigo-800 dark:text-indigo-300">
                                        3 novas mensagens
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mensagem 2 -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:border-indigo-200 dark:hover:border-indigo-600 transition-all duration-200">
                        <div class="flex items-start gap-5">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150" alt="Pedro" class="w-14 h-14 rounded-full object-cover ring-2 ring-indigo-100 dark:ring-indigo-900">
                            <div class="flex-1">
                                <div class="flex justify-between mb-2">
                                    <p class="font-semibold text-gray-900 dark:text-white">Pedro Almeida – TI</p>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">há 2h</span>
                                </div>
                                <p class="text-gray-700 dark:text-gray-300 line-clamp-2">
                                    Marco, o utilizador ainda não consegue entrar no sistema. Já reiniciei a password mas continua com erro 403. Podes verificar?
                                </p>
                                <div class="mt-3">
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-300">
                                        Urgente – Bloqueio de acesso
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mensagem 3 -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:border-indigo-200 dark:hover:border-indigo-600 transition-all duration-200">
                        <div class="flex items-start gap-5">
                            <img src="https://images.unsplash.com/photo-1552058544-f2b08422138a?w=150" alt="Sofia" class="w-14 h-14 rounded-full object-cover ring-2 ring-indigo-100 dark:ring-indigo-900">
                            <div class="flex-1">
                                <div class="flex justify-between mb-2">
                                    <p class="font-semibold text-gray-900 dark:text-white">Sofia Ribeiro – Compras</p>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">há 4h</span>
                                </div>
                                <p class="text-gray-700 dark:text-gray-300 line-clamp-2">
                                    Olá Marco, o fornecedor confirmou entrega para amanhã mas precisamos aprovar a fatura proforma. Podes dar luz verde?
                                </p>
                                <div class="mt-3">
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300">
                                        1 nova mensagem
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mensagem 4 -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:border-indigo-200 dark:hover:border-indigo-600 transition-all duration-200">
                        <div class="flex items-start gap-5">
                            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150" alt="Miguel" class="w-14 h-14 rounded-full object-cover ring-2 ring-indigo-100 dark:ring-indigo-900">
                            <div class="flex-1">
                                <div class="flex justify-between mb-2">
                                    <p class="font-semibold text-gray-900 dark:text-white">Miguel Santos – Manutenção</p>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">há 6h</span>
                                </div>
                                <p class="text-gray-700 dark:text-gray-300 line-clamp-2">
                                    Atualização: a impressora do piso 3 já foi reparada, mas o toner está quase no fim. Recomendo encomenda urgente para evitar paragens.
                                </p>
                                <div class="mt-3">
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/40 text-indigo-800 dark:text-indigo-300">
                                        2 novas mensagens
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mensagem 5 -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:border-indigo-200 dark:hover:border-indigo-600 transition-all duration-200">
                        <div class="flex items-start gap-5">
                            <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=150" alt="Inês" class="w-14 h-14 rounded-full object-cover ring-2 ring-indigo-100 dark:ring-indigo-900">
                            <div class="flex-1">
                                <div class="flex justify-between mb-2">
                                    <p class="font-semibold text-gray-900 dark:text-white">Inês Ferreira – Marketing</p>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">há 9h</span>
                                </div>
                                <p class="text-gray-700 dark:text-gray-300 line-clamp-2">
                                    Marco, o material gráfico para o evento de dia 5 já chegou? Precisamos confirmar antes de fechar o layout final. Obrigada!
                                </p>
                                <div class="mt-3">
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/40 text-purple-800 dark:text-purple-300">
                                        4 novas mensagens
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Atividade Recente -->
                <div class="space-y-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <i class="fas fa-stream text-indigo-600 dark:text-indigo-400"></i>
                        Atividade Recente
                    </h2>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-6 space-y-5 text-sm">
                        <div class="flex items-start gap-4">
                            <i class="fas fa-check-circle text-emerald-500 text-xl mt-1"></i>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Pedido #198 concluído</p>
                                <p class="text-gray-600 dark:text-gray-400">por Ana Costa • há 1h</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <i class="fas fa-comment-dots text-indigo-500 text-xl mt-1"></i>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Nova resposta no pedido #229</p>
                                <p class="text-gray-600 dark:text-gray-400">por Pedro Almeida • há 2h</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <i class="fas fa-plus-circle text-purple-500 text-xl mt-1"></i>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Criaste novo pedido #234</p>
                                <p class="text-gray-600 dark:text-gray-400">Manutenção impressora piso 2 • há 4h</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <i class="fas fa-file-upload text-blue-500 text-xl mt-1"></i>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Anexo adicionado ao pedido #215</p>
                                <p class="text-gray-600 dark:text-gray-400">por Ana Costa • há 5h</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <i class="fas fa-exclamation-triangle text-red-500 text-xl mt-1"></i>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Pedido #241 marcado como urgente</p>
                                <p class="text-gray-600 dark:text-gray-400">por Sofia Ribeiro • há 7h</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <i class="fas fa-user-check text-teal-500 text-xl mt-1"></i>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Utilizador atribuído ao pedido #233</p>
                                <p class="text-gray-600 dark:text-gray-400">por Miguel Santos • há 11h</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Scripts ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // Gráfico de linha - Pedidos por mês
        var optionsLine = {
            chart: { type: 'area', height: 320, toolbar: { show: false }, zoom: { enabled: false } },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            series: [{
                name: 'Pedidos Criados',
                data: [31, 40, 28, 51, 42, 109, 100, 85, 120, 95, 140, 180]
            }, {
                name: 'Pedidos Resolvidos',
                data: [11, 32, 45, 32, 34, 52, 41, 60, 78, 95, 110, 145]
            }],
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.3 } },
            xaxis: { categories: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'] },
            colors: ['#6366f1', '#10b981'],
            tooltip: { x: { format: 'dd/MM' } },
            theme: { mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light' }
        };
        var chartLine = new ApexCharts(document.querySelector("#pedidosLineChart"), optionsLine);
        chartLine.render();

        // Gráfico donut - Distribuição por estado
        var optionsPie = {
            chart: { type: 'donut', height: 320 },
            series: [58, 143, 11, 100],
            labels: ['Em Aberto', 'Resolvidos', 'Urgentes', 'Cancelados/Pausados'],
            colors: ['#f59e0b', '#10b981', '#ef4444', '#6b7280'],
            plotOptions: { pie: { donut: { labels: { show: true, total: { show: true, label: 'Total', formatter: () => '312' } } } } },
            responsive: [{ breakpoint: 480, options: { chart: { width: 300 } } }],
            legend: { position: 'bottom' }
        };
        var chartPie = new ApexCharts(document.querySelector("#pedidosPieChart"), optionsPie);
        chartPie.render();


    </script>
    <!-- Gauge Radial para SLA -->
    <script>
        var optionsGauge = {
            chart: { type: 'radialBar', height: 160 },
            series: [92],
            plotOptions: {
                radialBar: {
                    hollow: { margin: 0, size: '70%' },
                    track: { background: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb' },
                    dataLabels: { show: false }
                }
            },
            colors: ['#14b8a6'],
            labels: ['SLA'],
            theme: { mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light' }
        };
        var chartGauge = new ApexCharts(document.querySelector("#slaGauge"), optionsGauge);
        chartGauge.render();
    </script>
@endsection