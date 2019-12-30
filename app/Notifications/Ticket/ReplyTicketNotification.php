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
        $cc = [];
        if($this->ticketComment->ticket->mails){
            foreach ($this->ticketComment->ticket->mails as $mail) {
                $cc[] = $mail->email;
            }
            
        }
        $message = (new MailMessage)
                    ->subject(__('Mail/Ticket/ReplyTicketNotification.subject'))
                    ->line(__('Mail/Ticket/ReplyTicketNotification.ticketName', ['ticket_name' => $this->ticketComment->ticket->name]))
                    ->line(__('Mail/Ticket/ReplyTicketNotification.reply', ['reply' => $this->ticketComment->comment]))
                    ->action(__('Mail/Ticket/ReplyTicketNotification.seeMore'), url('/admin/ticket/'. $this->ticketComment->ticket->id))
                    ->line(__('Mail/Ticket/ReplyTicketNotification.footer'))
                    ->cc($cc);
    
        saveSysMailToSentFolder($notifiable->email, $message->data());

        return $message;
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
