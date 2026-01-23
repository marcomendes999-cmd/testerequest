@extends('layouts.app')

@section('title', 'Acessos Ativos')

@section('content')
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Acessos Ativos</h2>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 shadow-md" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if($activeUsers->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <p class="text-gray-500 text-lg">Nenhum utilizador ativo encontrado.</p>
            </div>
        @else
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">ID</th>
                            <th class="py-3 px-6 text-left">Utilizador</th>
                            <th class="py-3 px-6 text-left">Último Acesso</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @foreach($activeUsers as $user)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <span class="font-medium text-gray-700">{{ $user->id }}</span>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span class="font-medium text-gray-700">{{ $user->name }}</span>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span class="font-medium text-gray-700">{{ $user->last_active }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
