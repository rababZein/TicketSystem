<?php

namespace App\Jobs\Ticket;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\Ticket;

use App\Notifications\Ticket\TicketChangeStatusNotification;

class TicketChangeStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $ticket;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $temp = \App::getLocale();

        \App::setLocale(isset($this->ticket->project->owner->metadata->language) ? $this->ticket->project->owner->metadata->language : 'de');
        
        try {
            $this->ticket->project->owner->notify(new TicketChangeStatusNotification($this->ticket));
        } catch (\Exception $ex) {
            throw new \Exception($ex);
        }

        \App::setLocale($temp);
    }
}
