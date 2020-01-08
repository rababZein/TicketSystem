<?php

namespace Modules\TaskComment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskCommentRequest extends FormRequest
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
        if ($this->taskComment->created_by == auth()->user()->id) {
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
            'comment' => 'min:3|max:4294967295',
        ];
    }
}
