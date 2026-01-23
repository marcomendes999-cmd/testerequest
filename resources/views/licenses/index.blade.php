@extends('layouts.app')

@section('title', 'Gestão de Licenças')

@section('content')
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Gestão de Licenças</h2>
            @role('admin')
            <a href="{{ route('licenses.create') }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i> Nova Licença
            </a>
            @endrole
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 shadow-md" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if($licenses->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <p class="text-gray-500 text-lg">Nenhuma licença encontrada.</p>
            </div>
        @else
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">ID</th>
                            <th class="py-3 px-6 text-left">Nome</th>
                            <th class="py-3 px-6 text-left">Utilizadores Máximos</th>
                            <th class="py-3 px-6 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @foreach($licenses as $license)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <span class="font-medium text-gray-700">{{ $license->id }}</span>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span class="font-medium text-gray-700">{{ $license->name }}</span>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span class="font-medium text-gray-700">{{ $license->max_users }}</span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center space-x-2">
                                        @role('admin')
                                        <a href="{{ route('licenses.edit', $license) }}" class="w-8 h-8 flex justify-center items-center rounded-full bg-yellow-100 text-yellow-600 hover:bg-yellow-200 transition duration-200 transform hover:scale-110" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('licenses.destroy', $license) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex justify-center items-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 transition duration-200 transform hover:scale-110" onclick="return confirm('Tem certeza que deseja excluir esta licença?');" title="Excluir">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $licenses->links() }}
            </div>
        @endif
    </div>
@endsection
