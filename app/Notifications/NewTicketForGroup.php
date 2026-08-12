<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTicketForGroup extends Notification
{
    use Queueable;

    public function __construct(private readonly Ticket $ticket)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Novo pedido atribuído ao seu grupo')
            ->greeting('Olá, '.$notifiable->name.'!')
            ->line('Entrou um novo pedido para o grupo '.$this->ticket->grupo?->name.'.')
            ->line('Título: '.$this->ticket->titulo)
            ->action('Abrir pedido', route('tickets.edit', $this->ticket))
            ->line('Crie as tasks necessárias e atribua-as a um técnico.');
    }
}
