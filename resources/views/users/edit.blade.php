@extends('layouts.app')

@section('title', 'Editar Utilizador')

@section('content')
<div class="container mx-auto p-4 flex justify-center">
    <div class="w-full max-w-2xl bg-white rounded-lg shadow-lg p-6 overflow-auto">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Editar Utilizador</h2>

        <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-4"
            x-data="{ tipoId: @json((string) old('tipo_id', $user->tipo_id)), tipoColaboradorId: @json((string) $tipoColaboradorId), grupoId: @json((string) old('grupo_id', optional($user->posto)->grupo_id ?? '')), postoId: @json((string) old('posto_id', $user->posto_id ?? '')), unidadeId: @json((string) old('unidade_id', $user->unidade_id ?? '')), postos: @json($postosParaSelecao) }">
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
                    <label for="tipo_id" class="block text-gray-700 text-sm font-semibold mb-2">Tipo</label>
                    <select name="tipo_id" id="tipo_id" x-model="tipoId" @change="if (tipoId !== tipoColaboradorId) { grupoId = ''; postoId = ''; unidadeId = ''; }" class="form-select w-full px-3 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id }}" {{ (string) old('tipo_id', $user->tipo_id) === (string) $tipo->id ? 'selected' : '' }}>{{ ucfirst($tipo->name) }}</option>
                        @endforeach
                    </select>
                    @error('tipo_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div id="workplace-fields" x-show="tipoId === tipoColaboradorId" class="grid grid-cols-1 gap-4 rounded-xl border border-sky-100 bg-sky-50/60 p-4 md:grid-cols-3">
                <div>
                    <label for="grupo_id" class="mb-2 block text-sm font-semibold text-gray-700">Grupo</label>
                    <select name="grupo_id" id="grupo_id" x-model="grupoId" @change="postoId = ''" :required="tipoId === tipoColaboradorId" class="form-select w-full rounded-lg border px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="">Selecionar grupo</option>
                        @foreach($grupos as $grupo)
                            <option value="{{ $grupo->id }}" {{ old('grupo_id', optional($user->posto)->grupo_id) == $grupo->id ? 'selected' : '' }}>{{ $grupo->name }}</option>
                        @endforeach
                    </select>
                    @error('grupo_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="posto_id" class="mb-2 block text-sm font-semibold text-gray-700">Posto de trabalho</label>
                        <select name="posto_id" id="posto_id" x-model="postoId" :disabled="!grupoId" :required="tipoId === tipoColaboradorId" class="form-select w-full rounded-lg border px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 disabled:cursor-not-allowed disabled:bg-gray-100">
                            <option value="" x-text="grupoId ? 'Selecionar posto' : 'Selecione primeiro o grupo'"></option>
                            @foreach($postosParaSelecao as $posto)
                                <option value="{{ $posto['id'] }}" data-grupo="{{ $posto['grupo_id'] }}" {{ old('posto_id', $user->posto_id) == $posto['id'] ? 'selected' : '' }}>{{ $posto['name'] }}</option>
                            @endforeach
                    </select>
                    @error('posto_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="unidade_id" class="mb-2 block text-sm font-semibold text-gray-700">Unidade de posto</label>
                    <select name="unidade_id" id="unidade_id" class="form-select w-full rounded-lg border px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 disabled:cursor-not-allowed disabled:bg-gray-100">
                        <option value="">Selecione primeiro o posto</option>
                        @foreach($unidadesParaSelecao as $unidade)
                            <option value="{{ $unidade['id'] }}" data-posto="{{ $unidade['posto_id'] }}" {{ old('unidade_id', $user->unidade_id) == $unidade['id'] ? 'selected' : '' }}>{{ $unidade['name'] }}</option>
                        @endforeach
                    </select>
                    @error('unidade_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            @role('admin')
                <div class="mb-4 rounded-xl border border-indigo-100 bg-indigo-50/50 p-4">
                    <label class="mb-3 block text-sm font-semibold text-slate-800">Roles do utilizador</label>
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        @foreach($roles as $role)
                            <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 transition hover:border-indigo-300">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" {{ in_array($role->id, old('roles', $user->roles->pluck('id')->all())) ? 'checked' : '' }}>
                                <span class="font-medium capitalize">{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('roles')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endrole

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
        @role('admin')
            @if (!$user->hasVerifiedEmail())
                <form action="{{ route('users.verification.resend', $user) }}" method="POST" class="mt-4 rounded-lg border border-amber-200 bg-amber-50 p-4">
                    @csrf
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-sm text-amber-800">O e-mail deste utilizador ainda não foi validado.</p>
                        <button type="submit" class="rounded-lg border border-amber-300 bg-white px-4 py-2 text-sm font-semibold text-amber-800 hover:bg-amber-100">Reenviar e-mail de validação</button>
                    </div>
                </form>
            @endif
        @endrole    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tipo = document.getElementById('tipo_id');
        const grupo = document.getElementById('grupo_id');
        const posto = document.getElementById('posto_id');
        const unidade = document.getElementById('unidade_id');
        const campos = document.getElementById('workplace-fields');

        if (!tipo || !grupo || !posto || !unidade || !campos) return;

        const atualizar = () => {
            const colaborador = tipo.value === '{{ $tipoColaboradorId }}';
            campos.style.display = colaborador ? '' : 'none';
            grupo.required = colaborador;
            posto.required = colaborador;
            unidade.required = colaborador;

            Array.from(posto.options).forEach((option) => {
                if (!option.value) return;
                option.hidden = option.dataset.grupo !== grupo.value;
                option.disabled = option.dataset.grupo !== grupo.value;
            });

            posto.disabled = !grupo.value;
            if (posto.selectedOptions[0] && posto.selectedOptions[0].disabled) posto.value = '';

            Array.from(unidade.options).forEach((option) => {
                if (!option.value) return;
                option.hidden = option.dataset.posto !== posto.value;
                option.disabled = option.dataset.posto !== posto.value;
            });
            unidade.disabled = !posto.value;
            if (unidade.selectedOptions[0] && unidade.selectedOptions[0].disabled) unidade.value = '';
        };

        tipo.addEventListener('change', atualizar);
        grupo.addEventListener('change', atualizar);
        posto.addEventListener('change', atualizar);
        atualizar();
    });
</script>

@endsection
