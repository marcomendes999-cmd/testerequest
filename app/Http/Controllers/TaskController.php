<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'ordem' => 'nullable|integer',
            'estado_id' => 'nullable|exists:statuses,id',
            'prazo' => 'nullable|date',
            'data_ini' => 'nullable|date',
            'disponivel' => 'boolean',
            'dependencia' => 'nullable|integer',
            'time' => 'nullable',
            'un_id' => 'nullable|integer',
        ]);

        $validated['ticket_id'] = $ticket->id;
        $validated['user_id'] = Auth::id();

        Task::create($validated);

        return back()->with('success', 'Task criada com sucesso.');
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'ordem' => 'nullable|integer',
            'estado_id' => 'nullable|exists:statuses,id',
            'prazo' => 'nullable|date',
            'data_ini' => 'nullable|date',
            'disponivel' => 'boolean',
            'dependencia' => 'nullable|integer',
            'time' => 'nullable',
            'un_id' => 'nullable|integer',
        ]);

        $task->update($validated);

        return back()->with('success', 'Task atualizada.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return back()->with('success', 'Task eliminada.');
    }
}
