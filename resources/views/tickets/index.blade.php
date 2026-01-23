@extends('layouts.app')

@section('title', 'Lista de Tickets')

@section('content')
<div 
    x-data="ticketFilter()" 
    x-init="init()" 
    class="space-y-6"
>
    <!-- Cabeçalho -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-700">Lista de Requests</h2>
        <a href="{{ route('tickets.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-200 shadow-md">
            <i class="fas fa-plus mr-2"></i> New Request
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white shadow-md rounded-lg p-4 flex flex-wrap gap-4 items-end">
        <div class="flex flex-col">
            <label class="text-sm font-medium text-gray-600 mb-1">Categoria</label>
            <select x-model="filters.categoria" @change="applyFilters" class="border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Todas</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col">
            <label class="text-sm font-medium text-gray-600 mb-1">Estado</label>
            <select x-model="filters.estado" @change="applyFilters" class="border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Todos</option>
                @foreach($estados as $est)
                    <option value="{{ $est->id }}">{{ $est->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col flex-1">
            <label class="text-sm font-medium text-gray-600 mb-1">Pesquisar</label>
            <input 
                type="text" 
                x-model="filters.search" 
                @input.debounce.500ms="applyFilters" 
                class="border-gray-300 rounded-md w-full focus:ring-indigo-500 focus:border-indigo-500" 
                placeholder="Pesquisar por título..."
            >
        </div>

        <button 
            @click="clearFilters" 
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
            Limpar
        </button>
    </div>

    <!-- Tabela -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">ID</th>
                    <th class="py-3 px-6 text-left">Título</th>
                    <th class="py-3 px-6 text-left">Descrição</th>
                    <th class="py-3 px-6 text-left">Estado</th>
                    <th class="py-3 px-6 text-left">Categoria</th>
                    <th class="py-3 px-6 text-left">Prazo</th>
                    <th class="py-3 px-6 text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach($tickets as $ticket)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 px-6">{{ $ticket->id }}</td>
                        <td class="py-3 px-6">{{ $ticket->titulo }}</td>
                        <td class="py-3 px-6">{{ Str::limit($ticket->descricao, 50) }}</td>
                        <td class="py-3 px-6">{{ $ticket->estado->name ?? '' }}</td>
                        <td class="py-3 px-6">{{ $ticket->categoria->name ?? '' }}</td>
                        <td class="py-3 px-6">{{ $ticket->prazo ? \Carbon\Carbon::parse($ticket->prazo)->format('d/m/Y') : 'N/A' }}</td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('tickets.show', $ticket) }}" class="w-8 h-8 rounded-full bg-indigo-500 text-white flex items-center justify-center hover:bg-indigo-700" title="Ver"><i class="fas fa-eye"></i></a>
                                 @role('tecnico')
                                         <a href="{{ route('tickets.edit', $ticket) }}" class="w-8 h-8 rounded-full bg-yellow-500 text-white flex items-center justify-center hover:bg-yellow-700" title="Editar"><i class="fas fa-edit"></i></a>
                                 @endrole
                                <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="inline-flex">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center hover:bg-red-700" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este ticket?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $tickets->links() }}
    </div>
</div>

<!-- Alpine.js Script -->
<script>
function ticketFilter() {
    return {
        filters: {
            categoria: '{{ request('categoria') }}',
            estado: '{{ request('estado') }}',
            search: '{{ request('search') }}',
        },
        init() {
            console.log('Filtros iniciados', this.filters);
        },
        applyFilters() {
            const params = new URLSearchParams(this.filters).toString();
            window.location = `?${params}`;
        },
        clearFilters() {
            this.filters = { categoria: '', estado: '', search: '' };
            this.applyFilters();
        }
    }
}
</script>
@endsection
