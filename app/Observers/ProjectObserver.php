<?php

namespace App\Observers;

use App\Models\Project;
use App\Models\User;
use App\Jobs\Project\ProjectAssignJob;
use \Illuminate\Http\Request;

class ProjectObserver
{
    private $input;

    public function __construct(Request $request)
    {
        $this->input = $request->all();
    }
    /**
     * Handle the project "created" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function created(Project $project)
    {
        if (isset($this->input['project_assign']) && $this->input['project_assign']) {
            $employees = User::find($this->input['project_assign']);
            $project->assigns()->attach($employees);
            $project->assigns;

            ProjectAssignJob::dispatch($employees, $project);
        }

        $project->owner;
    }

    /**
     * Handle the project "updated" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function updated(Project $project)
    {
        if (isset($this->input['project_assign'])) {
            $employees = User::find($this->input['project_assign']);
            $project->assigns()->sync($employees);
            $project->assigns;
      
            ProjectAssignJob::dispatch($employees, $project);
        }

        $project->owner;
    }

    /**
     * Handle the project "deleted" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function deleted(Project $project)
    {
        //
    }

    /**
     * Handle the project "restored" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function restored(Project $project)
    {
        //
    }

    /**
     * Handle the project "force deleted" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function forceDeleted(Project $project)
    {
        //
    }
}
