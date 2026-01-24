<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" xintegrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
         <link rel="stylesheet" href="/build/assets/app-BUsT5kbZ.css">
        <link rel="preload" as="style" href="/build/assets/app-BUsT5kbZ.css" />
        <link rel="modulepreload" href="/build/assets/app-DlYOw6CL.js" />
    

    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }

        .active-link {
            background-color: #3b3f46;
            border-left-color: #6366f1;
            color: white;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal h-full">

    <div class="flex h-full">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-gray-200 shadow-md">
            <div class="p-6 text-center">
                <a href="/dashboard" class="text-white text-2xl font-semibold uppercase">
                    <img src="{{ asset('img/mrequest_logo.png') }}" alt="M Request" class="mx-auto" style="max-height: 50px;">
                </a>
            </div>
            <nav class="mt-8">
                <a href="/dashboard" class="flex items-center py-2.5 px-6 opacity-75 hover:opacity-100 hover:bg-gray-700 transition duration-300 ease-in-out">
                    <i class="fas fa-home mr-3"></i> Dashboard
                </a>
                <a href="{{ route('tickets.index') }}" class="flex items-center py-2.5 px-6 opacity-75 hover:opacity-100 hover:bg-gray-700 transition duration-300 ease-in-out">
                    <i class="fas fa-ticket-alt mr-3"></i> Requests
                </a>
                <a href="#" class="flex items-center py-2.5 px-6 opacity-75 hover:opacity-100 hover:bg-gray-700 transition duration-300 ease-in-out">
                    <i class="fas fa-chart-line mr-3"></i> Relatórios
                </a>

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="w-full flex justify-between items-center py-2.5 px-6 opacity-75 hover:opacity-100 hover:bg-gray-700 transition duration-300 ease-in-out focus:outline-none">
                        <span><i class="fas fa-cogs mr-3"></i> Configurações</span>
                        <i class="fas fa-chevron-down transform transition-transform duration-300 ease-in-out" :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" x-cloak class="bg-gray-700 text-gray-400">
                        <a href="{{ route('categories.index') }}" class="block py-2 px-10 hover:bg-gray-600 transition duration-300 ease-in-out">Categoria</a>
                        <a href="{{ route('statuses.index') }}" class="block py-2 px-10 hover:bg-gray-600 transition duration-300 ease-in-out">Estados</a>
                        <a href="{{ route('urgencies.index') }}" class="block py-2 px-10 hover:bg-gray-600 transition duration-300 ease-in-out">Urgência</a>
                        <a href="{{ route('users.index') }}" class="block py-2 px-10 hover:bg-gray-600 transition duration-300 ease-in-out">
                           Utilizadores
                        </a>
                        @role('admin')
                            <hr class="border-gray-600 my-2">
                            <a href="{{ route('roles.index') }}" class="block py-2 px-10 hover:bg-gray-600 transition duration-300 ease-in-out">
                                <i class="fas fa-user-shield mr-2"></i> Gestão de Roles
                            </a>
                            <a href="{{ route('permissions.index') }}" class="block py-2 px-10 hover:bg-gray-600 transition duration-300 ease-in-out">
                                <i class="fas fa-key mr-2"></i> Gestão de Permissões
                            </a>
                            <a href="{{ route('licenses.index') }}" class="block py-2 px-10 hover:bg-gray-600 transition duration-300 ease-in-out">
                                <i class="fas fa-key mr-2"></i> Licenças
                            </a>
                            <a href="{{ route('acessos.index') }}" class="block py-2 px-10 hover:bg-gray-600 transition duration-300 ease-in-out">
                                <i class="fas fa-key mr-2"></i> Acessos
                            </a>
                            <a href="{{ route('grupos.index') }}" class="block py-2 px-10 hover:bg-gray-600 transition duration-300 ease-in-out">
                                <i class="fas fa-users mr-2"></i> Grupos
                            </a>
                            <a href="{{ route('postos.index') }}" class="block py-2 px-10 hover:bg-gray-600 transition duration-300 ease-in-out">
                                <i class="fas fa-briefcase mr-2"></i> Postos
                            </a>
                            <a href="{{ route('unidades.index') }}" class="block py-2 px-10 hover:bg-gray-600 transition duration-300 ease-in-out">
                                <i class="fas fa-building mr-2"></i> Unidades
                            </a>

                        @endrole
                    </div>
                </div>
            </nav>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Navbar -->
            <header class="flex justify-between items-center bg-white shadow py-4 px-6 relative">
                <div></div>
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="text-gray-600 hover:text-gray-900 px-4 focus:outline-none">
                        {{ Auth::user()->name }} <i class="fas fa-chevron-down ml-2"></i>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user mr-2"></i> Perfil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i> Sair
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200 p-6">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-gray-100 text-gray-600 text-center p-4">
                <p>&copy; 2025 MARLI. Todos os direitos reservados.</p>
            </footer>
        </div>
    </div>
</body>
</html>
<script type="module" src="/build/assets/app-DlYOw6CL.js"></script> 