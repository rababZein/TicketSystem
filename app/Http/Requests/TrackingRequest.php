<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Exceptions\ItemNotFoundException;

class TrackingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $task_id =$this->route('task_id');
        $task = Task::find($task_id);

        if (!$task) {
            throw new ItemNotFoundException($task_id);
        }
        
        if ($task->responsible->id == auth()->user()->id || auth()->user()->isAdmin()) {
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
        $task_id =$this->route('task_id');

        return [
            'comment' => 'required|string',
            'start_at' => 'required|date_format:Y-m-d H:i:s',
            'end_at' => 'date_format:Y-m-d H:i:s',
            'task_id' => 'required|integer|exists:tasks,id|in:'.$task_id
        ];
    }

    /**
     * Custome valiation error messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            //'task_id.different' => 'The task id is not correct'
        ];
    }
}
