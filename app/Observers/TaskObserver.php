<?php

namespace App\Observers;

use App\Models\Task;
use \Illuminate\Http\Request;
use App\Jobs\Task\TaskAssignJob;
use Modules\Activity\Http\Controllers\ActivityController;
use App\Exceptions\ItemNotUpdatedException;

class TaskObserver
{
    private $input;
    private $activityLog;

    public function __construct(Request $request, ActivityController $activityLog)
    {
        $this->input = $request->all();
        $this->activityLog = $activityLog;
    }
    
    /**
     * Handle the task "created" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function created(Task $task)
    {
        if (isset($this->input['responsible_id'])) {
            TaskAssignJob::dispatch($this->input['responsible_id'], $task);
        }

        $ticket_id = null;
        if ($task->ticket) {
            $ticket_id = $task->ticket->id;
        }

        $this->activityLog->addToLog('Create task: '.$task->name, $task->project->owner->id, $task->project->id, $ticket_id, $task->id);
    }

    /**
     * Handle the task "updating" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function updating(Task $task)
    {
        if($task->isDirty('status_id') && ($task->status_id == 2 || $task->status_id == 3)){ 
            // status is changed && new status is pending or in-progress
            if ($task->ticket) {
                $task->ticket->status_id = $task->status_id;
                $task->ticket->save();
            }
        }
        if($task->responsible_id == auth()->user()->id) {
            if(!$task->isClean(['name', 'description', 'responsible_id', 'created_by', 'updated_by', 'ticket_id', 'project_id', 'count_hours', 'deleted_at', 'priority', 'deadline', 'start_at'])){
                throw new ItemNotUpdatedException('task');
            }    
        }
    }

    /**
     * Handle the task "updated" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function updated(Task $task)
    {
        if (isset($this->input['responsible_id'])) {
            TaskAssignJob::dispatch($this->input['responsible_id'], $task);
        }

        $task->project;
        $task->responsible;
        $task->task_status;
        $task->deadline;

        $ticket_id = null;
        if ($task->ticket) {
            $ticket_id = $task->ticket->id;
        }

        $this->activityLog->addToLog('Update task: '.$task->name, $task->project->owner->id, $task->project->id, $ticket_id, $task->id);
    }

    /**
     * Handle the task "deleted" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function deleted(Task $task)
    {
        $ticket_id = null;
        if ($task->ticket) {
            $ticket_id = $task->ticket->id;
        }

        $this->activityLog->addToLog('Delete task: '.$task->name, $task->project->owner->id, $task->project->id, $ticket_id, $task->id);
    }

    /**
     * Handle the task "restored" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function restored(Task $task)
    {
        //
    }

    /**
     * Handle the task "force deleted" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function forceDeleted(Task $task)
    {
        //
    }
}
