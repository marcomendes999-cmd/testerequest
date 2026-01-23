@extends('layouts.app')

@section('title', 'Nova Empresa')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border-2 border-gray-100">
            <div class="p-8 bg-gray-50 border-b border-gray-200">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Nova Empresa</h2>
                <p class="text-gray-600 mb-6">Preencha os campos para adicionar uma nova empresa.</p>
                
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                
                <form action="{{ route('empresas.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700">Nome da Empresa</label>
                            <input type="text" name="nome" id="nome" value="{{ old('nome') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            @error('nome')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nif" class="block text-sm font-medium text-gray-700">NIF</label>
                            <input type="text" name="nif" id="nif" value="{{ old('nif') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            @error('nif')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="num_cliente" class="block text-sm font-medium text-gray-700">Nº Cliente</label>
                            <input type="text" name="num_cliente" id="num_cliente" value="{{ old('num_cliente') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            @error('num_cliente')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="telefone" class="block text-sm font-medium text-gray-700">Telefone</label>
                            <input type="text" name="telefone" id="telefone" value="{{ old('telefone') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            @error('telefone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Moradas -->
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold text-gray-700 mb-3">Moradas</h3>
                        <div id="moradas-container" class="space-y-6">
                            <div class="morada-item grid grid-cols-1 md:grid-cols-4 gap-6 bg-gray-50 p-4 rounded-md border">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Rua / Avenida / Travessa</label>
                                    <input type="text" name="moradas[0][rua]" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nº Porta</label>
                                    <input type="text" name="moradas[0][numero]"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Cidade</label>
                                    <input type="text" name="moradas[0][cidade]" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Código Postal</label>
                                    <input type="text" name="moradas[0][codigo_postal]"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="button" id="add-morada"
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-200">
                                <i class="fas fa-plus mr-2"></i>Adicionar Morada
                            </button>
                        </div>
                    </div>

                    <!-- Botão final -->
                    <div class="flex justify-end mt-6">
                         <a href="{{ route('empresas.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg shadow-sm hover:bg-gray-100 transition mr-4">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            Guardar Empresa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script para adicionar/remover moradas -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let index = 1;
        const container = document.getElementById('moradas-container');
        const addBtn = document.getElementById('add-morada');

        addBtn.addEventListener('click', function () {
            const morada = document.createElement('div');
            morada.classList.add('morada-item', 'grid', 'grid-cols-1', 'md:grid-cols-4', 'gap-6', 'bg-gray-50', 'p-4', 'rounded-md', 'border', 'relative');
            morada.innerHTML = `
                <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 remove-morada">
                    <i class="fas fa-trash"></i>
                </button>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Rua / Avenida / Travessa</label>
                    <input type="text" name="moradas[${index}][rua]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nº Porta</label>
                    <input type="text" name="moradas[${index}][numero]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cidade</label>
                    <input type="text" name="moradas[${index}][cidade]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Código Postal</label>
                    <input type="text" name="moradas[${index}][codigo_postal]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                </div>
            `;
            container.appendChild(morada);
            index++;
        });

        container.addEventListener('click', function (e) {
            if (e.target.closest('.remove-morada')) {
                e.target.closest('.morada-item').remove();
            }
        });
    });
</script>
@endsection
