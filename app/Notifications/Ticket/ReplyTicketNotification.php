<?php

namespace App\Notifications\Ticket;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use App\Notifications\BaseNotification;

use Modules\TicketComment\Entities\TicketComment;

class ReplyTicketNotification extends BaseNotification
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
                    ->view('emails.ticket-reply', ['ticketComment' => $this->ticketComment,'notifiable' => $notifiable])
                    ->subject(__('Mail/Ticket/ReplyTicketNotification.subject'))
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
