<?php

namespace App\Http\Requests\TaskRequest;

use Illuminate\Foundation\Http\FormRequest;

class AddTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
            'status_id' => 'nullable|integer|exists:status,id',
            'count_hours' => 'nullable|numeric|min:0'
        ];
    }
}
