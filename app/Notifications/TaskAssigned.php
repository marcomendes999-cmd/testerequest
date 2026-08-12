<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssigned extends Notification
{
    use Queueable;

    public function __construct(private readonly Task $task)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $ticket = $this->task->ticket;

        return (new MailMessage)
            ->subject('Nova tarefa atribuída — '.($ticket?->code ?? 'Pedido'))
            ->greeting('Olá, '.$notifiable->name.'!')
            ->line('Foi-lhe atribuída uma nova tarefa para realizar.')
            ->line('Pedido: '.($ticket?->code ?? '—').' — '.($ticket?->titulo ?? ''))
            ->line('Tarefa: '.$this->task->titulo)
            ->when($this->task->descricao, fn (MailMessage $mail) => $mail->line('Descrição: '.$this->task->descricao))
            ->when($this->task->prazo, fn (MailMessage $mail) => $mail->line('Prazo: '.$this->task->prazo->format('d/m/Y')))
            ->action('Ver pedido', route('tickets.show', $ticket));
    }
}
