<?php

namespace App\Http\Requests\TrackingRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Task;
use App\Exceptions\ItemNotFoundException;

class EditTrackingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // who can edit ?
        $task_id =$this->route('task_id');
        $task = Task::find($task_id);

        if (!$task) {
            throw new ItemNotFoundException($task_id);
        }
        
        // admin && responsible
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
            'comment' => 'string',
            'start_at' => 'date_format:Y-m-d H:i:s',
            'end_at' => 'date_format:Y-m-d H:i:s',
            'task_id' => 'integer|exists:tasks,id|in:'.$task_id,
            'count_time' => 'numeric|min:0'
        ];
    }
}
