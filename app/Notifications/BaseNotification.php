<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class BaseNotification extends Notification
{
    public function intro($message, $notifiable)
    {
        if ($notifiable->metadata) {
            if ($notifiable->metadata->gender == 'male') {
                $message->line(__('Mail/Intro.male', ['name' => $notifiable->name]));
            } elseif ($notifiable->metadata->gender == 'female') {
                $message->line(__('Mail/Intro.female', ['name' => $notifiable->name]));
            } else {
                $message->line(__('Mail/Intro.unknown', ['name' => $notifiable->name]));
            }
        } else {
            $message->line(__('Mail/Intro.unknown', ['name' => $notifiable->name])); 
        }

        return $message;
    }


    
}
