<?php

namespace Modules\ProjectComment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // who can update ??
        // 1- creator
        if ($this->ProjectComment->created_by == auth()->user()->id) {
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
            'project_id' => 'required|integer|exists:project_comments,id',
            'comment' => 'required|string',
        ];
    }
}
