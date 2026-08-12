@extends('layouts.app')

@section('title', 'Editar Licença')

@section('content')
    <div class="container mx-auto p-4 flex justify-center">
        <div class="w-full max-w-lg bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Editar Licença</h2>

            <form action="{{ route('licenses.update', $license) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-6">
                    <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">Nome da Licença</label>
                    <input type="text" name="name" id="name" class="form-input w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" value="{{ old('name', $license->name) }}" placeholder="Ex: Licença Básica" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="max_users" class="block text-gray-700 text-sm font-semibold mb-2">Número Máximo de Utilizadores</label>
                    <input type="number" name="max_users" id="max_users" class="form-input w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" value="{{ old('max_users', $license->max_users) }}" placeholder="Ex: 5" required>
                    @error('max_users')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="expires_at" class="block text-gray-700 text-sm font-semibold mb-2">Data de validade</label>
                    <input type="date" name="expires_at" id="expires_at" value="{{ old('expires_at', optional($license->expires_at)->format('Y-m-d')) }}" class="form-input w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    @error('expires_at')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <input type="hidden" name="ativo" value="0">
                    <label for="ativo" class="flex cursor-pointer items-center gap-3 text-sm font-semibold text-gray-700">
                        <input type="checkbox" name="ativo" id="ativo" value="1" @checked(old('ativo', $license->ativo)) class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Licença ativa
                    </label>
                    <p class="mt-2 text-xs text-gray-500">Só pode existir uma licença ativa. Ao ativar esta, qualquer outra será desativada.</p>
                </div>
                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('licenses.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg shadow-sm hover:bg-gray-100 transition duration-300">
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
