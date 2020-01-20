<?php

namespace App\Observers;

use App\Models\Project;
use App\Models\User;
use App\Jobs\Project\ProjectAssignJob;
use \Illuminate\Http\Request;
use Modules\Activity\Http\Controllers\ActivityController;

class ProjectObserver
{
    private $input;
    private $activityLog;

    public function __construct(Request $request, ActivityController $activityLog)
    {
        $this->input = $request->all();
        $this->activityLog = $activityLog;
    }

    public function retrieved(Project $project)
    {
        $project->owner;
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

        $this->activityLog->addToLog('Create project: '.$project->name, $project->owner->id, $project->id);
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

        $this->activityLog->addToLog('Update project: '.$project->name, $project->owner->id, $project->id);
    }

    /**
     * Handle the project "deleted" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function deleted(Project $project)
    {
        $this->activityLog->addToLog('Delete project: '.$project->name, $project->owner->id, $project->id);
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
