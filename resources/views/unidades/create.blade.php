@extends('layouts.app')

@section('title', 'Criar Nova Unidade')

@section('content')
    <div class="container mx-auto p-4 flex justify-center">
        <div class="w-full max-w-lg bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Criar Nova Unidade</h2>

            <form action="{{ route('unidades.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Nome -->
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">Nome da Unidade</label>
                    <input type="text" name="name" id="name" class="form-input w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" value="{{ old('name') }}" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Posto de Trabalho -->
                <div class="mb-4">
                    <label for="posto_id" class="block text-gray-700 text-sm font-semibold mb-2">Posto de Trabalho</label>
                    <select name="posto_id" id="posto_id" class="form-select w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                        @foreach($postos as $posto)
                            <option value="{{ $posto->id }}" {{ old('posto_id') == $posto->id ? 'selected' : '' }}>{{ $posto->name }}</option>
                        @endforeach
                    </select>
                    @error('posto_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Capacidade -->
                <div class="mb-4">
                    <label for="capacidade" class="block text-gray-700 text-sm font-semibold mb-2">Capacidade (horas)</label>
                    <input type="number" step="0.5" name="capacidade" id="capacidade" class="form-input w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" value="{{ old('capacidade') }}" required>
                    @error('capacidade')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ordem -->
                <div class="mb-4">
                    <label for="ordem" class="block text-gray-700 text-sm font-semibold mb-2">Ordem</label>
                    <input type="text" name="ordem" id="ordem" class="form-input w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" value="{{ old('ordem') }}">
                    @error('ordem')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Botões -->
                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('unidades.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg shadow-sm hover:bg-gray-100 transition duration-300">
                        <i class="fas fa-arrow-left mr-2"></i> Voltar
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
