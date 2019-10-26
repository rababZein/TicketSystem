<?php

namespace App\Http\Requests\TaskRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Task;
use App\Exceptions\ItemNotFoundException;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // who can update task

        // 1- admin
        if (auth()->user()->isAdmin()) {
            return true;
        }

        $task_id =$this->route('task');
        $task = Task::find($task_id);

        if (!$task) {
            throw new ItemNotFoundException($task_id);
        }
        
        // 2- created by
        if ($task->created_by == auth()->user()->id) {
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
            'project_id' => 'integer|exists:projects,id',
            'ticket_id' => 'nullable|integer|exists:tickets,id',
            'responsible_id' => 'integer|exists:users,id',
            'status_id' => 'nullable|integer|exists:status,id',
            'count_hours' => 'nullable|numeric|min:0'
        ];
    }
}
