<?php

namespace Modules\TaskComment\Observers;

use Modules\TaskComment\Entities\TaskComment;
use Modules\Activity\Http\Controllers\ActivityController;

class TaskCommentObserver
{
    private $activityLog;

    public function __construct(ActivityController $activityLog)
    {
        $this->activityLog = $activityLog;
    }
    /**
     * Handle the TaskComment "created" event.
     *
     * @param  \App\TaskComment  $taskComment
     * @return void
     */
    public function created(TaskComment $taskComment)
    {
        $taskComment->creator;
        $taskComment->updater;

        $ticket_id = null;
        if ($taskComment->task->ticket) {
            $ticket_id = $taskComment->task->ticket->id;
        }

        $this->activityLog->addToLog('Create Comment on Task: '.$taskComment->task->name, $taskComment->task->project->owner->id, $taskComment->task->project->id, $ticket_id, $taskComment->task->id);
    }

    /**
     * Handle the TaskComment "updated" event.
     *
     * @param  \App\TaskComment  $taskComment
     * @return void
     */
    public function updated(TaskComment $taskComment)
    {
        $taskComment->creator;
        $taskComment->updater;

        $ticket_id = null;
        if ($taskComment->task->ticket) {
            $ticket_id = $taskComment->task->ticket->id;
        }

        $this->activityLog->addToLog('Update Comment on Task: '.$taskComment->task->name, $taskComment->task->project->owner->id, $taskComment->task->project->id, $ticket_id, $taskComment->task->id);
    }

    /**
     * Handle the TaskComment "deleted" event.
     *
     * @param  \App\TaskComment  $taskComment
     * @return void
     */
    public function deleted(TaskComment $taskComment)
    {
        $taskComment->creator;
        $taskComment->updater;
    
        $ticket_id = null;
        if ($taskComment->task->ticket) {
            $ticket_id = $taskComment->task->ticket->id;
        }

        $this->activityLog->addToLog('Delete Comment on Task: '.$taskComment->task->name, $taskComment->task->project->owner->id, $taskComment->task->project->id, $ticket_id, $taskComment->task->id);
    }

    /**
     * Handle the TaskComment "restored" event.
     *
     * @param  \App\TaskComment  $taskComment
     * @return void
     */
    public function restored(TaskComment $taskComment)
    {
        //
    }

    /**
     * Handle the TaskComment "force deleted" event.
     *
     * @param  \App\TaskComment  $taskComment
     * @return void
     */
    public function forceDeleted(TaskComment $taskComment)
    {
        //
    }
}
