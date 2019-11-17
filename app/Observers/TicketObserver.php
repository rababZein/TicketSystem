<?php

namespace App\Observers;

use App\Models\Ticket;
use Modules\Activity\Http\Controllers\ActivityController;
use App\Jobs\Ticket\TicketChangeStatusJob;

class TicketObserver
{
    private $activityLog;

    public function __construct(ActivityController $activityLog)
    {
        $this->activityLog = $activityLog;
    }
    /**
     * Handle the ticket "created" event.
     *
     * @param  \App\Ticket  $ticket
     * @return void
     */
    public function created(Ticket $ticket)
    {
        $ticket->project->owner;

        $this->activityLog->addToLog('Create ticket: '.$ticket->name, $ticket->project->owner->id, $ticket->project->id, $ticket->id);
    }

    /**
     * Handle the ticket "updating" event.
     *
     * @param  \App\Ticket  $ticket
     * @return void
     */
    public function updating(Ticket $ticket)
    {
      if($ticket->isDirty('status_id') && $ticket->status_id == 4){ 
        // status is changed && new status is done
        TicketChangeStatusJob::dispatch($ticket);
      }
    }

    /**
     * Handle the ticket "updated" event.
     *
     * @param  \App\Ticket  $ticket
     * @return void
     */
    public function updated(Ticket $ticket)
    {
        $ticket->project->owner;

        $this->activityLog->addToLog('Create ticket: '.$ticket->name, $ticket->project->owner->id, $ticket->project->id, $ticket->id);
    }

    /**
     * Handle the ticket "deleted" event.
     *
     * @param  \App\Ticket  $ticket
     * @return void
     */
    public function deleted(Ticket $ticket)
    {
        $this->activityLog->addToLog('Create ticket: '.$ticket->name, $ticket->project->owner->id, $ticket->project->id, $ticket->id);
    }

    /**
     * Handle the ticket "restored" event.
     *
     * @param  \App\Ticket  $ticket
     * @return void
     */
    public function restored(Ticket $ticket)
    {
        //
    }

    /**
     * Handle the ticket "force deleted" event.
     *
     * @param  \App\Ticket  $ticket
     * @return void
     */
    public function forceDeleted(Ticket $ticket)
    {
        //
    }
}
