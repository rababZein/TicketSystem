<?php

namespace Modules\ClientComment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddClientCommentRequest extends FormRequest
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
            'client_id' => 'required|integer|exists:users,id',
            'comment' => 'required|min:3|max:4294967295',
        ];
    }
}
