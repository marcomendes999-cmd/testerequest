@extends('layouts.app')

@section('title', 'Editar Utilizador')

@section('content')
<div class="container mx-auto p-4 flex justify-center">
    <div class="w-full max-w-2xl bg-white rounded-lg shadow-lg p-6 overflow-auto">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Editar Utilizador</h2>

        <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nome -->
                <div>
                    <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">Nome</label>
                    <input type="text" name="name" id="name" class="form-input w-full px-3 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" value="{{ old('name', $user->name) }}" placeholder="Nome do utilizador" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
                    <input type="email" name="email" id="email" class="form-input w-full px-3 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" value="{{ old('email', $user->email) }}" placeholder="exemplo@email.com" required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Número -->
                <div>
                    <label for="numero" class="block text-gray-700 text-sm font-semibold mb-2">Número</label>
                    <input type="text" name="numero" id="numero" class="form-input w-full px-3 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" value="{{ old('numero', $user->numero) }}" placeholder="Número do utilizador (4 dígitos)">
                    @error('numero')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo -->
                <div>
                    <label for="tipo" class="block text-gray-700 text-sm font-semibold mb-2">Tipo</label>
                    <select name="tipo" id="tipo" class="form-select w-full px-3 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo }}" {{ old('tipo', $user->tipo) === $tipo ? 'selected' : '' }}>{{ ucfirst($tipo) }}</option>
                        @endforeach
                    </select>
                    @error('tipo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Empresa -->
                <div>
                    <label for="empresa_id" class="block text-gray-700 text-sm font-semibold mb-2">Empresa (Opcional)</label>
                    <select name="empresa_id" id="empresa_id" class="form-select w-full px-3 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="">-- Nenhuma --</option>
                        @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id }}" {{ old('empresa_id', $user->empresa_id) == $empresa->id ? 'selected' : '' }}>
                                {{ $empresa->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('empresa_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Roles (informativo) -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Roles atribuídas</label>
                @if($user->roles->isEmpty())
                    <p class="text-gray-500">Nenhuma role atribuída</p>
                @else
                    <ul class="list-disc list-inside text-gray-700">
                        @foreach($user->roles as $role)
                            <li>{{ $role->name }}</li>
                        @endforeach
                    </ul>
                @endif
                @role('admin')
                <div class="mt-1 text-sm text-gray-500">
                    <a href="{{ route('users.roles.edit', $user) }}" class="text-blue-600 hover:underline">Gerir Roles e Permissões</a>
                </div>
                @endrole
            </div>

            <!-- Password -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Nova Password (Opcional)</label>
                    <input type="password" name="password" id="password" class="form-input w-full px-3 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Password">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-semibold mb-2">Confirmação da Nova Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-input w-full px-3 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Confirme a password">
                </div>
            </div>

            <!-- Botões -->
            <div class="flex flex-col md:flex-row items-center justify-between mt-6 gap-3">
                <a href="{{ route('users.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg shadow-sm hover:bg-gray-100 transition duration-300 text-center w-full md:w-auto">
                    <i class="fas fa-arrow-left mr-2"></i> Voltar
                </a>
                <button type="submit" class="px-6 py-2 bg-yellow-500 text-white rounded-lg shadow-lg hover:bg-yellow-600 transition duration-300 transform hover:scale-105 w-full md:w-auto">
                    <i class="fas fa-save mr-2"></i> Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
