<!-- Logo -->
<div class="p-6 border-b border-gray-800/50 flex justify-center">
    <a href="/dashboard" class="block hover:scale-105 transition">
        <img
            src="{{ asset('img/mrequest_logo.png') }}"
            alt="MRequest"
            class="h-12 object-contain brightness-110"
        >
    </a>
</div>

<!-- Navigation -->
<nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">

    <a href="/dashboard"
       class="group flex items-center px-4 py-3 rounded-xl hover:bg-indigo-900/20
       {{ request()->is('dashboard*') ? 'active-link' : '' }}">
        <i class="fas fa-home w-6 text-center"></i>
        <span class="ml-4 font-semibold">Dashboard</span>
    </a>

    <a href="{{ route('tickets.index') }}"
       class="group flex items-center px-4 py-3 rounded-xl hover:bg-indigo-900/20
       {{ request()->routeIs('tickets.*') ? 'active-link' : '' }}">
        <i class="fas fa-ticket-alt w-6 text-center"></i>
        <span class="ml-4 font-semibold">Requests</span>
    </a>

    <a href="#"
       class="group flex items-center px-4 py-3 rounded-xl hover:bg-indigo-900/20">
        <i class="fas fa-chart-line w-6 text-center"></i>
        <span class="ml-4 font-semibold">Relatórios</span>
    </a>

    <!-- Configurações -->
    <div
        x-data="{ open: {{ request()->routeIs(
            'categories.*','statuses.*','urgencies.*','users.*',
            'roles.*','permissions.*','licenses.*',
            'acessos.*','grupos.*','postos.*','unidades.*'
        ) ? 'true' : 'false' }} }"
        class="space-y-1"
    >

        <button @click="open = !open"
                class="w-full flex items-center justify-between px-4 py-3 rounded-xl hover:bg-indigo-900/20">
            <div class="flex items-center">
                <i class="fas fa-cog w-6 text-center"></i>
                <span class="ml-4 font-semibold">Configurações</span>
            </div>
            <i class="fas fa-chevron-down transition-transform"
               :class="{ 'rotate-180': open }"></i>
        </button>

        <div x-show="open" x-transition class="pl-4 space-y-1">
            <a href="{{ route('categories.index') }}" class="block px-4 py-2 rounded-lg text-sm hover:bg-indigo-900/30">Categorias</a>
            <a href="{{ route('statuses.index') }}"   class="block px-4 py-2 rounded-lg text-sm hover:bg-indigo-900/30">Estados</a>
            <a href="{{ route('urgencies.index') }}"  class="block px-4 py-2 rounded-lg text-sm hover:bg-indigo-900/30">Urgência</a>
            <a href="{{ route('users.index') }}"      class="block px-4 py-2 rounded-lg text-sm hover:bg-indigo-900/30">Utilizadores</a>

            @role('admin')
                <div class="border-t border-gray-700 my-3"></div>

                <a href="{{ route('roles.index') }}"       class="block px-4 py-2 rounded-lg text-sm hover:bg-indigo-900/30">
                    <i class="fas fa-user-shield mr-2"></i> Roles
                </a>
                <a href="{{ route('permissions.index') }}" class="block px-4 py-2 rounded-lg text-sm hover:bg-indigo-900/30">
                    <i class="fas fa-key mr-2"></i> Permissões
                </a>
                <a href="{{ route('licenses.index') }}"    class="block px-4 py-2 rounded-lg text-sm hover:bg-indigo-900/30">
                    <i class="fas fa-id-card mr-2"></i> Licenças
                </a>
                <a href="{{ route('acessos.index') }}"     class="block px-4 py-2 rounded-lg text-sm hover:bg-indigo-900/30">
                    <i class="fas fa-shield-alt mr-2"></i> Acessos
                </a>
                <a href="{{ route('grupos.index') }}"      class="block px-4 py-2 rounded-lg text-sm hover:bg-indigo-900/30">
                    <i class="fas fa-users mr-2"></i> Grupos
                </a>
                <a href="{{ route('postos.index') }}"      class="block px-4 py-2 rounded-lg text-sm hover:bg-indigo-900/30">
                    <i class="fas fa-briefcase mr-2"></i> Postos
                </a>
                <a href="{{ route('unidades.index') }}"    class="block px-4 py-2 rounded-lg text-sm hover:bg-indigo-900/30">
                    <i class="fas fa-building mr-2"></i> Unidades
                </a>
            @endrole
        </div>
    </div>

</nav>

<!-- Footer -->
<div class="p-4 border-t border-gray-800 text-center text-xs text-gray-400">
    © 2026 MARLI
</div>
