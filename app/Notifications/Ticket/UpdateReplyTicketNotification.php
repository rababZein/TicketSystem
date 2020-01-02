<?php

namespace App\Notifications\Ticket;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use Modules\TicketComment\Entities\TicketComment;

class UpdateReplyTicketNotification extends Notification
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
                    ->subject(__('Mail/Ticket/UpdateReplyTicketNotification.subject'))
                    ->line(__('Mail/Ticket/UpdateReplyTicketNotification.ticketName', ['ticket_name' => $this->ticketComment->ticket->name]))
                    ->line(__('Mail/Ticket/UpdateReplyTicketNotification.reply', ['reply' => $this->ticketComment->comment]))
                    ->action(__('Mail/Ticket/UpdateReplyTicketNotification.seeMore'), url('/admin/ticket/'. $this->ticketComment->ticket->id))
                    ->line(__('Mail/Ticket/UpdateReplyTicketNotification.footer'))
                    ->cc($cc);
            
        saveSysMailToSentFolder($notifiable->email, $message->data(), $cc);

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
