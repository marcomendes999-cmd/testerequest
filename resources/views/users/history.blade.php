@extends('layouts.app')

@section('title', 'Histórico de Login de ' . $user->name)

@section('content')
    <div class="container mx-auto p-4">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Histórico de Login de {{ $user->name }}</h2>
            <a href="{{ route('users.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg shadow-sm hover:bg-gray-100 transition duration-300 transform hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i> Voltar
            </a>
        </div>

        @if($history->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <p class="text-gray-500 text-lg">Nenhum histórico de login encontrado para este utilizador.</p>
            </div>
        @else
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Login</th>
                            <th class="py-3 px-6 text-left">Logout</th>
                            <th class="py-3 px-6 text-left">IP</th>
                            <th class="py-3 px-6 text-left">User Agent</th>
                            <th class="py-3 px-6 text-left">Duração</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @foreach($history as $item)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-6 text-left whitespace-nowrap">{{ $item->login_at->format('d/m/Y H:i') }}</td>
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    {{ $item->logout_at ? $item->logout_at->format('d/m/Y H:i') : 'Ativo' }}
                                </td>
                                <td class="py-3 px-6 text-left">{{ $item->ip_address }}</td>
                                <td class="py-3 px-6 text-left">{{ Str::limit($item->user_agent, 50) }}</td>
                                <td class="py-3 px-6 text-left">
                                    @if($item->duration)
                                        {{ floor($item->duration / 60) }} min {{ $item->duration % 60 }} seg
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
