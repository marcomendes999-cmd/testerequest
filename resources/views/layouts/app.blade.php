<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'MRequest') }}</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Figtree', system-ui, sans-serif; }
        [x-cloak] { display: none !important; }

        .active-link {
            @apply bg-indigo-900/30 border-l-4 border-indigo-500 text-indigo-100 font-semibold;
        }

        .shadow-soft {
            box-shadow: 0 10px 25px -5px rgba(0,0,0,.1);
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>

<body
    x-data="{ sidebarOpen: false }"
    class="h-full bg-gray-50 text-gray-900 antialiased overflow-hidden"
>

<div class="flex h-full">

    <!-- OVERLAY MOBILE -->
    <div
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black/40 z-30 md:hidden"
        x-cloak
    ></div>

    <!-- SIDEBAR MOBILE -->
    <aside
        x-show="sidebarOpen"
        x-transition:enter="transition transform duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:leave="transition transform duration-300"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 w-72 bg-gray-900 text-gray-100 z-40 md:hidden flex flex-col"
        x-cloak
    >

        @include('layouts.partials.sidebar')

    </aside>

    <!-- SIDEBAR DESKTOP -->
    <aside class="hidden md:flex md:w-72 lg:w-80 bg-gray-900 text-gray-100 shadow-2xl flex-col">
        @include('layouts.partials.sidebar')
    </aside>

    <!-- MAIN -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- HEADER -->
        <header class="bg-white shadow-soft z-20">
            <div class="flex items-center justify-between px-6 py-4">

                <!-- LEFT -->
                <div class="flex items-center gap-4">

                    <!-- HAMBURGER -->
                    <button
                        @click="sidebarOpen = true"
                        class="md:hidden text-gray-600 hover:text-indigo-600"
                    >
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <div class="relative hidden sm:block">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input
                            type="text"
                            placeholder="Pesquisar..."
                            class="pl-10 pr-4 py-2 rounded-full border border-gray-300 focus:border-indigo-500 focus:outline-none"
                        >
                    </div>
                </div>

                <!-- USER -->
                <div x-data="{ open:false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-3">
                        <img src="https://pbs.twimg.com/profile_images/1387408059635490819/hnZQUwxb.jpg"
                             class="h-9 w-9 rounded-full object-cover">
                        <span class="font-semibold hidden sm:block">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-sm"></i>
                    </button>

                    <div
                        x-show="open"
                        @click.outside="open = false"
                        x-transition
                        x-cloak
                        class="absolute right-0 mt-3 w-60 bg-white rounded-xl shadow-2xl border"
                    >
                        <a href="{{ route('profile.edit') }}" class="block px-5 py-3 hover:bg-gray-100">
                            <i class="fas fa-user mr-2"></i> Perfil
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left px-5 py-3 text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-2"></i> Sair
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        <!-- CONTENT -->
        <main class="flex-1 overflow-y-auto p-6 lg:p-10">
            @yield('content')
        </main>

        <!-- FOOTER -->
        <footer class="bg-white border-t py-4 text-center text-sm text-gray-500">
            © 2026 MARLI — Todos os direitos reservados.
        </footer>

    </div>
</div>

</body>
</html>
<script>
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark')
    } else {
        document.documentElement.classList.remove('dark')
    }
</script>