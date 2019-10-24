<?php

namespace App\Http\Requests\TicketRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Ticket;
use App\Exceptions\ItemNotFoundException;

class DeleteTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // who can delete ticket ??

        // 1- admin
        if (auth()->user()->isAdmin()) {
            return true;
        }

        $ticket_id =$this->route('ticket_id');
        $ticket = Ticket::find($ticket_id);

        if (!$ticket) {
            throw new ItemNotFoundException($ticket_id);
        }

        // 2- creator
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
