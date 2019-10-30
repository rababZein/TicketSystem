<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\TicketRequest\AddTicketRequest;
use App\Http\Requests\TicketRequest\UpdateTicketRequest;
use App\Http\Requests\TicketRequest\DeleteTicketRequest;
use App\Http\Requests\TicketRequest\ViewTicketRequest;
use App\Http\Requests\TicketRequest\ListTicketRequest;
use App\Models\Ticket;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\API\BaseController;
use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;
use App\Exceptions\InvalidDataException;
use App\Exceptions\ItemNotFoundException;
use App\Exceptions\ItemNotDeletedException;
use App\Http\Resources\Ticket\TicketResource;
use App\Http\Resources\Ticket\TicketCollection;

class TicketController extends BaseController
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('permission:ticket-list|ticket-create|ticket-edit|ticket-delete', ['only' => ['index', 'show', 'getTicketsByProjectId']]);
    $this->middleware('permission:ticket-create', ['only' => ['store']]);
    $this->middleware('permission:ticket-edit', ['only' => ['update']]);
    $this->middleware('permission:ticket-delete', ['only' => ['destroy']]);
  }

  /**
   * Display a data listing of the resource.
   *
   * @return Response
   */
  public function index(ListTicketRequest $request)
  {
    if (auth()->user()->isAdmin()) {
      $tickets = Ticket::with('project.owner')->latest()->paginate();
    } else {
      $ticketModel = new Ticket();
      $tickets = $ticketModel->ownTickets(auth()->user()->id);
    }

    return $this->sendResponse(new TicketCollection($tickets), 'Tickets retrieved successfully.');
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
    } catch (\Exception $ex) {
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
  public function show(ViewTicketRequest $request, $id)
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
  public function destroy(DeleteTicketRequest $request, $id)
  {
    $ticket = Ticket::find($id);

    if (is_null($ticket)) {
      throw new ItemNotFoundException($id);
    }

    if ($ticket->tasks->isNotEmpty()) {
      throw new InvalidDataException(
        [
          'tasks' => $ticket->tasks->toArray()
        ],
        'Can\'t delete!, Ticket has tasks.'
      );
    }

    try {
      $ticket->delete();
    } catch (\Throwable $th) {
      throw new ItemNotDeletedException('Tracking_task');
    }

    return $this->sendResponse(new TicketResource($ticket), 'Ticket deleted successfully.');
  }

  public function getTicketsByProjectId($id, ListTicketRequest $request)
  {
    $ticket = Ticket::whereHas('project', function ($query) use ($id) {
      $query->where('id', $id);
    })->latest()->paginate();

    if (is_null($ticket)) {
      throw new ItemNotFoundException($id);
    }

    return $this->sendResponse(new TicketCollection($ticket), 'Tickets retrieved successfully.');
  }
}
