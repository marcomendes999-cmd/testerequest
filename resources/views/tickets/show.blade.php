@extends('layouts.app')

@section('title', 'Detalhes do Ticket')

@section('content')
    <div class="container mx-auto p-4">
        <div class="bg-white shadow-lg rounded-lg p-8 mb-8">

            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h2 class="text-3xl font-bold text-gray-800">Request #{{ $ticket->id }} - {{ $ticket->titulo }}</h2>
                <a href="{{ route('tickets.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg shadow-sm hover:bg-gray-100 transition duration-300 transform hover:scale-105">
                    <i class="fas fa-arrow-left mr-2"></i> Voltar
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Coluna da Esquerda -->
                <div class="space-y-4">
                    <p class="text-gray-600"><span class="font-semibold text-gray-800">Número do Operário:</span> {{ $ticket->num_operario ?? 'N/A' }}</p>
                    <p class="text-gray-600"><span class="font-semibold text-gray-800">Categoria:</span> {{ $ticket->categoria->name ?? 'N/A' }}</p>
                    <p class="text-gray-600"><span class="font-semibold text-gray-800">Urgência:</span> {{ $ticket->urgencia->name ?? 'N/A' }}</p>
                    <p class="text-gray-600"><span class="font-semibold text-gray-800">Estado:</span> {{ $ticket->estado->name ?? 'N/A' }}</p>
                    <p class="text-gray-600"><span class="font-semibold text-gray-800">Email:</span> {{ $ticket->email ?? 'N/A' }}</p>
                </div>
                <!-- Coluna da Direita -->
                <div class="space-y-4">
                    <p class="text-gray-600"><span class="font-semibold text-gray-800">Prazo:</span> {{ $ticket->prazo ? \Carbon\Carbon::parse($ticket->prazo)->format('d/m/Y') : 'N/A' }}</p>
                    <p class="text-gray-600"><span class="font-semibold text-gray-800">Aprovado:</span>
                        <span class="px-2 py-1 font-semibold leading-tight rounded-full {{ $ticket->aprovado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $ticket->aprovado ? 'Sim' : 'Não' }}
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
            
            <!-- Secção de Ficheiros Anexados -->
            <div class="mb-6">
                <span class="font-semibold text-gray-800 block mb-2">Ficheiros Anexados:</span>
                @if($ticket->files->isEmpty())
                    <p class="text-gray-500">Nenhum ficheiro anexado.</p>
                @else
                    <ul class="list-disc list-inside bg-gray-50 p-4 rounded-lg text-gray-700 space-y-2">
                        @foreach($ticket->files as $file)
                            <li>
                                <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="text-blue-600 hover:underline">
                                    <i class="fas fa-paperclip mr-2"></i>{{ $file->file_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
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
                <a href="{{ route('tickets.edit', $ticket) }}" class="px-6 py-2 bg-yellow-500 text-white rounded-lg shadow-lg hover:bg-yellow-600 transition duration-300 transform hover:scale-105">
                    <i class="fas fa-edit mr-2"></i> Editar Request
                </a>
                <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg shadow-lg hover:bg-red-700 transition duration-300 transform hover:scale-105" onclick="return confirm('Tem certeza que deseja excluir este Request?');">
                        <i class="fas fa-trash-alt mr-2"></i> Excluir Request
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
