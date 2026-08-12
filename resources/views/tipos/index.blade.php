@extends('layouts.app')

@section('title', 'Tipos de utilizador')

@section('content')
<div class="container mx-auto p-4">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-3xl font-bold text-gray-800">Tipos de utilizador</h2>
        <a href="{{ route('tipos.create') }}" class="rounded-lg bg-blue-600 px-6 py-2 text-white shadow-lg transition hover:bg-blue-700"><i class="fas fa-plus mr-2"></i>Novo tipo</a>
    </div>
    @if(session('success')) <div class="mb-6 rounded-lg border-l-4 border-green-500 bg-green-100 p-4 text-green-700">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="mb-6 rounded-lg border-l-4 border-red-500 bg-red-100 p-4 text-red-700">{{ session('error') }}</div> @endif
    <div class="overflow-hidden rounded-lg bg-white shadow-md">
        <table class="min-w-full leading-normal">
            <thead><tr class="bg-gray-100 text-left text-sm uppercase text-gray-600"><th class="px-6 py-3">Nome</th><th class="px-6 py-3">Utilizadores</th><th class="px-6 py-3 text-center">Ações</th></tr></thead>
            <tbody class="text-sm text-gray-600">
            @forelse($tipos as $tipo)
                <tr class="border-b border-gray-200 hover:bg-gray-50"><td class="px-6 py-3 font-medium">{{ ucfirst($tipo->name) }}</td><td class="px-6 py-3">{{ $tipo->users_count }}</td><td class="px-6 py-3"><div class="flex justify-center gap-2"><a href="{{ route('tipos.edit', $tipo) }}" class="rounded-full bg-yellow-100 px-2 py-1 text-yellow-700"><i class="fas fa-edit"></i></a><form action="{{ route('tipos.destroy', $tipo) }}" method="POST">@csrf @method('DELETE')<button onclick="return confirm('Remover este tipo?')" class="rounded-full bg-red-100 px-2 py-1 text-red-700"><i class="fas fa-trash-alt"></i></button></form></div></td></tr>
            @empty
                <tr><td colspan="3" class="px-6 py-6 text-center text-gray-500">Sem tipos registados.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $tipos->links() }}</div>
</div>
@endsection
