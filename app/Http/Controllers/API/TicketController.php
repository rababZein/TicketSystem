<?php 

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\API\BaseController;

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
    $tickets = Ticket::with('project')->get();

    return $this->sendResponse($tickets->toArray(), 'Tickets retrieved successfully.');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string',
      'description' => 'required|string',
      'project_id' => 'required|integer|exists:projects,id',
    ]);

    if($validator->fails()){
       return $this->sendError('Validation Error.', $validator->errors());      
    }

    $input = $request->all();
    $input['created_at'] = Carbon::now();
    $input['created_by'] = auth()->user()->id;

    $ticket = Ticket::create($input);

    return $this->sendResponse($ticket->toArray(), 'Ticket created successfully.');
    
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    $ticket = Ticket::find($id);

    if (is_null($ticket)) {
        return $this->sendError('Ticket not found.');
    }

    return $this->sendResponse($ticket->toArray(), 'Ticket retrieved successfully.');    
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'string',
      'description' => 'string',
      'project_id' => 'integer|exists:projects,id',
    ]);

    if($validator->fails()){
        return $this->sendError('Validation Error.', $validator->errors());       
    }

    $ticket = Ticket::find($id);
    
    if (!$ticket) {
        return $this->sendError('Not found Error.', 'Sorry, ticket with id ' . $id . ' cannot be found', 400);
    }

    $ticket->updated_at = Carbon::now();
    $ticket->updated_by = auth()->user()->id;

    $updated = $ticket->fill($request->all())->save();

    if (!$updated)
      return $this->sendError('Not update!.', 'Sorry, Ticket could not be updated', 500);

    return $this->sendResponse($ticket->toArray(), 'Ticket updated successfully.');    
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
      return $this->sendError('ticket not found.');
    }

    if($ticket->tasks->isNotEmpty()) {
      return $this->sendError('Can\'t delete!, Ticket has tasks.');
    }

    $ticket->delete();

    return $this->sendResponse($ticket->toArray(), 'Ticket deleted successfully.');
  }
  
}

?>