<?php

namespace App\Http\Requests\ProjectRequest;

use Illuminate\Foundation\Http\FormRequest;

class AddProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // all who has permission
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
            'name' => 'required|string|unique:projects',
            'description' => 'required|string',
            'owner_id' => 'required|integer|exists:users,id',
            'task_rate' => 'required|integer',
            'budget_hours' => 'required|integer',
            'project_assign' => 'array',
            'project_assign.*' => 'integer|exists:users,id',
        ];
    }
}
