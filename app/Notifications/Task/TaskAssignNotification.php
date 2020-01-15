<?php

namespace App\Notifications\Task;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use App\Notifications\BaseNotification;

use App\Models\Task;
use Illuminate\Support\HtmlString;

class TaskAssignNotification extends BaseNotification
{
    use Queueable;
    private $task;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
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
                    ->subject(__('Mail/Task/TaskAssignNotification.subject'));

        $message = $this->intro($message, $notifiable);

        $message->line(__('Mail/Task/TaskAssignNotification.taskName', ['task_name' => $this->task->name]))
                ->line(new HtmlString(__('Mail/Task/TaskAssignNotification.description', ['description' => $this->task->description])))
                ->action(__('Mail/Task/TaskAssignNotification.seeMore'), url('/admin/task/'.$this->task->id))
                ->line(__('Mail/Task/TaskAssignNotification.footer'));

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
