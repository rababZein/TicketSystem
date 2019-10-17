<?php 

namespace App\Http\Controllers\API;

use App\Http\Requests\TicketRequest\AddTicketRequest;
use App\Http\Requests\TicketRequest\UpdateTicketRequest;
use App\Models\Ticket;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\API\BaseController;
use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;
use App\Exceptions\InvalidDataException;
use App\Exceptions\ItemNotFoundException;
use App\Exceptions\ItemNotDeletedException;
use App\Http\Resources\TicketResource;

class TicketController extends BaseController 
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('permission:ticket-list|ticket-create|ticket-edit|ticket-delete', ['only' => ['index']]);
      $this->middleware('permission:ticket-create', ['only' => ['store']]);
      $this->middleware('permission:ticket-edit', ['only' => ['update']]);
      $this->middleware('permission:ticket-delete', ['only' => ['destroy']]);
  }

  /**
   * Display a listing of the resource view.
   *
   * @return Response
   */
  public function index()
  {
    return view('pages.tickets.index');
  }

  /**
   * Display a data listing of the resource.
   *
   * @return Response
   */
  public function getAll()
  {
    $tickets = Ticket::with('project.owner')->get();
 
    return $this->sendResponse(TicketResource::collection($tickets), 'Tickets retrieved successfully.');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(AddTicketRequest $request)
  {
    $input = $request->validated();
    $input['created_at'] = Carbon::now();
    $input['created_by'] = auth()->user()->id;

    try {
      $ticket = Ticket::create($input);
    } catch (\Throwable $th) {
      throw new ItemNotCreatedException('Ticket');
    }

    return $this->sendResponse(new TicketResource($ticket), 'Ticket created successfully.');
    
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    $ticket = Ticket::with('tasks', 'project')->get();
    $ticket = $ticket->find($id);

    if (is_null($ticket)) {
      throw new ItemNotFoundException($id);
    }

    return $this->sendResponse(new TicketResource($ticket), 'Ticket retrieved successfully.');    
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(UpdateTicketRequest $request, $id)
  {
    $ticket = Ticket::find($id);
    
    if (!$ticket) {
      throw new ItemNotFoundException($id);
    }

    $ticket->updated_at = Carbon::now();
    $ticket->updated_by = auth()->user()->id;

    try {
      $updated = $ticket->fill($request->validated())->save();
    } catch (\Throwable $th) {
      throw new ItemNotUpdatedException('Ticket');
    }
    
    if (!$updated)
      throw new ItemNotUpdatedException('Ticket');

    return $this->sendResponse(new TicketResource($ticket), 'Ticket updated successfully.');    
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    $ticket = Ticket::find($id);

    if (is_null($ticket)) {
      throw new ItemNotFoundException($id);
    }

    if($ticket->tasks->isNotEmpty()) {
      throw new InvalidDataException([
        'tasks' => $ticket->tasks->toArray()
      ],
      'Can\'t delete!, Ticket has tasks.');
    }

    try {
      $ticket->delete();
    } catch (\Throwable $th) {
      throw new ItemNotDeletedException('Tracking_task');
    }

    return $this->sendResponse(new TicketResource($ticket), 'Ticket deleted successfully.');
  }
  
}

?>