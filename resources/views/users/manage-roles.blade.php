@extends('layouts.app')

@section('title', 'Gerir Roles do Utilizador')

@section('content')
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Gerir Roles do Utilizador</h2>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 shadow-md" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-gray-600 mb-6">Utilizador: <strong class="text-gray-800">{{ $user->name }}</strong></p>

            <form action="{{ route('users.roles.update', $user) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    @foreach($roles as $role)
                        <div class="flex items-center">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" id="role-{{ $role->id }}"
                                class="form-checkbox h-5 w-5 text-blue-600"
                                {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                            <label for="role-{{ $role->id }}" class="ml-2 text-gray-700">{{ $role->name }}</label>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <a href="{{ route('users.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg shadow-sm hover:bg-gray-100 transition duration-300">
                        <i class="fas fa-arrow-left mr-2"></i> Voltar
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i> Guardar Roles
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
