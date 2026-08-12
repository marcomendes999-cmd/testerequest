@extends('layouts.app')

@section('title', 'Detalhes do Ticket')

@section('content')
    <div class="container mx-auto p-4">
        <div class="bg-white shadow-lg rounded-lg p-8 mb-8">

            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h2 class="text-3xl font-bold text-gray-800">Pedido #{{ $ticket->code }} - {{ $ticket->titulo }}</h2>
                <a href="{{ route('tickets.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg shadow-sm hover:bg-gray-100 transition duration-300 transform hover:scale-105">
                    <i class="fas fa-arrow-left mr-2"></i> Voltar
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Coluna da Esquerda -->
                <div class="space-y-4">
                    <p class="text-gray-600"><span class="font-semibold text-gray-800">Código:</span> {{ $ticket->code ?? '—' }}</p>
                    <p class="text-gray-600"><span class="font-semibold text-gray-800">Grupo:</span> {{ $ticket->grupo->name ?? 'N/A' }}</p>
                    <p class="text-gray-600"><span class="font-semibold text-gray-800">Urgência:</span> {{ $ticket->urgencia->name ?? 'N/A' }}</p>
                    <p class="text-gray-600"><span class="font-semibold text-gray-800">Estado:</span> {{ $ticket->estado->name ?? 'N/A' }}</p>
                    <p class="text-gray-600"><span class="font-semibold text-gray-800">Email:</span> {{ $ticket->email ?? 'N/A' }}</p>
                </div>
                <!-- Coluna da Direita -->
                <div class="space-y-4">
                    <p class="text-gray-600"><span class="font-semibold text-gray-800">Prazo:</span> {{ $ticket->prazo?->format('d/m/Y') ?? 'Não definido' }}</p>
                    <p class="text-gray-600"><span class="font-semibold text-gray-800">Aprovado:</span>
                        <span class="px-2 py-1 font-semibold leading-tight rounded-full {{ $ticket->aprovado === 2 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $ticket->aprovado === 2 ? 'Sim' : 'Não' }}
                        </span>
                    </p>
                    <p class="text-gray-600"><span class="font-semibold text-gray-800">Criado por:</span> {{ $ticket->user->name ?? 'N/A' }}</p>
                    <p class="text-gray-600"><span class="font-semibold text-gray-800">Data de Criação:</span> {{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:i') }}</p>
                    <p class="text-gray-600"><span class="font-semibold text-gray-800">Última Atualização:</span> {{ \Carbon\Carbon::parse($ticket->updated_at)->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <div class="mb-6">
                <span class="font-semibold text-gray-800 block mb-2">Descrição:</span>
                <div class="bg-gray-50 p-4 rounded-lg text-gray-700">
                    {{ $ticket->descricao }}
                </div>
            </div>
            
            @if(Auth::user()->tipo?->name === 'colaborador')
            <!-- Secção de Tarefas -->
            <div class="mb-6">
                <span class="font-semibold text-gray-800 block mb-2">
                    Tarefas
                    @if($ticket->tasks->count())
                        <span class="text-sm font-normal text-gray-500">({{ $ticket->tasks->count() }})</span>
                    @endif
                </span>

                @if($ticket->tasks->isEmpty())
                    <p class="text-gray-500 mb-3">Ainda sem tarefas para este pedido.</p>
                @else
                    <div class="bg-gray-50 rounded-lg overflow-hidden mb-3">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-100 text-gray-600 text-left">
                                <tr>
                                    <th class="px-4 py-2">Título</th>
                                    <th class="px-4 py-2">Estado</th>
                                    <th class="px-4 py-2">Operário</th>
                                    <th class="px-4 py-2">Prazo</th>
                                    @if($ticket->tasks->contains('user_id', Auth::id()))
                                        <th class="px-4 py-2">Atualizar estado</th>
                                    @endif
                                    @hasanyrole('tecnico|admin')
                                        <th class="px-4 py-2 text-center">Ações</th>
                                    @endhasanyrole
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ticket->tasks as $task)
                                    <tr class="border-t border-gray-200">
                                        <td class="px-4 py-2">{{ $task->titulo }}</td>
                                        <td class="px-4 py-2">{{ $task->estado->name ?? '—' }}</td>
                                        <td class="px-4 py-2">
                                            @if($task->user)
                                                {{ $task->user->name }}
                                            @else
                                                <span class="text-gray-400 italic">Por atribuir</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2">{{ $task->prazo_formatado ?? '—' }}</td>
                                        @if($ticket->tasks->contains('user_id', Auth::id()))
                                            <td class="px-4 py-2">
                                                @if((int) $task->user_id === (int) Auth::id())
                                                    <form action="{{ route('tasks.status.update', $task) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <select name="estado_id" class="rounded-md border-gray-300 py-1 text-xs" onchange="this.form.submit()">
                                                            @foreach($estados as $estado)
                                                                <option value="{{ $estado->id }}" @selected((int) $task->estado_id === (int) $estado->id)>{{ $estado->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </form>
                                                @else
                                                    <span class="text-gray-400">&mdash;</span>
                                                @endif
                                            </td>
                                        @endif
                                        @hasanyrole('tecnico|admin')
                                            <td class="px-4 py-2 text-center">
                                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Eliminar esta tarefa?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:underline text-xs">
                                                        <i class="fas fa-trash-alt"></i> Eliminar
                                                    </button>
                                                </form>
                                            </td>
                                        @endhasanyrole
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @can('create', [App\Models\Task::class, $ticket])
                    <form action="{{ route('tasks.store', $ticket) }}" method="POST" class="bg-gray-50 p-4 rounded-lg">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Nova tarefa</label>
                                <input type="text" name="titulo" required placeholder="Título da tarefa" class="w-full rounded-md border-gray-300 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Atribuir a</label>
                                <select name="user_id" class="w-full rounded-md border-gray-300 shadow-sm text-sm">
                                    <option value="">Por atribuir</option>
                                    @foreach($tecnicos as $tecnico)
                                        <option value="{{ $tecnico->id }}">{{ $tecnico->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Prazo</label>
                                <input type="date" name="prazo" class="w-full rounded-md border-gray-300 shadow-sm text-sm">
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="mb-1 block text-xs font-medium text-gray-600">Descrição da tarefa</label>
                            <textarea name="descricao" rows="3" placeholder="Descreva o trabalho a realizar" class="w-full rounded-md border-gray-300 shadow-sm text-sm">{{ old('descricao') }}</textarea>
                        </div>
                        <button type="submit" class="mt-3 px-4 py-1.5 bg-yellow-500 text-white text-sm rounded-lg shadow hover:bg-yellow-600 transition">
                            <i class="fas fa-plus mr-1"></i> Adicionar tarefa
                        </button>
                        @error('user_id')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </form>
                @endcan
            </div>

            @endif

            <!-- Secção de Ficheiros Anexados -->
            <div class="mb-6">
                <span class="font-semibold text-gray-800 block mb-2">Ficheiros Anexados:</span>
                @if($ticket->files->isEmpty())
                    <p class="text-gray-500 mb-3">Nenhum ficheiro anexado.</p>
                @else
                    <ul class="bg-gray-50 p-4 rounded-lg text-gray-700 space-y-2 mb-3">
                        @foreach($ticket->files as $file)
                            <li class="flex items-center justify-between">
                                <a href="{{ route('tickets.files.download', [$ticket, $file]) }}" class="text-blue-600 hover:underline">
                                    <i class="fas fa-paperclip mr-2"></i>{{ $file->file_name }}
                                </a>
                                <form action="{{ route('tickets.file.delete', [$ticket, $file]) }}" method="POST" onsubmit="return confirm('Remover este ficheiro?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <form action="{{ route('tickets.files.store', $ticket) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-3">
                    @csrf
                    <input type="file" name="files[]" multiple class="text-sm text-gray-600" required>
                    <button type="submit" class="px-4 py-1.5 bg-gray-700 text-white text-sm rounded-lg shadow hover:bg-gray-800 transition">
                        <i class="fas fa-upload mr-1"></i> Anexar
                    </button>
                </form>
                @error('files.*')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Secção de Mensagens -->
            <div class="mt-8 border-t pt-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">Mensagens</h3>
                
                <!-- Lista de Mensagens -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-inner mb-6 max-h-80 overflow-y-auto">
                    @foreach($ticket->messages as $message)
                        <div class="mb-4 p-4 rounded-lg {{ Auth::id() === $message->user->id ? 'bg-blue-100' : 'bg-gray-200' }}">
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
                        <textarea name="content" id="content" rows="3" class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Escreva a sua mensagem..." required></textarea>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-lg hover:bg-indigo-700 transition duration-300 transform hover:scale-105">
                            <i class="fas fa-paper-plane mr-2"></i> Enviar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Botões de Ação -->
            <div class="flex space-x-4 mt-6">
                @can('update', $ticket)
                <a href="{{ route('tickets.edit', $ticket) }}" class="px-6 py-2 bg-yellow-500 text-white rounded-lg shadow-lg hover:bg-yellow-600 transition duration-300 transform hover:scale-105">
                    <i class="fas fa-edit mr-2"></i> Editar Pedido
                </a>
                @endcan
                <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg shadow-lg hover:bg-red-700 transition duration-300 transform hover:scale-105" onclick="return confirm('Tem certeza que deseja excluir este Pedido?');">
                        <i class="fas fa-trash-alt mr-2"></i> Excluir Pedido
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
