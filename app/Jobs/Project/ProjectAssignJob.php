<?php

namespace App\Jobs\Project;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Project\ProjectAssignNotification;
use App\Models\Project;

class ProjectAssignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $employees, $project;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($employees, Project $project)
    {
        $this->employees = $employees;
        $this->project = $project;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            \Notification::send($this->employees, new ProjectAssignNotification($this->project));
        } catch (\Exception $ex) {
            throw new \Exception($ex);
        }
    }
}
