<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Models\Setting;
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
     * Handle the ticket "creating" event.
     *
     * @param  \App\Ticket  $ticket
     * @return void
     */
    public function creating(Ticket $ticket)
    {
        $ticketSetting = Setting::where('entity', 'ticket')
                ->where('current', true)
                ->orderBy('created_at', 'desc')->first();

        if ($ticketSetting) {
            $ticket->setting_id = $ticketSetting->id;
            $ticket->number = $ticketSetting->key . sprintf("%08d", $ticketSetting->last_number + 1);
        
            $ticketSetting->last_number = sprintf("%08d", $ticketSetting->last_number + 1);
            $ticketSetting->updated_by = auth()->user()->id ? auth()->user()->id  : 1; // admin
            $ticketSetting->save();
        }
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

        $ticketSetting = Setting::find($ticket->setting_id);

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
      if($ticket->isDirty('status_id') && ($ticket->status_id == 4 || $ticket->status_id == 3)){ 
        // status is changed && new status is done or in-progress
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
        $this->activityLog->addToLog('Delete ticket: '.$ticket->name, $ticket->project->owner->id, $ticket->project->id, $ticket->id);
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
