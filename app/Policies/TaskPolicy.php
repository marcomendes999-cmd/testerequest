<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\Ticket;
use App\Models\User;

class TaskPolicy
{
    protected function isStaff(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'tecnico']);
    }

    protected function isGroupCollaborator(User $user, Ticket $ticket): bool
    {
        return (int) $ticket->grupo?->colaborador_id === (int) $user->id;
    }

    /**
     * Quem pode criar tasks num pedido: a equipa técnica/administração.
     * (a autorização sobre o próprio pedido — ver o ticket, etc. — já é
     * validada pela TicketPolicy antes de chegar aqui)
     */
    public function create(User $user, Ticket $ticket): bool
    {
        return $this->isStaff($user) || $this->isGroupCollaborator($user, $ticket);
    }

    /**
     * Editar os dados de uma task (título, prazo, reatribuir operário, etc.):
     * reservado à equipa técnica/administração.
     */
    public function update(User $user, Task $task): bool
    {
        return $this->isStaff($user) || $this->isGroupCollaborator($user, $task->ticket);
    }

    /**
     * O operário a quem a task está atribuída pode atualizar só o progresso
     * (estado) da sua própria task, mesmo sem ser ele a geri-la por inteiro.
     * Útil se um dia abrires esta ação a mais do que admin/tecnico.
     */
    public function updateProgress(User $user, Task $task): bool
    {
        return (int) $task->user_id === (int) $user->id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $this->isStaff($user) || $this->isGroupCollaborator($user, $task->ticket);
    }
}
