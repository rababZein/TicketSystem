<?php

namespace App\Http\Requests\ProjectRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project;

class ViewProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // who view ?

        // 1- admin
        if (auth()->user()->isAdmin()) {
            return true;
        }

        $project_id =$this->route('project_id');
        $project = Project::find($project_id);

        if (!$project) {
            throw new ItemNotFoundException($project_id);
        }


        // 2- creator && owner
        if ($project->created_by == auth()->user()->id
            || $project->owner->id == auth()->user()->id) {
            return true;
        }

        // 3- assigns
        foreach ($project->assigns as $assign) {
            if ($assign->id == auth()->user()->id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
