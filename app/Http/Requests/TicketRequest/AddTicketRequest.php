<?php

namespace App\Http\Requests\TicketRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project;
use App\Exceptions\ItemNotFoundException;

class AddTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // who can add ticket ??

        // 1- admin
        if (auth()->user()->isAdmin()) {
            return true;
        }

        $project_id =$this->route('project_id');
        $project = Project::find($project_id);

        if (!$project) {
            throw new ItemNotFoundException($project_id);
        }

        // 2- project owner
        if ($project->owner->id == auth()->user()->id) {
            return true;
        }

        // 3- people who are assign to project
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
            'status_id' => 'nullable|integer|exists:status,id',
        ];
    }
}
