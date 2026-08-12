<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTicketSubmitted extends Notification
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
            ->subject('Pedido submetido com sucesso — '.$this->ticket->code)
            ->greeting('Olá, '.$notifiable->name.'!')
            ->line('O seu pedido foi submetido com sucesso.')
            ->line('Código do pedido: '.$this->ticket->code)
            ->line('Título: '.$this->ticket->titulo)
            ->action('Ver pedido', route('tickets.show', $this->ticket))
            ->line('Guarde este código para referência futura.');
    }
}
