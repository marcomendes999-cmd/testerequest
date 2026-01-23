@extends('layouts.app')

@section('title', 'Detalhes da Empresa')

@section('content')
    <div class="container mx-auto p-4">
        <div class="bg-white shadow-lg rounded-lg p-8 mb-8">

            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h2 class="text-3xl font-bold text-gray-800">Empresa #{{ $empresa->id }} - {{ $empresa->nome }}</h2>
                <a href="{{ route('empresas.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg shadow-sm hover:bg-gray-100 transition duration-300 transform hover:scale-105">
                    <i class="fas fa-arrow-left mr-2"></i> Voltar
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Coluna da Esquerda -->
                <div class="space-y-4">
                    <p class="text-gray-600"><span class="font-semibold text-gray-800">Cliente Nº:</span> {{ $empresa->num_cliente ?? 'N/A' }}</p>
                    <p class="text-gray-600"><span class="font-semibold text-gray-800">NIF:</span> {{ $empresa->nif ?? 'N/A' }}</p>
                    <p class="text-gray-600"><span class="font-semibold text-gray-800">Email:</span> {{ $empresa->email ?? 'N/A' }}</p>
                    <p class="text-gray-600"><span class="font-semibold text-gray-800">Telefone:</span> {{ $empresa->telefone ?? 'N/A' }}</p>
                </div>
                <!-- Coluna da Direita -->
                <div class="space-y-4">
                    <p class="text-gray-600"><span class="font-semibold text-gray-800">Data de Criação:</span> {{ \Carbon\Carbon::parse($empresa->created_at)->format('d/m/Y H:i') }}</p>
                    <p class="text-gray-600"><span class="font-semibold text-gray-800">Última Atualização:</span> {{ \Carbon\Carbon::parse($empresa->updated_at)->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <!-- Secção de Moradas -->
            <div class="mb-6">
                <h3 class="font-semibold text-gray-800 mb-2 text-xl">Moradas</h3>
                @if($empresa->moradas->isEmpty())
                    <p class="text-gray-500">Nenhuma morada cadastrada.</p>
                @else
                    <ul class="list-disc list-inside bg-gray-50 p-4 rounded-lg text-gray-700 space-y-2">
                        @foreach($empresa->moradas as $morada)
                            <li>
                                {{ $morada->tipo ?? '' }} {{ $morada->rua }}{{ $morada->numero ? ', '.$morada->numero : '' }} - {{ $morada->codigo_postal }} {{ $morada->cidade }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Botões de Ação -->
            <div class="flex space-x-4 mt-6">
                <a href="{{ route('empresas.edit', $empresa) }}" class="px-6 py-2 bg-yellow-500 text-white rounded-lg shadow-lg hover:bg-yellow-600 transition duration-300 transform hover:scale-105">
                    <i class="fas fa-edit mr-2"></i> Editar Empresa
                </a>
                <form action="{{ route('empresas.destroy', $empresa) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg shadow-lg hover:bg-red-700 transition duration-300 transform hover:scale-105" onclick="return confirm('Tem certeza que deseja excluir esta empresa?');">
                        <i class="fas fa-trash-alt mr-2"></i> Excluir Empresa
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
