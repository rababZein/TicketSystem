<?php

namespace App\Notifications\Project;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use App\Notifications\BaseNotification;

use App\Models\Project;

class ProjectAssignNotification extends BaseNotification
{
    use Queueable;
    private $project;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Project $project)
    {        
        $this->project = $project;
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
        ->subject(__('Mail/Project/ProjectAssignNotification.subject'));

        $message = $this->intro($message, $notifiable);

        $message->line(__('Mail/Project/ProjectAssignNotification.projectName', ['project_name' => $this->project->name]))
        ->line(__('Mail/Project/ProjectAssignNotification.description', ['description' => $this->project->description]))
        ->line(__('Mail/Project/ProjectAssignNotification.owner', ['owner' => $this->project->owner->name]))
        ->action(__('Mail/Project/ProjectAssignNotification.seeMore'), url('/admin/project/'. $this->project->id))
        ->line(__('Mail/Project/ProjectAssignNotification.footer'));

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
