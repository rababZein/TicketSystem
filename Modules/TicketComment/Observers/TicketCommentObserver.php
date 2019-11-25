<?php

namespace Modules\TicketComment\Observers;

use Modules\TicketComment\Entities\TicketComment;
use Modules\Activity\Http\Controllers\ActivityController;

use App\Jobs\Ticket\ReplyTicketJob;
use App\Jobs\Ticket\UpdateReplyTicketJob;

class TicketCommentObserver
{
    private $activityLog;

    public function __construct(ActivityController $activityLog)
    {
        $this->activityLog = $activityLog;
    }
    /**
     * Handle the TicketComment "created" event.
     *
     * @param  \App\TicketComment  $ticketComment
     * @return void
     */
    public function created(TicketComment $ticketComment)
    {
        $ticketComment->creator;
        $ticketComment->updater;

        $this->activityLog->addToLog('Create Comment on Ticket: '.$ticketComment->ticket->name, $ticketComment->ticket->project->owner->id, $ticketComment->ticket->project->id, $ticketComment->ticket->id);
    
        ReplyTicketJob::dispatch($ticketComment);
    }

    /**
     * Handle the TicketComment "updated" event.
     *
     * @param  \App\TicketComment  $ticketComment
     * @return void
     */
    public function updated(TicketComment $ticketComment)
    {
        $ticketComment->creator;
        $ticketComment->updater;

        $this->activityLog->addToLog('Update Comment on Ticket: '.$ticketComment->ticket->name, $ticketComment->ticket->project->owner->id, $ticketComment->ticket->project->id, $ticketComment->ticket->id);
   
        UpdateReplyTicketJob::dispatch($ticketComment);
    }

    /**
     * Handle the TicketComment "deleted" event.
     *
     * @param  \App\TicketComment  $ticketComment
     * @return void
     */
    public function deleted(TicketComment $ticketComment)
    {
        $ticketComment->creator;
        $ticketComment->updater;
    
        $this->activityLog->addToLog('Delete Comment on Ticket: '.$ticketComment->ticket->name, $ticketComment->ticket->project->owner->id, $ticketComment->ticket->project->id, $ticketComment->ticket->id);
    }

    /**
     * Handle the TicketComment "restored" event.
     *
     * @param  \App\TicketComment  $ticketComment
     * @return void
     */
    public function restored(TicketComment $ticketComment)
    {
        //
    }

    /**
     * Handle the TicketComment "force deleted" event.
     *
     * @param  \App\TicketComment  $ticketComment
     * @return void
     */
    public function forceDeleted(TicketComment $ticketComment)
    {
        //
    }
}
