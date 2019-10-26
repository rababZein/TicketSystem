<?php

namespace App\Http\Requests\ProjectRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project;
use App\Exceptions\ItemNotFoundException;

class UpdateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // who can update ??

        // 1- admin
        if (auth()->user()->isAdmin()) {
            return true;
        }

        $project_id =$this->route('project');
        $project = Project::find($project_id);

        if (!$project) {
            throw new ItemNotFoundException($project_id);
        }

        // 2- creator
        if ($project->created_by == auth()->user()->id) {
            return true;
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
            'name' => 'string',
            'description' => 'string',
            'owner_id' => 'integer|exists:users,id',
            'task_rate' => 'integer',
            'budget_hours' => 'integer',
            'project_assign' => 'array',
            'project_assign.*' => 'integer|exists:users,id',
        ];
    }
}
