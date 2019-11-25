<?php

namespace App\Jobs\Ticket;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Modules\TicketComment\Entities\TicketComment;

class UpdateReplyTicketJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $ticketComment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(TicketComment $ticketComment)
    {
        $this->ticketComment = $ticketComment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->ticketComment->ticket->project->owner->notify(new TicketChangeStatusNotification($this->ticket));
        } catch (\Exception $ex) {
            throw new \Exception($ex);
        }
    }
}
