@extends('layouts.app')

@section('title', 'Criar Novo Utilizador')

@section('content')
    <div class="container mx-auto p-4 flex justify-center">
        <div class="w-full max-w-2xl bg-white rounded-lg shadow-lg p-6 overflow-auto">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Criar Novo Utilizador</h2>

            <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nome -->
                    <div>
                        <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">Nome</label>
                        <input type="text" name="name" id="name" class="form-input w-full px-3 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" value="{{ old('name') }}" placeholder="Nome do utilizador" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
                        <input type="email" name="email" id="email" class="form-input w-full px-3 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" value="{{ old('email') }}" placeholder="exemplo@email.com" required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Número -->
                    <div>
                        <label for="numero" class="block text-gray-700 text-sm font-semibold mb-2">Número</label>
                        <input type="text" name="numero" id="numero" class="form-input w-full px-3 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" value="{{ old('numero') }}" placeholder="Número do utilizador (4 dígitos)">
                        @error('numero')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo -->
                    <div>
                        <label for="tipo" class="block text-gray-700 text-sm font-semibold mb-2">Tipo</label>
                        <select name="tipo" id="tipo" class="form-select w-full px-3 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo }}" {{ old('tipo') === $tipo ? 'selected' : '' }}>{{ ucfirst($tipo) }}</option>
                            @endforeach
                        </select>
                        @error('tipo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                        <input type="password" name="password" id="password" class="form-input w-full px-3 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Password" required>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmação da Password -->
                    <div>
                        <label for="password_confirmation" class="block text-gray-700 text-sm font-semibold mb-2">Confirmação da Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-input w-full px-3 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Confirme a password" required>
                    </div>
                </div>
                
                <!-- Botões -->
                <div class="flex flex-col md:flex-row items-center justify-between mt-6 gap-3">
                    <a href="{{ route('users.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg shadow-sm hover:bg-gray-100 transition duration-300 text-center w-full md:w-auto">
                        <i class="fas fa-arrow-left mr-2"></i> Voltar
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105 w-full md:w-auto">
                        <i class="fas fa-save mr-2"></i> Criar Utilizador
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
