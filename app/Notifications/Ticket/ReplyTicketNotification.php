<?php

namespace App\Notifications\Ticket;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use Modules\TicketComment\Entities\TicketComment;

class ReplyTicketNotification extends Notification
{
    use Queueable;
    
    private $ticketComment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TicketComment $ticketComment)
    {
        $this->ticketComment = $ticketComment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line(__('Mail/Ticket/ReplyTicketNotification.ticketName', ['ticket_name' => $this->ticketComment->ticket->name]))
                    ->line(__('Mail/Ticket/ReplyTicketNotification.reply', ['reply' => $this->ticketComment->comment]))
                    ->action(__('Mail/Ticket/ReplyTicketNotification.seeMore'), url('/ticket/'. $this->ticketComment->ticket->id))
                    ->line(__('Mail/Ticket/ReplyTicketNotification.footer'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
