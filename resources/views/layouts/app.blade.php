<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'M Request') }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Figtree', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full bg-slate-100 font-sans leading-normal tracking-normal">
    <div class="flex h-full">
        <!-- Sidebar -->
        <aside class="flex w-72 shrink-0 flex-col bg-slate-950 text-slate-300 shadow-2xl">
            <div class="border-b border-white/10 px-6 py-7">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 shadow-lg shadow-indigo-900/40">
                        <i class="fas fa-layer-group text-lg text-white"></i>
                    </span>
                    <span>
                        <img src="{{ asset('img/mrequest_logo.png') }}" alt="M Request" class="max-h-8 max-w-[150px] object-contain object-left">
                        <span class="mt-1 block text-[10px] font-medium uppercase tracking-[0.22em] text-slate-500">Service desk</span>
                    </span>
                </a>
            </div>

            <nav class="flex-1 space-y-7 overflow-y-auto px-4 py-6">
                <div>
                    <p class="mb-3 px-3 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">Área de trabalho</p>
                    <div class="space-y-1">
                        <a href="{{ route('dashboard') }}" @class([
                            'flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition',
                            'bg-indigo-500/15 text-white ring-1 ring-inset ring-indigo-400/20' => request()->routeIs('dashboard'),
                            'hover:bg-white/5 hover:text-white' => !request()->routeIs('dashboard'),
                        ])>
                            <i class="fas fa-table-cells w-5 text-center text-indigo-300"></i> Dashboard
                        </a>
                        <a href="{{ route('tickets.index') }}" @class([
                            'flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition',
                            'bg-indigo-500/15 text-white ring-1 ring-inset ring-indigo-400/20' => request()->routeIs('tickets.*'),
                            'hover:bg-white/5 hover:text-white' => !request()->routeIs('tickets.*'),
                        ])>
                            <i class="fas fa-ticket w-5 text-center text-indigo-300"></i> Pedidos
                        </a>
                    </div>
                </div>

                @hasanyrole('tecnico|admin')
                    @php
                        $settingsActive = request()->routeIs('categories.*', 'statuses.*', 'urgencies.*', 'users.*', 'tipos.*', 'roles.*', 'permissions.*', 'licenses.*', 'acessos.*', 'grupos.*', 'postos.*', 'unidades.*');
                    @endphp
                    <div x-data="{ open: {{ $settingsActive ? 'true' : 'false' }} }">
                        <p class="mb-3 px-3 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">Administração</p>
                        <button @click="open = !open" type="button" :class="open ? 'bg-white/5 text-white' : 'hover:bg-white/5 hover:text-white'" class="flex w-full items-center justify-between rounded-xl px-3 py-3 text-sm font-medium transition focus:outline-none">
                            <span class="flex items-center gap-3"><i class="fas fa-sliders w-5 text-center text-indigo-300"></i> Configurações</span>
                            <i class="fas fa-chevron-down text-xs text-slate-500 transition-transform" :class="{ 'rotate-180': open }"></i>
                        </button>
                        <div x-show="open" x-cloak class="mt-1 space-y-1 border-l border-white/10 py-1 pl-4">
                            <a href="{{ route('categories.index') }}" @class(['block rounded-lg px-3 py-2 text-sm transition hover:bg-white/5 hover:text-white', 'bg-white/5 text-white' => request()->routeIs('categories.*')])>Categorias</a>
                            <a href="{{ route('statuses.index') }}" @class(['block rounded-lg px-3 py-2 text-sm transition hover:bg-white/5 hover:text-white', 'bg-white/5 text-white' => request()->routeIs('statuses.*')])>Estados</a>
                            <a href="{{ route('urgencies.index') }}" @class(['block rounded-lg px-3 py-2 text-sm transition hover:bg-white/5 hover:text-white', 'bg-white/5 text-white' => request()->routeIs('urgencies.*')])>Urgências</a>
                            @role('admin')
                                <a href="{{ route('users.index') }}" @class(['block rounded-lg px-3 py-2 text-sm transition hover:bg-white/5 hover:text-white', 'bg-white/5 text-white' => request()->routeIs('users.*')])>Utilizadores</a>
                                <a href="{{ route('tipos.index') }}" @class(['block rounded-lg px-3 py-2 text-sm transition hover:bg-white/5 hover:text-white', 'bg-white/5 text-white' => request()->routeIs('tipos.*')])>Tipos de utilizador</a>
                                <div class="my-2 border-t border-white/10"></div>
                                <a href="{{ route('roles.index') }}" @class(['block rounded-lg px-3 py-2 text-sm transition hover:bg-white/5 hover:text-white', 'bg-white/5 text-white' => request()->routeIs('roles.*')])>Roles</a>
                                <a href="{{ route('permissions.index') }}" @class(['block rounded-lg px-3 py-2 text-sm transition hover:bg-white/5 hover:text-white', 'bg-white/5 text-white' => request()->routeIs('permissions.*')])>Permissões</a>
                                @if(Auth::id() === 1)
                                    <a href="{{ route('licenses.index') }}" @class(['block rounded-lg px-3 py-2 text-sm transition hover:bg-white/5 hover:text-white', 'bg-white/5 text-white' => request()->routeIs('licenses.*')])>Licenças</a>
                                @endif
                                <a href="{{ route('acessos.index') }}" @class(['block rounded-lg px-3 py-2 text-sm transition hover:bg-white/5 hover:text-white', 'bg-white/5 text-white' => request()->routeIs('acessos.*')])>Acessos</a>
                                <a href="{{ route('grupos.index') }}" @class(['block rounded-lg px-3 py-2 text-sm transition hover:bg-white/5 hover:text-white', 'bg-white/5 text-white' => request()->routeIs('grupos.*')])>Grupos</a>
                                <a href="{{ route('postos.index') }}" @class(['block rounded-lg px-3 py-2 text-sm transition hover:bg-white/5 hover:text-white', 'bg-white/5 text-white' => request()->routeIs('postos.*')])>Postos</a>
                                <a href="{{ route('unidades.index') }}" @class(['block rounded-lg px-3 py-2 text-sm transition hover:bg-white/5 hover:text-white', 'bg-white/5 text-white' => request()->routeIs('unidades.*')])>Unidades</a>
                            @endrole
                        </div>
                    </div>
                @endhasanyrole
            </nav>

            <div class="m-4 rounded-2xl border border-white/10 bg-white/5 p-4">
                <div class="flex items-center gap-3">
                    @if (Auth::user()->profile_photo_path)
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="" class="h-10 w-10 rounded-xl object-cover">
                    @else
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-500/20 text-indigo-200"><i class="fas fa-user"></i></span>
                    @endif
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                        <p class="truncate text-xs text-slate-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex flex-1 flex-col overflow-hidden">
            @php
                $pageTitle = match (true) {
                    request()->routeIs('dashboard') => 'Dashboard',
                    request()->routeIs('tickets.*') => 'Pedidos',
                    request()->routeIs('categories.*') => 'Categorias',
                    request()->routeIs('statuses.*') => 'Estados',
                    request()->routeIs('urgencies.*') => 'Urgências',
                    request()->routeIs('users.*') => 'Utilizadores',
                    default => 'M Request',
                };
            @endphp
            <header class="relative flex items-center justify-between border-b border-white/10 bg-slate-950 px-6 py-4 shadow-lg">
                <div class="flex min-w-0 items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-500/15 text-indigo-300 ring-1 ring-inset ring-indigo-400/20">
                        <i class="fas fa-table-cells"></i>
                    </span>
                    <div class="min-w-0">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-500">Área atual</p>
                        <h1 class="truncate text-base font-semibold text-white">{{ $pageTitle }}</h1>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('tickets.create') }}" class="hidden items-center rounded-xl bg-indigo-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-950/40 transition hover:bg-indigo-400 sm:inline-flex">
                        <i class="fas fa-plus mr-2 text-xs"></i>Novo pedido
                    </a>
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-3 rounded-xl px-2 py-1.5 text-sm font-medium text-slate-200 transition hover:bg-white/5 hover:text-white focus:outline-none">
                        @if (Auth::user()->profile_photo_path)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="" class="h-8 w-8 rounded-lg object-cover">
                        @else
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 text-indigo-200"><i class="fas fa-user text-xs"></i></span>
                        @endif
                        <span class="hidden max-w-32 truncate sm:block">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs text-slate-500"></i>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 z-20 mt-3 w-52 overflow-hidden rounded-xl border border-white/10 bg-slate-900 py-1 shadow-2xl shadow-black/30">
                        <div class="border-b border-white/10 px-4 py-3">
                            <p class="truncate text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                            <p class="truncate text-xs text-slate-500">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="mt-1 block px-4 py-2 text-sm text-slate-300 transition hover:bg-white/5 hover:text-white"><i class="fas fa-user mr-2 w-4 text-center text-indigo-300"></i> Perfil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-slate-300 transition hover:bg-white/5 hover:text-white"><i class="fas fa-sign-out-alt mr-2 w-4 text-center text-indigo-300"></i> Sair</button>
                        </form>
                    </div>
                </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-100 p-6">
                @yield('content')
            </main>

            <footer class="bg-white p-4 text-center text-sm text-slate-500">
                <p>&copy; {{ now()->year }} MARLI. Todos os direitos reservados.</p>
            </footer>
        </div>
    </div>
</body>
</html>
