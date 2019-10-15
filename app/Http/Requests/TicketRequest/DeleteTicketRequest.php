<?php

namespace App\Http\Requests\TicketRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Ticket;

class DeleteTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user()->isAdmin()) {
            return true;
        }

        $ticket_id =$this->route('ticket_id');
        $ticket = Ticket::find($ticket_id);

        if (!$ticket) {
            throw new ItemNotFoundException($ticket_id);
        }

        if ($ticket->created_by == auth()->user()->id) {
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
            //
        ];
    }
}
