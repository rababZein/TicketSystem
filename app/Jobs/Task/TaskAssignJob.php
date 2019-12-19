<?php

namespace App\Jobs\Task;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Task\TaskAssignNotification;
use App\Models\User;
use App\Models\Task;
use App\Exceptions\ItemNotFoundException;

class TaskAssignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $responsibleId, $task;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($responsibleId, Task $task)
    {
        $this->responsibleId = $responsibleId;
        $this->task = $task;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $responsible = User::find($this->responsibleId);
        if (!$responsible) {
            throw new ItemNotFoundException($responsible);
        }

        $temp = \App::getLocale();

        \App::setLocale(isset($responsible->metadata->language) ? $responsible->metadata->language : 'de');
        
        try {
            $responsible->notify(new TaskAssignNotification($this->task));
        } catch (\Exception $ex) {
            throw new \Exception($ex);
        }

        \App::setLocale($temp);
    }
}
