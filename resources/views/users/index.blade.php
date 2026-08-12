@extends('layouts.app')

@section('title', 'Gestão de Utilizadores')

@section('content')
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Gestão de Utilizadores</h2>
            <a href="{{ route('users.create', request()->only('tipo_id')) }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i> Novo Utilizador
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 shadow-md" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ route('users.index') }}" method="GET" class="mb-6 grid grid-cols-1 gap-3 rounded-xl bg-white p-4 shadow-md md:grid-cols-4">
            <div>
                <label for="nome" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Nome</label>
                <input type="search" name="nome" id="nome" value="{{ request('nome') }}" placeholder="Pesquisar utilizador" class="form-input w-full rounded-lg border px-3 py-2 text-sm">
            </div>
            <div>
                <label for="tipo_id" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Tipo</label>
                <select name="tipo_id" id="tipo_id" class="form-select w-full rounded-lg border px-3 py-2 text-sm">
                    <option value="">Todos os tipos</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id }}" {{ (string) request('tipo_id') === (string) $tipo->id ? 'selected' : '' }}>{{ ucfirst($tipo->name) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="grupo_filtro" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Grupo</label>
                <select name="grupo_id" id="grupo_filtro" class="form-select w-full rounded-lg border px-3 py-2 text-sm">
                    <option value="">Todos os grupos</option>
                    @foreach($grupos as $grupo)
                        <option value="{{ $grupo->id }}" {{ (string) request('grupo_id') === (string) $grupo->id ? 'selected' : '' }}>{{ $grupo->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700"><i class="fas fa-filter mr-1"></i> Filtrar</button>
                <a href="{{ route('users.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">Limpar</a>
            </div>
        </form>

        @if($users->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <p class="text-gray-500 text-lg">Nenhum utilizador encontrado.</p>
            </div>
        @else
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Nome</th>
                            <th class="py-3 px-6 text-left">Email</th>
                            <th class="py-3 px-6 text-left">Número</th>
                            <th class="py-3 px-6 text-left">Tipo</th>
                            <th class="py-3 px-6 text-left">Grupo / Posto</th>
                            <th class="py-3 px-6 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @foreach($users as $user)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-6 text-left">
                                    <span class="font-medium text-gray-700">{{ $user->name }}</span>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span class="font-medium text-gray-700">{{ $user->email }}</span>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span class="font-medium text-gray-700">{{ $user->numero }}</span>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span class="font-medium text-gray-700 capitalize">{{ $user->tipo?->name ?? '—' }}</span>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    @if($user->posto)
                                        <span class="block font-medium text-gray-700">{{ $user->posto->name }}</span>
                                        <span class="text-xs text-gray-500">{{ $user->posto->grupo?->name }}</span>
                                        @if($user->unidade)
                                            <span class="block text-xs text-sky-600">{{ $user->unidade->name }}</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center space-x-2">
                                        <a href="{{ route('users.history', $user) }}" class="w-8 h-8 flex justify-center items-center rounded-full bg-indigo-100 text-indigo-600 hover:bg-indigo-200 transition duration-200 transform hover:scale-110" title="Ver Histórico">
                                            <i class="fas fa-history"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" class="w-8 h-8 flex justify-center items-center rounded-full bg-yellow-100 text-yellow-600 hover:bg-yellow-200 transition duration-200 transform hover:scale-110" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex justify-center items-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 transition duration-200 transform hover:scale-110" onclick="return confirm('Tem certeza que deseja excluir este utilizador?');" title="Excluir">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
