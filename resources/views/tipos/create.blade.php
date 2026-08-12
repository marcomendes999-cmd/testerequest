@extends('layouts.app')
@section('title', 'Criar tipo')
@section('content')
<div class="container mx-auto flex justify-center p-4"><div class="w-full max-w-lg rounded-lg bg-white p-8 shadow-lg"><h2 class="mb-6 text-center text-3xl font-bold text-gray-800">Novo tipo</h2><form action="{{ route('tipos.store') }}" method="POST">@csrf <label for="name" class="mb-2 block text-sm font-semibold text-gray-700">Nome</label><input name="name" id="name" value="{{ old('name') }}" required class="w-full rounded-lg border px-4 py-2">@error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror <div class="mt-6 flex justify-between"><a href="{{ route('tipos.index') }}" class="rounded-lg border px-6 py-2">Voltar</a><button class="rounded-lg bg-blue-600 px-6 py-2 text-white">Criar</button></div></form></div></div>
@endsection
