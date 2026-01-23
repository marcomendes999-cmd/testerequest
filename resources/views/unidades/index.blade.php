@extends('layouts.app')

@section('title', 'Gestão de Unidades')

@section('content')
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Gestão de Unidades</h2>
            <a href="{{ route('unidades.create') }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i> Nova Unidade
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 shadow-md" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        
        <!-- Filtros de pesquisa compactos -->
        <div class="bg-white shadow-lg rounded-xl p-4 mb-6 border border-gray-100">
            <form action="{{ route('unidades.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[150px]">
                    <label for="grupo_id" class="block text-xs font-medium text-gray-700 mb-1">Grupo</label>
                    <select name="grupo_id" id="grupo_id" class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">Todos os Grupos</option>
                        @foreach($grupos as $grupo)
                            <option value="{{ $grupo->id }}" {{ request('grupo_id') == $grupo->id ? 'selected' : '' }}>{{ $grupo->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label for="posto_id" class="block text-xs font-medium text-gray-700 mb-1">Posto</label>
                    <select name="posto_id" id="posto_id" class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">Todos os Postos</option>
                        @foreach($postos as $posto)
                            <option value="{{ $posto->id }}" {{ request('posto_id') == $posto->id ? 'selected' : '' }}>{{ $posto->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="px-4 py-1.5 bg-indigo-600 text-white font-medium rounded-md shadow-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 text-sm">
                        <i class="fas fa-filter mr-1"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>

        @if($unidades->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <p class="text-gray-500 text-lg">Nenhuma unidade encontrada.</p>
            </div>
        @else
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">ID</th>
                            <th class="py-3 px-6 text-left">Nome</th>
                            <th class="py-3 px-6 text-left">Posto de Trabalho</th>
                            <th class="py-3 px-6 text-left">Grupo</th>
                            <th class="py-3 px-6 text-left">Capacidade</th>
                            <th class="py-3 px-6 text-left">Ordem</th>
                            <th class="py-3 px-6 text-left">Activo</th>
                            <th class="py-3 px-6 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @foreach($unidades as $unidade)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-6 text-left whitespace-nowrap">{{ $unidade->id }}</td>
                                <td class="py-3 px-6 text-left">{{ $unidade->name }}</td>
                                <td class="py-3 px-6 text-left">{{ optional($unidade->posto)->name ?? 'N/A' }}</td>
                                <td class="py-3 px-6 text-left">{{ optional(optional($unidade->posto)->grupo)->name ?? 'N/A' }}</td>
                                <td class="py-3 px-6 text-left">{{ $unidade->capacidade }}h</td>
                                <td class="py-3 px-6 text-left">{{ $unidade->ordem }}</td>
                                <td class="py-3 px-6 text-left">
                                    <span class="px-2 py-1 font-semibold leading-tight rounded-full {{ $unidade->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $unidade->activo ? 'Sim' : 'Não' }}
                                    </span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center space-x-2">
                                        <a href="{{ route('unidades.edit', $unidade) }}" class="w-8 h-8 flex justify-center items-center rounded-full bg-yellow-100 text-yellow-600 hover:bg-yellow-200 transition duration-200 transform hover:scale-110" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('unidades.destroy', $unidade) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex justify-center items-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 transition duration-200 transform hover:scale-110" onclick="return confirm('Tem certeza que deseja excluir esta unidade?');" title="Excluir">
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
                {{ $unidades->links() }}
            </div>
        @endif
    </div>
@endsection
