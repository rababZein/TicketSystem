<?php

namespace App\Http\Requests\TicketRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Ticket;
use App\Exceptions\ItemNotFoundException;

class ViewTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // who can view ticket ??

        // 1- admin
        if (auth()->user()->isAdmin()) {
            return true;
        }

        $ticket_id =$this->route('ticket_id');
        $ticket = Ticket::find($ticket_id);

        if (!$ticket) {
            throw new ItemNotFoundException($ticket_id);
        }

        // 2- creator, project owner
        if ($ticket->created_by == auth()->user()->id || $ticket->project->owner->id == auth()->user()->id) {
            return true;
        }
        
        // 3- assigns 
        foreach ($ticket->project->assigns as $assign) {
            if ($assign->id == auth()->user()->id) {
                return true;
            }
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
