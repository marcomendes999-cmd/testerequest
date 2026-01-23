@extends('layouts.app')

@section('title', 'Editar Empresa')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border-2 border-gray-100" x-data="empresaForm()">
            <div class="p-8 bg-gray-50 border-b border-gray-200">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Editar Empresa #{{ $empresa->id }}</h2>
                <p class="text-gray-600 mb-6">Altere os campos para atualizar a empresa.</p>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('empresas.update', $empresa) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700">Nome</label>
                            <input type="text" name="nome" id="nome" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('nome', $empresa->nome) }}" required>
                            @error('nome')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nif" class="block text-sm font-medium text-gray-700">NIF</label>
                            <input type="text" name="nif" id="nif" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('nif', $empresa->nif) }}" required>
                            @error('nif')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="num_cliente" class="block text-sm font-medium text-gray-700">Nº Cliente</label>
                            <input type="text" name="num_cliente" id="num_cliente" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('num_cliente', $empresa->num_cliente) }}" required>
                            @error('num_cliente')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('email', $empresa->email) }}">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="telefone" class="block text-sm font-medium text-gray-700">Telefone</label>
                            <input type="text" name="telefone" id="telefone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('telefone', $empresa->telefone) }}">
                            @error('telefone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Moradas -->
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Moradas</h3>

                        <template x-for="(morada, index) in moradas" :key="index">
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4 items-end">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Rua / Avenida / Travessa</label>
                                    <input type="text" :name="'moradas['+index+'][rua]'" x-model="morada.rua" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Número</label>
                                    <input type="text" :name="'moradas['+index+'][numero]'" x-model="morada.numero" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Cidade</label>
                                    <input type="text" :name="'moradas['+index+'][cidade]'" x-model="morada.cidade" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Código Postal</label>
                                    <input type="text" :name="'moradas['+index+'][codigo_postal]'" x-model="morada.codigo_postal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="button" @click="removeMorada(index)" class="px-3 py-2 bg-red-500 text-white rounded-md shadow hover:bg-red-600 transition">Remover</button>
                                </div>
                            </div>
                        </template>

                        <button type="button" @click="addMorada()" class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow hover:bg-indigo-700 transition">
                            <i class="fas fa-plus mr-2"></i> Adicionar Morada
                        </button>
                    </div>

                    <div class="flex justify-end mt-8">
                        <a href="{{ route('empresas.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg shadow-sm hover:bg-gray-100 transition mr-4">
                            Cancelar
                        </a>
                        <button type="submit" class="px-6 py-2 bg-yellow-500 text-white font-semibold rounded-md shadow-lg hover:bg-yellow-600 transition">
                            <i class="fas fa-save mr-2"></i> Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@php
    $moradas_array = old('moradas', $empresa->moradas->map(function($m){
        return [
            'rua' => $m->rua,
            'numero' => $m->numero,
            'cidade' => $m->cidade,
            'codigo_postal' => $m->codigo_postal,
        ];
    })->toArray());
@endphp

<script>
function empresaForm() {
    return {
        moradas: @json($moradas_array),
        addMorada() {
            this.moradas.push({ rua: '', numero: '', cidade: '', codigo_postal: '' });
        },
        removeMorada(index) {
            this.moradas.splice(index, 1);
        }
    }
}
</script>


@endsection
