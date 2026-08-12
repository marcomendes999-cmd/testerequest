<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * Roles que têm acesso "de suporte" a todos os tickets, independentemente
     * de quem os criou (equipa técnica / administração).
     */
    protected function isStaff(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'tecnico']);
    }

    /**
     * Neste momento o "dono" do ticket é quem está guardado em `userid`
     * (é o utilizador que o TicketController::store() regista ao criar o
     * ticket). Se no futuro separares "requerente" de "técnico atribuído",
     * troca esta comparação para o campo correto.
     */
    protected function isOwner(User $user, Ticket $ticket): bool
    {
        return (string) $user->id === (string) $ticket->userid;
    }

    protected function isGroupCollaborator(User $user, Ticket $ticket): bool
    {
        return (int) $ticket->grupo?->colaborador_id === (int) $user->id;
    }

    protected function hasAssignedTask(User $user, Ticket $ticket): bool
    {
        return $ticket->tasks()->where('user_id', $user->id)->exists();
    }

    /**
     * Quem pode ver a listagem de tickets.
     * O scoping (quais tickets aparecem) é feito no TicketController::index(),
     * esta ability só controla o acesso à página em si.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Quem pode ver um ticket específico — aqui é que fechamos o IDOR:
     * só a equipa de suporte ou o dono do ticket.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        return $this->isOwner($user, $ticket)
            || $this->isGroupCollaborator($user, $ticket)
            || $this->hasAssignedTask($user, $ticket);
    }

    /**
     * Qualquer utilizador autenticado pode criar um pedido.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Só a equipa técnica/administração edita os dados do ticket
     * (categoria, urgência, estado, etc.).
     */
    public function update(User $user, Ticket $ticket): bool
    {
        return $this->isOwner($user, $ticket)
            && strcasecmp((string) $user->tipo?->name, 'cliente') === 0;
    }

    public function manageTasks(User $user, Ticket $ticket): bool
    {
        return $this->isStaff($user) || $this->isGroupCollaborator($user, $ticket);
    }

    public function approve(User $user, Ticket $ticket): bool
    {
        return $this->isGroupCollaborator($user, $ticket);
    }

    /**
     * Eliminar um ticket é uma ação destrutiva — reservada ao admin.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Quem pode enviar mensagens no ticket: as mesmas pessoas que o podem ver.
     */
    public function addMessage(User $user, Ticket $ticket): bool
    {
        return $this->view($user, $ticket);
    }

    /**
     * Quem pode anexar/remover ficheiros: as mesmas pessoas que o podem ver.
     */
    public function manageFiles(User $user, Ticket $ticket): bool
    {
        return $this->view($user, $ticket);
    }
}
