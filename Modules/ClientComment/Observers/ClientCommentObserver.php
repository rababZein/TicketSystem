<?php

namespace Modules\ClientComment\Observers;

use Modules\ClientComment\Entities\ClientComment;
use Modules\Activity\Http\Controllers\ActivityController;

class ClientCommentObserver
{
    private $activityLog;

    public function __construct(ActivityController $activityLog)
    {
        $this->activityLog = $activityLog;
    }
    /**
     * Handle the ClientComment "created" event.
     *
     * @param  \App\ClientComment  $clientComment
     * @return void
     */
    public function created(ClientComment $clientComment)
    {
        $clientComment->creator;
        $clientComment->updater;

        $this->activityLog->addToLog('Create Comment on Client: '.$clientComment->client->name, $clientComment->client->id);
    }

    /**
     * Handle the ClientComment "updated" event.
     *
     * @param  \App\ClientComment  $clientComment
     * @return void
     */
    public function updated(ClientComment $clientComment)
    {
        $clientComment->creator;
        $clientComment->updater;

        $this->activityLog->addToLog('Update Comment on Client: '.$clientComment->client->name, $clientComment->client->id);
    }

    /**
     * Handle the ClientComment "deleted" event.
     *
     * @param  \App\ClientComment  $clientComment
     * @return void
     */
    public function deleted(ClientComment $clientComment)
    {
        $clientComment->creator;
        $clientComment->updater;
    
        $this->activityLog->addToLog('Delete Comment on Client: '.$clientComment->client->name, $clientComment->client->id);
    }

    /**
     * Handle the ClientComment "restored" event.
     *
     * @param  \App\ClientComment  $clientComment
     * @return void
     */
    public function restored(ClientComment $clientComment)
    {
        //
    }

    /**
     * Handle the ClientComment "force deleted" event.
     *
     * @param  \App\ClientComment  $clientComment
     * @return void
     */
    public function forceDeleted(ClientComment $clientComment)
    {
        //
    }
}
