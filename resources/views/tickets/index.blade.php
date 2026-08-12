@extends('layouts.app')

@section('title', 'Lista de Pedidos')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-semibold text-gray-700">Lista de Pedidos</h2>
    </div>

    @if(session('success'))
        <div class="rounded-lg border-l-4 border-green-500 bg-green-50 p-4 text-green-700">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('tickets.index') }}" class="flex flex-wrap items-end gap-4 rounded-lg bg-white p-4 shadow-md">
        <div class="flex flex-col">
            <label for="grupo_id" class="mb-1 text-sm font-medium text-gray-600">Grupo</label>
            <select name="grupo_id" id="grupo_id" class="rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Todos</option>
                @foreach($grupos as $grupo)
                    <option value="{{ $grupo->id }}" @selected((string) request('grupo_id') === (string) $grupo->id)>{{ $grupo->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col">
            <label for="estado" class="mb-1 text-sm font-medium text-gray-600">Estado</label>
            <select name="estado" id="estado" class="rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Todos</option>
                @foreach($estados as $estado)
                    <option value="{{ $estado->id }}" @selected((string) request('estado') === (string) $estado->id)>{{ $estado->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col">
            <label for="aprovado" class="mb-1 text-sm font-medium text-gray-600">Aprovação</label>
            <select name="aprovado" id="aprovado" class="rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Todos</option>
                <option value="2" @selected(request('aprovado') === '2')>Aprovado</option>
                <option value="1" @selected(request('aprovado') === '1')>Não aprovado</option>
            </select>
        </div>

        <div class="flex flex-col">
            <label for="search" class="mb-1 text-sm font-medium text-gray-600">Título</label>
            <input type="search" name="search" id="search" value="{{ request('search') }}" placeholder="Pesquisar pedido" class="rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div class="flex flex-col">
            <label for="submetido_por" class="mb-1 text-sm font-medium text-gray-600">Submetido por</label>
            <input type="search" name="submetido_por" id="submetido_por" value="{{ request('submetido_por') }}" placeholder="Nome do utilizador" class="rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div class="flex gap-2">
            <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 font-medium text-white transition hover:bg-indigo-700">Filtrar</button>
            <a href="{{ route('tickets.index') }}" class="rounded-md border border-gray-300 px-4 py-2 font-medium text-gray-700 transition hover:bg-gray-50">Limpar</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-lg bg-white shadow-md">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                    <tr>
                        <th class="px-6 py-3">Código</th>
                        <th class="px-6 py-3">Título</th>
                        <th class="px-6 py-3">Grupo</th>
                        <th class="px-6 py-3">Submetido por</th>
                        <th class="px-6 py-3">Estado</th>
                        <th class="px-6 py-3">Prazo</th>
                        <th class="px-6 py-3">Aprovação</th>
                        <th class="px-6 py-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($tickets as $ticket)
                        <tr class="transition hover:bg-gray-50">
                            <td class="whitespace-nowrap px-6 py-4 font-semibold text-indigo-700">{{ $ticket->code ?? '—' }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $ticket->titulo }}</td>
                            <td class="px-6 py-4">{{ $ticket->grupo?->name ?? '—' }}</td>
                            <td class="px-6 py-4">{{ $ticket->user?->name ?? '—' }}</td>
                            <td class="px-6 py-4">{{ $ticket->estado?->name ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4">{{ $ticket->prazo?->format('d/m/Y') ?? '—' }}</td>
                            <td class="px-6 py-4">
                                @if((int) $ticket->aprovado === 2)
                                    <span class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-800">Aprovado</span>
                                @else
                                    <span class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-800">Não aprovado</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <a href="{{ route('tickets.show', $ticket) }}" class="font-medium text-indigo-600 hover:text-indigo-800">Ver detalhe</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-500">Não existem pedidos com os filtros selecionados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>{{ $tickets->links() }}</div>
</div>
@endsection
