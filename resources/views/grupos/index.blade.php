@extends('layouts.app')

@section('title', 'Gestão de Grupos')

@section('content')
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Gestão de Grupos</h2>
            <a href="{{ route('grupos.create') }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i> Novo Grupo
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 shadow-md" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if($grupos->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <p class="text-gray-500 text-lg">Nenhum grupo encontrado.</p>
            </div>
        @else
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">ID</th>
                            <th class="py-3 px-6 text-left">Nome</th>
                            <th class="py-3 px-6 text-left">Ordem</th>
                            <th class="py-3 px-6 text-left">Colaborador responsável</th>
                            <th class="py-3 px-6 text-left">Ativo</th>
                            <th class="py-3 px-6 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @foreach($grupos as $grupo)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <span class="font-medium text-gray-700">{{ $grupo->id }}</span>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span class="font-medium text-gray-700">{{ $grupo->name }}</span>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span class="font-medium text-gray-700">{{ $grupo->ordem }}</span>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span class="font-medium text-gray-700">{{ $grupo->colaborador?->name ?? 'Sem responsável' }}</span>
                                </td>
                               <td class="py-3 px-6 text-left">
                                    <span class="px-2 py-1 font-semibold leading-tight rounded-full {{ $grupo->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $grupo->activo ? 'Sim' : 'Não' }}
                                    </span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center space-x-2">
                                        <a href="{{ route('grupos.edit', $grupo) }}" class="w-8 h-8 flex justify-center items-center rounded-full bg-yellow-100 text-yellow-600 hover:bg-yellow-200 transition duration-200 transform hover:scale-110" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('grupos.destroy', $grupo) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex justify-center items-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 transition duration-200 transform hover:scale-110" onclick="return confirm('Tem certeza que deseja excluir este grupo?');" title="Excluir">
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
                {{ $grupos->links() }}
            </div>
        @endif
    </div>
@endsection
