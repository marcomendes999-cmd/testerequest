<x-guest-layout no-container>
    <div class="grid grid-cols-1 lg:grid-cols-2 overflow-hidden">

        {{-- LEFT SIDE — FIXED BANNER --}}
        <div class="hidden lg:flex relative">
            <img 
                src="{{ asset('img/bannerpub2.jpg') }}" 
                alt="Banner" 
                style="height: 80%;margin-top: 15%;margin-left: 5%;" 
            >
        </div>

        {{-- RIGHT SIDE — LOGIN --}}
        <div class="flex items-center justify-center p-8 bg-gray-50 dark:bg-gray-900">
            <div class="w-full max-w-md bg-white dark:bg-gray-800 p-8 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700">

                {{-- LOGO CENTRADO --}}
                <div class="flex justify-center mb-6">
                    <a href="/">
                        <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                    </a>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 text-center mb-6">
                    Welcome
                </h1>

                {{-- STATUS --}}
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" required autofocus />
                        <x-input-error :messages="$errors->get('email')" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <x-input-label for="password" value="Password" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('password')" />
                    </div>

                    {{-- Remember --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center text-gray-700 dark:text-gray-300 text-sm">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 dark:border-gray-600">
                            <span class="ml-2">Lembrar-me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:underline">
                                Esqueceu a senha?
                            </a>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <button class="w-full py-3 rounded-lg text-white font-semibold bg-indigo-600 hover:bg-indigo-700 transition shadow-md">
                        Entrar
                    </button>
                </form>
            </div>
        </div>

    </div>
</x-guest-layout>
