<?php

namespace App\Notifications\Receipt;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Receipt;

class ReceiptPaidNotification extends Notification
{
    use Queueable;
    private $receipt;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Receipt $receipt)
    {
        $this->receipt = $receipt;
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
        $message = (new MailMessage)
                    ->subject(__('Mail/Receipt/ReceiptPaidNotification.subject'))
                    ->line(__('Mail/Receipt/ReceiptPaidNotification.receiptName', ['receipt_name' => $this->receipt->name]))
                    ->line(__('Mail/Receipt/ReceiptPaidNotification.taskName', ['task_name' => $this->receipt->task->name]))
                    ->line(__('Mail/Receipt/ReceiptPaidNotification.amount', ['amount' => $this->receipt->amount]))
                    ->action(__('Mail/Receipt/ReceiptPaidNotification.seeMore'), url('/admin/receipts/list'))
                    ->line(__('Mail/Receipt/ReceiptPaidNotification.footer'));
        
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
