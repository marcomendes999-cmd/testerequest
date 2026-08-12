@extends('layouts.app')

@section('title', 'Editar Grupo')

@section('content')
<div class="container mx-auto p-4 flex justify-center">
    <div class="w-full max-w-lg bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Editar Grupo</h2>

        <form action="{{ route('grupos.update', $grupo->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Nome --}}
            <div class="mb-6">
                <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">Nome do Grupo</label>
                <input type="text" name="name" id="name"
                    class="form-input w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    value="{{ old('name', $grupo->name) }}" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Ordem --}}
            <div class="mb-6">
                <label for="ordem" class="block text-gray-700 text-sm font-semibold mb-2">Ordem</label>
                <input type="text" name="ordem" id="ordem"
                    class="form-input w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    value="{{ old('ordem', $grupo->ordem) }}" maxlength="4">
                @error('ordem')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="colaborador_id" class="block text-gray-700 text-sm font-semibold mb-2">Colaborador responsável</label>
                <select name="colaborador_id" id="colaborador_id" class="form-select w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">Sem responsável atribuído</option>
                    @foreach($colaboradores as $colaborador)
                        <option value="{{ $colaborador->id }}" {{ old('colaborador_id', $grupo->colaborador_id) == $colaborador->id ? 'selected' : '' }}>{{ $colaborador->name }} — {{ $colaborador->email }}</option>
                    @endforeach
                </select>
                @error('colaborador_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Activo --}}
            <div class="mb-6">
                <label for="activo" class="flex items-center text-gray-700 text-sm font-semibold">
                    <input type="checkbox" name="activo" id="activo" value="1" 
                        class="form-checkbox h-5 w-5 text-blue-600 rounded"
                        @checked(old('activo', $grupo->activo))>
                    <span class="ml-2">Ativo</span>
                </label>
            </div>

            {{-- Botões --}}
            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('grupos.index') }}"
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg shadow-sm hover:bg-gray-100 transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i> Voltar
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
