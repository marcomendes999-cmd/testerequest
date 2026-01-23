@extends('layouts.app')

@section('title', 'Editar Unidade')

@section('content')
    <div class="container mx-auto p-4 flex justify-center">
        <div class="w-full max-w-lg bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Editar Unidade</h2>

            <form action="{{ route('unidades.update', $unidade->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Nome -->
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">Nome da Unidade</label>
                    <input type="text" name="name" id="name" class="form-input w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" value="{{ old('name', $unidade->name) }}" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Posto de Trabalho -->
                <div class="mb-4">
                    <label for="posto_id" class="block text-gray-700 text-sm font-semibold mb-2">Posto de Trabalho</label>
                    <select name="posto_id" id="posto_id" class="form-select w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                        @foreach($postos as $posto)
                            <option value="{{ $posto->id }}" {{ old('posto_id', $unidade->posto_id) == $posto->id ? 'selected' : '' }}>{{ $posto->name }}</option>
                        @endforeach
                    </select>
                    @error('posto_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Capacidade -->
                <div class="mb-4">
                    <label for="capacidade" class="block text-gray-700 text-sm font-semibold mb-2">Capacidade (horas)</label>
                    <input type="number" step="0.5" name="capacidade" id="capacidade" class="form-input w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" value="{{ old('capacidade', $unidade->capacidade) }}" required>
                    @error('capacidade')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ordem -->
                <div class="mb-4">
                    <label for="ordem" class="block text-gray-700 text-sm font-semibold mb-2">Ordem</label>
                    <input type="text" name="ordem" id="ordem" class="form-input w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" value="{{ old('ordem', $unidade->ordem) }}">
                    @error('ordem')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Activo -->
                <div class="mb-4 flex items-center">
                    <input type="checkbox" name="activo" id="activo" value="1" class="form-checkbox h-5 w-5 text-blue-600 rounded-md focus:ring-2 focus:ring-blue-400" {{ old('activo', $unidade->activo) ? 'checked' : '' }}>
                    <label for="activo" class="ml-2 block text-sm text-gray-700 font-semibold">Ativo</label>
                    @error('activo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botões -->
                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('unidades.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg shadow-sm hover:bg-gray-100 transition duration-300">
                        <i class="fas fa-arrow-left mr-2"></i> Voltar
                    </a>
                    <button type="submit" class="px-6 py-2 bg-yellow-500 text-white rounded-lg shadow-lg hover:bg-yellow-600 transition duration-300 transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i> Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
