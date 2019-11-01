<?php

namespace App\Observers;

use App\Models\Task;
use \Illuminate\Http\Request;
use App\Jobs\Task\TaskAssignJob;

class TaskObserver
{
    private $input;

    public function __construct(Request $request)
    {
        $this->input = $request->all();
    }
    
    /**
     * Handle the task "created" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function created(Task $task)
    {
        TaskAssignJob::dispatch($this->input['responsible_id'], $task);
        $task->project;
        $task->responsible;
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
    }

    /**
     * Handle the task "deleted" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function deleted(Task $task)
    {
        //
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
