<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\Ticket;
use App\Notifications\TaskAssigned;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Cria uma nova task associada a um pedido.
     *
     * O operário responsável ("user_id") é agora um dado explícito do
     * formulário — antes ficava sempre preenchido com quem criava a task,
     * o que não tinha nada a ver com quem a ia executar.
     */
    public function store(StoreTaskRequest $request, Ticket $ticket)
    {
        $dados = $request->validated();
        $dados['ticket_id'] = $ticket->id;

        // Sem estado indicado, arranca no primeiro estado disponível
        // (mesma convenção usada para os pedidos).
        $dados['estado_id'] ??= 1;

        // Sem ordem indicada, esta task fica a seguir à última do pedido.
        $dados['ordem'] ??= $ticket->tasks()->max('ordem') + 1;

        $task = $ticket->tasks()->create($dados);

        if ($task->user) {
            $task->user->notify(new TaskAssigned($task));
        }

        // O prazo do ticket acompanha a task com o prazo mais distante.
        // Uma nova task com prazo anterior não reduz o prazo já definido.
        if ($task->prazo && (!$ticket->prazo || $task->prazo->isAfter($ticket->prazo))) {
            $ticket->update(['prazo' => $task->prazo]);
        }

        $this->syncTicketStatus($ticket);

        return back()->with('success', 'Task criada com sucesso.');
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        if ($task->wasChanged('user_id') && $task->user) {
            $task->user->notify(new TaskAssigned($task));
        }

        // Ao editar, a task pode passar a ter o maior prazo ou deixar de o ter.
        // Por isso recalculamos o prazo do ticket com base em todas as suas tasks.
        $task->ticket->update([
            'prazo' => $task->ticket->tasks()->max('prazo'),
        ]);

        $this->syncTicketStatus($task->ticket);

        return back()->with('success', 'Task atualizada.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('updateProgress', $task);

        $validated = $request->validate([
            'estado_id' => ['required', 'exists:statuses,id'],
        ]);

        $task->update($validated);

        $this->syncTicketStatus($task->ticket);

        return back()->with('success', 'Estado da tarefa atualizado.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $ticket = $task->ticket;
        $task->delete();
        $this->syncTicketStatus($ticket);

        return back()->with('success', 'Task eliminada.');
    }

    private function syncTicketStatus(Ticket $ticket): void
    {
        $estadoId = $ticket->tasks()
            ->orderByDesc('ordem')
            ->orderByDesc('id')
            ->value('estado_id');

        if ($estadoId) {
            $ticket->update(['idestado' => $estadoId]);
        }
    }
}
