@props(['noContainer' => false])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        

        <link rel="stylesheet" href="/build/assets/app-BU5TSkbz.css">
    </head>

    <body class="font-sans text-gray-900 antialiased">

        @if ($noContainer)
            {{-- Layout limpo (ideal para login com banner) --}}
            {{ $slot }}
        @else
            {{-- Layout tradicional do Breeze --}}
            <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
                <div class="w-full sm:max-w-5xl mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                    {{ $slot }}
                </div>
            </div>
        @endif

    </body>
</html>

<script type="module" src="/build/assets/app-DLYOw6CL.js"></script> 