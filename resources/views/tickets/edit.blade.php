@extends('layouts.app')

@section('title', 'Editar Request')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border-2 border-gray-100">
                <div class="p-8 bg-gray-50 border-b border-gray-200" x-data="{ activeTab: 'formulario' }">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Editar Request #{{ $ticket->id }}</h2>
                    <p class="text-gray-600 mb-6">Altere os campos para atualizar o pedido.</p>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Abas -->
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <button @click="activeTab = 'formulario'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'formulario', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'formulario'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition duration-150 ease-in-out">
                                Formulário
                            </button>
                            <button @click="activeTab = 'mensagens'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'mensagens', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'mensagens'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition duration-150 ease-in-out">
                                Mensagens
                            </button>
                            <button @click="activeTab = 'ficheiros'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'ficheiros', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'ficheiros'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition duration-150 ease-in-out">
                                Ficheiros
                            </button>
                             @role('tecnico')
                            <button @click="activeTab = 'tasks'"
                                :class="{ 'border-b-2 border-yellow-500 text-yellow-600': activeTab === 'tasks' }"
                                class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-yellow-600">Tasks</button>
                            @endrole
                        </nav>
                    </div>

                    <!-- Conteúdo das Abas -->
                    <div class="mt-8">
                        <!-- Aba: Formulário -->
                        <div x-show="activeTab === 'formulario'">
                            @role('tecnico')
                                <!-- FORMULÁRIO EDITÁVEL PARA TÉCNICOS -->
                                <form action="{{ route('tickets.update', $ticket) }}" method="POST" class="space-y-6">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div>
                                            <label for="titulo" class="block text-sm font-medium text-gray-700">Título</label>
                                            <input type="text" name="titulo" id="titulo"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                value="{{ old('titulo', $ticket->titulo) }}" required readonly>
                                        </div>

                                        <div>
                                            <label for="num_operario" class="block text-sm font-medium text-gray-700">Número</label>
                                            <input type="text" name="num_operario" id="num_operario"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                value="{{ old('num_operario', $ticket->num_operario) }}" readonly >
                                        </div>

                                        <div>
                                            <label for="idcategoria" class="block text-sm font-medium text-gray-700">Categoria</label>
                                            <select name="idcategoria" id="idcategoria"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                required>
                                                <option value="">Selecione a Categoria</option>
                                                @foreach($categorias as $categoria)
                                                    <option value="{{ $categoria->id }}" {{ old('idcategoria', $ticket->idcategoria) == $categoria->id ? 'selected' : '' }}>
                                                        {{ $categoria->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label for="idurgencia" class="block text-sm font-medium text-gray-700">Urgência</label>
                                            <select name="idurgencia" id="idurgencia"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                required readonly>
                                                <option value="">Selecione a Urgência</option>
                                                @foreach($urgencias as $urgencia)
                                                    <option value="{{ $urgencia->id }}" {{ old('idurgencia', $ticket->idurgencia) == $urgencia->id ? 'selected' : '' }}>
                                                        {{ $urgencia->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label for="idestado" class="block text-sm font-medium text-gray-700">Estado</label>
                                            <select name="idestado" id="idestado"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                required>
                                                <option value="">Selecione o Estado</option>
                                                @foreach($estados as $estado)
                                                    <option value="{{ $estado->id }}" {{ old('idestado', $ticket->idestado) == $estado->id ? 'selected' : '' }}>
                                                        {{ $estado->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label for="prazo" class="block text-sm font-medium text-gray-700">Prazo</label>
                                            <input type="date" name="prazo" id="prazo"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                value="{{ old('prazo', $ticket->prazo ? \Carbon\Carbon::parse($ticket->prazo)->format('Y-m-d') : null) }}" readonly>
                                        </div>

                                        <div class="md:col-span-2">
                                            <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
                                            <textarea name="descricao" id="descricao" rows="4"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                required readonly>{{ old('descricao', $ticket->descricao) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between mt-6">
                                        <a href="{{ route('tickets.show', $ticket) }}"
                                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg shadow-sm hover:bg-gray-100 transition duration-300">
                                            <i class="fas fa-arrow-left mr-2"></i> Cancelar
                                        </a>
                                        <button type="submit"
                                            class="px-6 py-2 bg-yellow-500 text-white font-semibold rounded-md shadow-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition duration-150 ease-in-out">
                                            <i class="fas fa-save mr-2"></i> Salvar Alterações
                                        </button>
                                    </div>
                                </form>
                            @elserole('cliente')
                                <!-- VERSÃO SOMENTE LEITURA PARA CLIENTES -->
                                <div class="space-y-6 bg-gray-50 p-6 rounded-md border border-gray-200">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Título</label>
                                            <p class="mt-1 text-gray-900 font-semibold">{{ $ticket->titulo }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Número do Operário</label>
                                            <p class="mt-1 text-gray-900">{{ $ticket->num_operario ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Categoria</label>
                                            <p class="mt-1 text-gray-900">{{ $ticket->categoria->name ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Urgência</label>
                                            <p class="mt-1 text-gray-900">{{ $ticket->urgencia->name ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Estado</label>
                                            <p class="mt-1 text-gray-900">{{ $ticket->estado->name ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Fatura</label>
                                            <p class="mt-1 text-gray-900">{{ $ticket->numero_fatura }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Prazo</label>
                                            <p class="mt-1 text-gray-900">
                                                {{ $ticket->prazo ? \Carbon\Carbon::parse($ticket->prazo)->format('d/m/Y') : 'N/A' }}
                                            </p>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700">Descrição</label>
                                            <p class="mt-1 text-gray-900 whitespace-pre-line">{{ $ticket->descricao }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endrole
                        </div>


                        <!-- Aba: Mensagens -->
                        <div x-show="activeTab === 'mensagens'">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6">Mensagens do Ticket</h3>

                            <div class="bg-gray-100 p-6 rounded-lg shadow-inner mb-6 max-h-80 overflow-y-auto space-y-4">
                                @foreach($ticket->messages as $message)
                                    <div class="p-4 rounded-lg {{ Auth::id() === $message->user->id ? 'bg-blue-100' : 'bg-white' }}">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="font-semibold text-gray-800">{{ $message->user->name }}</span>
                                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($message->created_at)->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-700">{{ $message->content }}</p>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Formulário de Nova Mensagem -->
                            <form action="{{ route('tickets.message.store', $ticket) }}" method="POST">
                                @csrf
                                <div class="flex items-start space-x-4">
                                    <textarea name="content" id="content_msg" rows="3" class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Escreva a sua mensagem..." required></textarea>
                                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-lg hover:bg-indigo-700 transition duration-300 transform hover:scale-105">
                                        <i class="fas fa-paper-plane mr-2"></i> Enviar
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Aba: Ficheiros -->
                        <div x-show="activeTab === 'ficheiros'">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6">Ficheiros Anexados</h3>
                            
                            <!-- Lista de Ficheiros Existentes -->
                            <div class="bg-gray-100 p-6 rounded-lg shadow-inner mb-6">
                                <ul class="list-disc list-inside text-gray-700 space-y-2">
                                    @foreach($ticket->files as $file)
                                        <li class="flex justify-between items-center">
                                            <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="text-blue-600 hover:underline">
                                                <i class="fas fa-file-alt mr-2"></i> {{ $file->file_name }}
                                            </a>
                                            <form action="{{ route('tickets.file.delete', $file) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 transition duration-300" onclick="return confirm('Tem certeza que deseja remover este ficheiro?');">
                                                    Remover
                                                </button>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Formulário para Adicionar Novos Ficheiros -->
                            <form action="{{ route('tickets.files.store', $ticket) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label for="files" class="block text-sm font-medium text-gray-700">Adicionar Novos Ficheiros</label>
                                        <input type="file" name="files[]" id="files" multiple class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @error('files.*')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-lg hover:bg-indigo-700 transition duration-300 transform hover:scale-105">
                                        <i class="fas fa-upload mr-2"></i> Fazer Upload
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Aba: Tasks -->
                        <div x-show="activeTab === 'tasks'" class="mt-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tasks deste Pedido</h3>

                            <!-- FORMULÁRIO PARA NOVA TASK -->
                            <form action="{{ route('tasks.store', $ticket) }}" method="POST" class="space-y-4 bg-gray-50 p-4 rounded-lg mb-6">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Título</label>
                                        <input type="text" name="titulo" required class="w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Prazo</label>
                                        <input type="date" name="prazo" class="w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                                        <select name="estado_id" class="w-full rounded-md border-gray-300 shadow-sm">
                                            <option value="">Selecione...</option>
                                            @foreach($estados as $estado)
                                                <option value="{{ $estado->id }}">{{ $estado->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Descrição</label>
                                    <textarea name="descricao" rows="2" class="w-full rounded-md border-gray-300 shadow-sm"></textarea>
                                </div>

                                <button type="submit"
                                    class="px-4 py-2 bg-yellow-500 text-white font-semibold rounded-md shadow hover:bg-yellow-600 transition">
                                    Adicionar Task
                                </button>
                            </form>

                            <!-- LISTAGEM DE TASKS -->
                                    <div x-data="{ showEditModal: false, taskToEdit: {} }">
                                        <table class="min-w-full bg-white border border-gray-200 shadow rounded-lg">
                                            <thead class="bg-gray-100 text-gray-700">
                                                <tr>
                                                    <th class="px-4 py-2 text-left">Título</th>
                                                    <th class="px-4 py-2 text-left">Estado</th>
                                                    <th class="px-4 py-2 text-left">Prazo</th>
                                                    <th class="px-4 py-2 text-left">Ordem</th>
                                                    <th class="px-4 py-2 text-center">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($ticket->tasks ?? [] as $task)
                                                    <tr class="border-t">
                                                        <td class="px-4 py-2">{{ $task->titulo }}</td>
                                                        <td class="px-4 py-2">{{ $task->estado->name ?? '—' }}</td>
                                                        <td class="px-4 py-2">{{ $task->prazo ? \Carbon\Carbon::parse($task->prazo)->format('d/m/Y') : '—' }}</td>
                                                        <td class="px-4 py-2">{{ $task->ordem }}</td>
                                                        <td class="px-4 py-2 text-center space-x-2">
                                                            <!-- Botão Editar -->
                                                            <button 
                                                                @click="showEditModal = true; taskToEdit = {{ $task->toJson() }}"
                                                                class="text-blue-500 hover:underline">
                                                                Editar
                                                            </button>

                                                            <!-- Botão Apagar -->
                                                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-500 hover:underline">
                                                                    Apagar
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                                                            Sem tasks registadas.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                        <!-- 🔹 Modal de Edição -->
                                        <div 
                                            x-show="showEditModal" 
                                            class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50"
                                            x-transition
                                        >
                                            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
                                                <h2 class="text-lg font-semibold mb-4">Editar Task</h2>

                                                <form :action="`/tasks/${taskToEdit.id}`" method="POST" class="space-y-4">
                                                    @csrf
                                                    @method('PUT')

                                                    <div>
                                                        <label class="block text-gray-700 text-sm font-medium mb-1">Título</label>
                                                        <input type="text" name="titulo" x-model="taskToEdit.titulo" class="w-full border rounded p-2">
                                                    </div>

                                                    <div>
                                                        <label class="block text-gray-700 text-sm font-medium mb-1">Descrição</label>
                                                        <textarea name="descricao" x-model="taskToEdit.descricao" class="w-full border rounded p-2"></textarea>
                                                    </div>

                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-gray-700 text-sm font-medium mb-1">Prazo</label>
                                                            <input type="date" name="prazo" x-model="taskToEdit.prazo" class="w-full border rounded p-2">
                                                        </div>
                                                        <div>
                                                            <label class="block text-gray-700 text-sm font-medium mb-1">Ordem</label>
                                                            <input type="number" name="ordem" x-model="taskToEdit.ordem" class="w-full border rounded p-2">
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label class="block text-gray-700 text-sm font-medium mb-1">Estado</label>
                                                        <select name="estado_id" x-model="taskToEdit.estado_id" class="w-full border rounded p-2">
                                                            @foreach($estados as $estado)
                                                                <option value="{{ $estado->id }}">{{ $estado->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="flex justify-end space-x-2 mt-4">
                                                        <button 
                                                            type="button" 
                                                            @click="showEditModal = false" 
                                                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
                                                        >
                                                            Cancelar
                                                        </button>
                                                        <button 
                                                            type="submit" 
                                                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                                                        >
                                                            Guardar
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
