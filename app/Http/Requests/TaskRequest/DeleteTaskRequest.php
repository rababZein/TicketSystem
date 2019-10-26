<?php

namespace App\Http\Requests\TaskRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Task;
use App\Exceptions\ItemNotFoundException;

class DeleteTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // who can delete task ??

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
            //
        ];
    }
}
