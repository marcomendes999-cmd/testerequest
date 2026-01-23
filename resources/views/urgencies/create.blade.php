@extends('layouts.app')

@section('title', 'Criar Urgência')

@section('content')
    <div class="container mx-auto p-4 flex justify-center">
        <div class="w-full max-w-lg bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Criar Nova Urgência</h2>

            <form action="{{ route('urgencies.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">Nome da Urgência</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-input w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Ex: Baixa" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="ordem" class="block text-gray-700 text-sm font-semibold mb-2">Ordem</label>
                    <input type="text" name="ordem" id="ordem" value="{{ old('ordem') }}" class="shadow-sm rounded-lg w-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" required>
                    @error('ordem')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="time" class="block text-gray-700 text-sm font-semibold mb-2">Tempo</label>
                    <input type="text" name="time" id="time" value="{{ old('time') }}" class="shadow-sm rounded-lg w-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" required>
                    @error('time')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('urgencies.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg shadow-sm hover:bg-gray-100 transition duration-300">
                        <i class="fas fa-arrow-left mr-2"></i> Voltar
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i> Criar Urgência
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
