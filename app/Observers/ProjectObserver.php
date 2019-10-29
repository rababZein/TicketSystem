<?php

namespace App\Observers;

use App\Models\Project;
use App\Models\User;
use App\Jobs\Project\ProjectAssignJob;
use App\Http\Requests\ProjectRequest\AddProjectRequest;
use App\Http\Requests\ProjectRequest\UpdateProjectRequest;

class ProjectObserver
{
    /**
     * Handle the project "created" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function created(Project $project)
    {
        $request = new AddProjectRequest();
        $input = $request->validated();

        $employees = User::find($input['project_assign']);
        $project->assigns()->attach($employees);
        $project->assigns;

        ProjectAssignJob::dispatch($employees, $project);
    }

    /**
     * Handle the project "updated" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function updated(Project $project)
    {
        $request = new UpdateProjectRequest();
        $input = $request->validated();

        if (isset($input['project_assign'])) {
            $employees = User::find($input['project_assign']);
            $project->assigns()->sync($employees);
            $project->assigns;
      
            ProjectAssignJob::dispatch($employees, $project);
        }
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
