<?php

namespace App\Http\Requests\TaskRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project;

class AddTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user()->isAdmin()) {
            return true;
        }

        $project_id =$this->route('project_id');
        $project = Project::find($project_id);

        if (!$project) {
            throw new ItemNotFoundException($project_id);
        }

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
            'name' => 'required|string',
            'description' => 'required|string',
            'project_id' => 'required|integer|exists:projects,id',
            'ticket_id' => 'nullable|integer|exists:tickets,id',
            'responsible_id' => 'required|integer|exists:users,id',
            'count_hours' => 'nullable|numeric|min:0'
        ];
    }
}
