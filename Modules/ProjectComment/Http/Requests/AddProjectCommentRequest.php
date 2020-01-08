<?php

namespace Modules\ProjectComment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddProjectCommentRequest extends FormRequest
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
            'project_id' => 'required|integer|exists:projects,id',
            'comment' => 'required|min:3|max:4294967295',
        ];
    }
}
