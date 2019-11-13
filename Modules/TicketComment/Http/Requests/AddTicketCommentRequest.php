<?php

namespace Modules\TicketComment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddTicketCommentRequest extends FormRequest
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
            'ticket_id' => 'required|integer|exists:ticket_comments,id',
            'comment' => 'required|string',
        ];
    }
}
