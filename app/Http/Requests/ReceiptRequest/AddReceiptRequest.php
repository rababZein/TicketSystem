<?php

namespace App\Http\Requests\ReceiptRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project;

class AddReceiptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // who can create a receipt

        // 1- admin
        if (auth()->user()->isAdmin()) {
            return true;
        }

        $project_id =$this->route('project_id');
        $project = Project::find($project_id);

        if (!$project) {
            throw new ItemNotFoundException($project_id);
        }

        // 2- assigns
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
            'task_id' => 'required|integer|exists:tasks,id',
            'total' => 'numeric|min:0',
            'is_paid' => 'boolean',
        ];
    }
}
