@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border-2 border-gray-100">
            <div class="p-8 bg-gray-50 border-b border-gray-200">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Novo pedido</h2>
                <p class="text-gray-600 mb-6">Preencha os campos para abrir um novo pedido.</p>
                
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                
                <form action="{{ route('tickets.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label for="titulo" class="block text-sm font-medium text-gray-700">Título</label>
                            <input type="text" name="titulo" id="titulo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out" value="{{ old('titulo') }}" required>
                            @error('titulo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--div>
                            <label for="num_operario" class="block text-sm font-medium text-gray-700">Número</label>
                            <input type="text" name="num_operario" id="num_operario" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out" value="{{ Auth::id() }}" readonly>
                            @error('num_operario')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div-->

                        <div>
                            <label for="grupo_id" class="block text-sm font-medium text-gray-700">Grupo</label>
                            <select name="grupo_id" id="grupo_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out" required>
                                <option value="">Selecione o Grupo</option>
                                @foreach($grupos as $grupo)
                                    <option value="{{ $grupo->id }}" {{ old('grupo_id') == $grupo->id ? 'selected' : '' }}>{{ $grupo->name }}</option>
                                @endforeach
                            </select>
                            @error('grupo_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="idurgencia" class="block text-sm font-medium text-gray-700">Urgência</label>
                            <select name="idurgencia" id="idurgencia" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out" required>
                                <option value="">Selecione a Urgência</option>
                                @foreach($urgencias as $urgencia)
                                    <option value="{{ $urgencia->id }}" {{ old('idurgencia') == $urgencia->id ? 'selected' : '' }}>{{ $urgencia->name }}</option>
                                @endforeach
                            </select>
                            @error('idurgencia')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="prazo" class="block text-sm font-medium text-gray-700">Prazo</label>
                            <input type="date" name="prazo" id="prazo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out" value="{{ old('prazo') }}">
                            @error('prazo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out" value="{{ Auth::user()->email }}" readonly>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div-->

                        <!-- Campo atualizado para múltiplos ficheiros -->
                        <div>
                            <label for="files" class="block text-sm font-medium text-gray-700">Anexar Ficheiros (Opcional)</label>
                            <input type="file" name="files[]" id="files" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out" multiple>
                            @error('file')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
                            <textarea name="descricao" id="descricao" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out" required>{{ old('descricao') }}</textarea>
                            @error('descricao')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2 flex justify-end mt-4">
                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                Criar Ticket
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
