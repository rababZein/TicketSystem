<?php

namespace Modules\ClientComment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientCommentRequest extends FormRequest
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
        if ($this->ClientComment->created_by == auth()->user()->id) {
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
            'client_id' => 'required|integer|exists:client_comments,id',
            'comment' => 'required|string',
        ];
    }
}
