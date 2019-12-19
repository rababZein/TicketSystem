<?php

namespace App\Notifications\Project;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Project;

class ProjectAssignNotification extends Notification
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
        return (new MailMessage)
                    ->subject(__('Mail/Project/ProjectAssignNotification.subject'))
                    ->line(__('Mail/Project/ProjectAssignNotification.projectName', ['project_name' => $this->project->name]))
                    ->line(__('Mail/Project/ProjectAssignNotification.description', ['description' => $this->project->description]))
                    ->line(__('Mail/Project/ProjectAssignNotification.owner', ['owner', $this->project->owner->name]))
                    ->action(__('Mail/Project/ProjectAssignNotification.seeMore'), url('/project/'. $this->project->id))
                    ->line(__('Mail/Project/ProjectAssignNotification.footer'));
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
