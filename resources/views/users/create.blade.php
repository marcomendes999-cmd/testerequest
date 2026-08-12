@extends('layouts.app')

@section('title', 'Criar Novo Utilizador')

@section('content')
    <div class="container mx-auto flex justify-center p-4">
        <div class="w-full max-w-2xl overflow-auto rounded-lg bg-white p-6 shadow-lg">
            <h2 class="mb-6 text-center text-3xl font-bold text-gray-800">Criar Novo Utilizador</h2>

            <form action="{{ route('users.store') }}" method="POST" class="space-y-4"
                x-data="{ tipoId: @json((string) old('tipo_id', $tipoPredefinido)), tipoColaboradorId: @json((string) $tipoColaboradorId), grupoId: @json((string) old('grupo_id', '')), postoId: @json((string) old('posto_id', '')), unidadeId: @json((string) old('unidade_id', '')), postos: @json($postosParaSelecao) }">
                @csrf

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-gray-700">Nome</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="form-input w-full rounded-lg border px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-gray-700">E-mail</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required class="form-input w-full rounded-lg border px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="numero" class="mb-2 block text-sm font-semibold text-gray-700">Número</label>
                        <input type="text" name="numero" id="numero" value="{{ old('numero') }}" class="form-input w-full rounded-lg border px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @error('numero') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="tipo_id" class="mb-2 block text-sm font-semibold text-gray-700">Tipo</label>
                        <select name="tipo_id" id="tipo_id" x-model="tipoId" @change="if (tipoId !== tipoColaboradorId) { grupoId = ''; postoId = ''; unidadeId = ''; }" required class="form-select w-full rounded-lg border px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo->id }}" {{ (string) old('tipo_id', $tipoPredefinido) === (string) $tipo->id ? 'selected' : '' }}>{{ ucfirst($tipo->name) }}</option>
                            @endforeach
                        </select>
                        @error('tipo_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div id="workplace-fields" x-show="tipoId === tipoColaboradorId" class="grid grid-cols-1 gap-4 rounded-xl border border-sky-100 bg-sky-50/60 p-4 md:grid-cols-3">
                    <div>
                        <label for="grupo_id" class="mb-2 block text-sm font-semibold text-gray-700">Grupo</label>
                        <select name="grupo_id" id="grupo_id" x-model="grupoId" @change="postoId = ''" :required="tipoId === tipoColaboradorId" class="form-select w-full rounded-lg border px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="">Selecionar grupo</option>
                            @foreach($grupos as $grupo)
                                <option value="{{ $grupo->id }}" {{ old('grupo_id') == $grupo->id ? 'selected' : '' }}>{{ $grupo->name }}</option>
                            @endforeach
                        </select>
                        @error('grupo_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="posto_id" class="mb-2 block text-sm font-semibold text-gray-700">Posto de trabalho</label>
                        <select name="posto_id" id="posto_id" x-model="postoId" :disabled="!grupoId" :required="tipoId === tipoColaboradorId" class="form-select w-full rounded-lg border px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 disabled:cursor-not-allowed disabled:bg-gray-100">
                            <option value="" x-text="grupoId ? 'Selecionar posto' : 'Selecione primeiro o grupo'"></option>
                            @foreach($postosParaSelecao as $posto)
                                <option value="{{ $posto['id'] }}" data-grupo="{{ $posto['grupo_id'] }}" {{ old('posto_id') == $posto['id'] ? 'selected' : '' }}>{{ $posto['name'] }}</option>
                            @endforeach
                        </select>
                        @error('posto_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="unidade_id" class="mb-2 block text-sm font-semibold text-gray-700">Unidade de posto</label>
                        <select name="unidade_id" id="unidade_id" class="form-select w-full rounded-lg border px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 disabled:cursor-not-allowed disabled:bg-gray-100">
                            <option value="">Selecione primeiro o posto</option>
                            @foreach($unidadesParaSelecao as $unidade)
                                <option value="{{ $unidade['id'] }}" data-posto="{{ $unidade['posto_id'] }}" {{ old('unidade_id') == $unidade['id'] ? 'selected' : '' }}>{{ $unidade['name'] }}</option>
                            @endforeach
                        </select>
                        @error('unidade_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                @role('admin')
                    <div class="rounded-xl border border-indigo-100 bg-indigo-50/50 p-4">
                        <label class="mb-3 block text-sm font-semibold text-slate-800">Roles do utilizador</label>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            @foreach($roles as $role)
                                <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 transition hover:border-indigo-300">
                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                                    <span class="font-medium capitalize">{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('roles') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                @endrole

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-gray-700">Palavra-passe</label>
                        <input type="password" name="password" id="password" required class="form-input w-full rounded-lg border px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-gray-700">Confirmar palavra-passe</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required class="form-input w-full rounded-lg border px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                </div>

                <div class="mt-6 flex flex-col items-center justify-between gap-3 md:flex-row">
                    <a href="{{ route('users.index') }}" class="w-full rounded-lg border border-gray-300 px-6 py-2 text-center text-gray-700 shadow-sm transition hover:bg-gray-100 md:w-auto"><i class="fas fa-arrow-left mr-2"></i>Voltar</a>
                    <button type="submit" class="w-full rounded-lg bg-blue-600 px-6 py-2 text-white shadow-lg transition hover:bg-blue-700 md:w-auto"><i class="fas fa-save mr-2"></i>Criar utilizador</button>
                </div>
            </form>
        </div>
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
