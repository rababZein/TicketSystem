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
    $this->middleware('permission:ticket-list', ['only' => ['getTicketsCountPerClient', 'getTicketsPerClient']]);
  }

  /**
   * Display a data listing of the resource.
   *
   * @return Response
   */
  public function index(ListTicketRequest $request)
  {
    $input = $request->validated()['params']; 
    
    if (auth()->user()->isAdmin()) {
      $tickets = Ticket::with('project.owner');
    } else {
      $ticketModel = new Ticket();
      $tickets = $ticketModel->ownTickets(auth()->user()->id);
    }

    // global search
    if (isset($input['global_search']) && $input['global_search']) {
      // to be all between ()
      $tickets->where(function($query) use ($input){
        // in direct relation
        $query->whereHas('ticket_status', function($query) use($input) {
          $query->where('name', 'like', '%'.$input['global_search'].'%');
        });
        $query->orWhereHas('project', function($query) use($input) {
          $query->where('name', 'like', '%'.$input['global_search'].'%');
        });
        $query->orWhereHas('project.owner', function($query) use($input) {
          $query->where('name', 'like', '%'.$input['global_search'].'%');
        });
        // direct relation
        $query->orWhere('tickets.name','LIKE','%'.$input['global_search'].'%');
        $query->orWhere('tickets.number','LIKE','%'.$input['global_search'].'%');
        $query->orWhere('tickets.created_at','LIKE','%'.$input['global_search'].'%');
      });
    }


    // sorting
    if (isset($input['sort']) && $input['sort']) {
      foreach ($input['sort'] as $sortObj) {
        //direct relation then in-direct relation
        if (in_array($sortObj['name'], ['created_at', 'name', 'number', 'read'])) {
          if ($sortObj['order'] == 'desc') {
            $tickets->latest($sortObj['name']);
          } else {
            $tickets->oldest($sortObj['name']);
          }
          // inndirect relation
        } elseif ($sortObj['name'] == 'status.name') {
          $tickets->join('status', 'status.id', '=', 'tickets.status_id');
          $tickets->orderBy('status.name', $sortObj['order']);
        } elseif ($sortObj['name'] == 'project.name') {
          $tickets->join('projects', 'projects.id', '=', 'tickets.project_id');
          $tickets->orderBy('projects.name', $sortObj['order']);
        } elseif ($sortObj['name'] == 'project.owner.name') {
          $tickets->join('projects', 'projects.id', '=', 'tickets.project_id');
          $tickets->join('users as owners', 'owners.id', '=', 'projects.owner_id');
          $tickets->orderBy('owners.name', $sortObj['order']);
        }
      }
    }

    // filter 
    if (isset($input['filters']) && $input['filters']) {
      foreach ($input['filters'] as $filterObj) {
        // first type of filter
        if ($filterObj['type'] == 'simple') {
          if (in_array($filterObj['name'], ['name', 'number', 'created_at', 'read'])) {
             $tickets->where('tickets.'.$filterObj['name'],'LIKE','%'.$filterObj['text'].'%');
          } elseif ($filterObj['name'] == 'project.name') {
            $tickets->whereHas('project', function($query) use($filterObj) {
              $query->where('name', 'like', '%'.$filterObj['text'].'%');
            });
          } elseif ($filterObj['name'] == 'status.name') {
            $tickets->whereHas('task_status', function($query) use($filterObj) {
              $query->where('name', 'like', '%'.$filterObj['text'].'%');
            });
          } elseif ($filterObj['name'] == 'project.owner.name') {
            $tickets->whereHas('project.owner', function($query) use($filterObj) {
              $query->where('name', 'like', '%'.$filterObj['text'].'%');
            });
          }
        // second type of filter
        } elseif ($filterObj['type'] == 'select') {
          if ($filterObj['name'] == 'status.name') {
            $tickets->whereHas('ticket_status', function($query) use($filterObj) {
              $query->where('name', 'in', $filterObj['selected_options']);
            });
          }
        }
      }
    }


    $tickets->select('tickets.*');
    $tickets->latest();

    $tickets = $tickets->paginate();

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
    } catch (Exception $ex) {
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
    $ticket = Ticket::with('project.owner')->whereHas('project', function ($query) use ($id) {
      $query->where('id', $id);
    })->latest()->paginate();

    if (is_null($ticket)) {
      throw new ItemNotFoundException($id);
    }

    return $this->sendResponse(new TicketCollection($ticket), 'Tickets retrieved successfully.');
  }

  public function getTicketsCountPerClient($clientId)
  {
    $ticketsNumber = Ticket::with('project')->whereHas('project', function ($query)  use ($clientId) {
      $query->where('owner_id', $clientId);
    })->count();

    return $this->sendResponse(['ticketsNumber' => $ticketsNumber], 'Tickets Number retrieved successfully.');
  }

  public function getTicketsPerClient($clientId)
  {
    $tickets = Ticket::with('project')->whereHas('project', function ($query)  use ($clientId) {
      $query->where('owner_id', $clientId);
    })->paginate();
    
    return $this->sendResponse(new TicketCollection($tickets), 'Tickets retrieved successfully.');
  }
}
