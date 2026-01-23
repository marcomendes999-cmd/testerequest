@extends('layouts.app')

@section('title', 'Lista de Empresas')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-700">Lista de Empresas</h2>
        <a href="{{ route('empresas.create') }}" 
           class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-200 shadow-md">
            <i class="fas fa-plus mr-2"></i> Nova Empresa
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if($empresas->isEmpty())
        <p class="text-gray-500">Nenhuma empresa encontrada.</p>
    @else
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">ID</th>
                        <th class="py-3 px-6 text-left">Nome</th>
                        <th class="py-3 px-6 text-left">NIF</th>
                        <th class="py-3 px-6 text-left">Email</th>
                        <th class="py-3 px-6 text-left">Telefone</th>
                        <th class="py-3 px-6 text-left">Moradas</th>
                        <th class="py-3 px-6 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach($empresas as $empresa)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $empresa->id }}</td>
                            <td class="py-3 px-6 text-left font-medium text-gray-800">{{ $empresa->nome }}</td>
                            <td class="py-3 px-6 text-left">{{ $empresa->nif }}</td>
                            <td class="py-3 px-6 text-left">{{ $empresa->email ?? '-' }}</td>
                            <td class="py-3 px-6 text-left">{{ $empresa->telefone ?? '-' }}</td>
                            <td class="py-3 px-6 text-left">
                                @forelse($empresa->moradas as $morada)
                                    <div class="text-sm text-gray-700">
                                        {{ $morada->tipo }} {{ $morada->rua }} {{ $morada->numero ? ', '.$morada->numero : '' }}<br>
                                        {{ $morada->codigo_postal }} {{ $morada->cidade }}
                                    </div>
                                @empty
                                    <span class="text-gray-400 italic">Sem moradas</span>
                                @endforelse
                            </td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center space-x-2">
                                    <a href="{{ route('empresas.show', $empresa) }}" 
                                       class="w-8 h-8 rounded-full bg-indigo-500 text-white flex items-center justify-center hover:bg-indigo-700 transition duration-200" 
                                       title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('empresas.edit', $empresa) }}" 
                                       class="w-8 h-8 rounded-full bg-yellow-500 text-white flex items-center justify-center hover:bg-yellow-700 transition duration-200" 
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('empresas.destroy', $empresa) }}" method="POST" class="inline-flex">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                            class="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center hover:bg-red-700 transition duration-200" 
                                            title="Eliminar" 
                                            onclick="return confirm('Tem certeza que deseja eliminar esta empresa?');">
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
            {{ $empresas->links() }}
        </div>
    @endif
@endsection
