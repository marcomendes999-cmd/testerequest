@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl py-4">
        <div class="mb-8">
            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-indigo-600">Conta</p>
            <h2 class="mt-2 text-3xl font-bold text-slate-900">O meu perfil</h2>
            <p class="mt-2 text-slate-500">Atualiza os teus dados pessoais e as credenciais de acesso.</p>
        </div>

        @if (session('status') === 'profile-updated')
            <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                <i class="fas fa-check-circle"></i> Dados do perfil atualizados com sucesso.
            </div>
        @endif
        @if (session('status') === 'password-updated')
            <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                <i class="fas fa-check-circle"></i> Palavra-passe atualizada com sucesso.
            </div>
        @endif

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <aside class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                @if ($user->profile_photo_path)
                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Fotografia de {{ $user->name }}" class="h-16 w-16 rounded-2xl object-cover shadow-lg shadow-indigo-200">
                @else
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 text-2xl text-white shadow-lg shadow-indigo-200">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
                <h3 class="mt-4 truncate text-lg font-bold text-slate-900">{{ $user->name }}</h3>
                <p class="mt-1 truncate text-sm text-slate-500">{{ $user->email }}</p>
                <div class="mt-6 border-t border-slate-100 pt-5 text-sm">
                    <div class="flex justify-between gap-3 py-2">
                        <span class="text-slate-500">Membro desde</span>
                        <span class="font-medium text-slate-700">{{ $user->created_at?->format('d/m/Y') ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between gap-3 py-2">
                        <span class="text-slate-500">Estado do e-mail</span>
                        <span class="font-medium {{ $user->email_verified_at ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ $user->email_verified_at ? 'Verificado' : 'Por verificar' }}
                        </span>
                    </div>
                </div>
            </aside>

            <div class="space-y-8 lg:col-span-2">
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <div class="mb-7">
                        <h3 class="text-xl font-bold text-slate-900">Informação pessoal</h3>
                        <p class="mt-1 text-sm text-slate-500">Estes dados identificam a conta com sessão iniciada.</p>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label for="profile_photo" class="mb-2 block text-sm font-semibold text-slate-700">Fotografia de perfil</label>
                            <input id="profile_photo" name="profile_photo" type="file" accept="image/jpeg,image/png,image/webp" class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-2 text-xs text-slate-500">JPG, PNG ou WebP, até 2 MB.</p>
                            @error('profile_photo')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Nome</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" class="block w-full rounded-xl border-slate-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">E-mail</label>
                            <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                <span class="min-w-0 truncate text-slate-700">{{ $user->email }}</span>
                                <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold {{ $user->email_verified_at ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $user->email_verified_at ? 'Verificado' : 'Por verificar' }}
                                </span>
                            </div>
                            <p class="mt-2 text-xs text-slate-500">O e-mail é o identificador único da conta e não pode ser alterado.</p>
                        </div>

                        @if (!$user->hasVerifiedEmail())
                            <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <span>O teu e-mail ainda não foi validado.</span>
                                    <button type="submit" form="resend-verification" class="font-semibold text-amber-900 underline underline-offset-2 hover:text-amber-700">Reenviar e-mail de validação</button>
                                </div>
                            </div>
                        @endif

                        <div class="flex justify-end border-t border-slate-100 pt-5">
                            <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <i class="fas fa-save mr-2"></i>Guardar alterações
                            </button>
                        </div>
                    </form>
                    @if (!$user->hasVerifiedEmail())
                        <form id="resend-verification" method="POST" action="{{ route('verification.send') }}" class="hidden">
                            @csrf
                        </form>
                    @endif
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <div class="mb-7">
                        <h3 class="text-xl font-bold text-slate-900">Segurança</h3>
                        <p class="mt-1 text-sm text-slate-500">Escolhe uma palavra-passe forte e exclusiva.</p>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="current_password" class="mb-2 block text-sm font-semibold text-slate-700">Palavra-passe atual</label>
                            <input id="current_password" name="current_password" type="password" autocomplete="current-password" class="block w-full rounded-xl border-slate-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('current_password', 'updatePassword')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <div>
                                <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Nova palavra-passe</label>
                                <input id="password" name="password" type="password" autocomplete="new-password" class="block w-full rounded-xl border-slate-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('password', 'updatePassword')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">Confirmar palavra-passe</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="block w-full rounded-xl border-slate-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="flex justify-end border-t border-slate-100 pt-5">
                            <button type="submit" class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                <i class="fas fa-lock mr-2"></i>Atualizar palavra-passe
                            </button>
                        </div>
                    </form>
                </section>

                <section x-data="{ confirmDelete: false }" class="rounded-2xl border border-red-200 bg-red-50/50 p-6 sm:p-8">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-red-900">Eliminar conta</h3>
                            <p class="mt-1 text-sm text-red-700">Esta ação é permanente e elimina os dados associados à conta.</p>
                        </div>
                        <button type="button" @click="confirmDelete = true" class="rounded-xl border border-red-300 bg-white px-4 py-2.5 text-sm font-semibold text-red-700 transition hover:bg-red-100">Eliminar conta</button>
                    </div>

                    <form x-show="confirmDelete" x-cloak method="POST" action="{{ route('profile.destroy') }}" class="mt-6 rounded-xl border border-red-200 bg-white p-5">
                        @csrf
                        @method('DELETE')
                        <label for="delete_password" class="mb-2 block text-sm font-semibold text-slate-700">Confirma com a tua palavra-passe</label>
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <input id="delete_password" name="password" type="password" required class="flex-1 rounded-xl border-slate-300 px-4 py-2.5 shadow-sm focus:border-red-500 focus:ring-red-500">
                            <button type="submit" class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700">Confirmar eliminação</button>
                        </div>
                        @error('password', 'userDeletion')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </form>
                </section>
            </div>
        </div>
    </div>
@endsection
