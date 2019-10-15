<?php

namespace App\Http\Requests\TaskRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Task;

class ViewTaskRequest extends FormRequest
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

        $task_id =$this->route('task_id');
        $task = Task::find($task_id);

        if (!$task) {
            throw new ItemNotFoundException($task_id);
        }
        
        if ($task->responsible->id == auth()->user()->id) {
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
