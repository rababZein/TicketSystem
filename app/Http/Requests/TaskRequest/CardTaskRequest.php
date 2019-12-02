<?php

namespace App\Http\Requests\TaskRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project;
use App\Exceptions\ItemNotFoundException;

class CardTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // who can add new task?

        // 1- admin && who has permission
        if (auth()->user()->isAdmin() || auth()->user()->can('task-list')) {
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
            'status_id' => 'required|integer|exists:status,id',
            'employee_id' => 'nullable||integer|exists:users,id',
            'project_id' => 'nullable|integer|exists:projects,id'
        ];
    }
}
