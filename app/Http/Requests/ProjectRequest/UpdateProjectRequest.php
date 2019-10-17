<?php

namespace App\Http\Requests\ProjectRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
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
            'name' => 'string',
            'description' => 'string',
            'owner_id' => 'integer|exists:users,id',
            'task_rate' => 'integer',
            'budget_hours' => 'integer',
            'project_assign' => 'array',
            'project_assign.*' => 'integer|exists:users,id',
        ];
    }
}
