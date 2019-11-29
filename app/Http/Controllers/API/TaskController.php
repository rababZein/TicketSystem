<?php 

namespace App\Http\Controllers\API;

use DB;
use App\Http\Requests\TaskRequest\AddTaskRequest;
use App\Http\Requests\TaskRequest\UpdateTaskRequest;
use App\Http\Requests\TaskRequest\ViewTaskRequest;
use App\Http\Requests\TaskRequest\DeleteTaskRequest;
use App\Http\Requests\TaskRequest\ListTaskRequest;
use App\Models\Task;
use App\Models\User;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\API\BaseController;
use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;
use App\Exceptions\InvalidDataException;
use App\Exceptions\ItemNotFoundException;
use App\Exceptions\ItemNotDeletedException;
use App\Http\Resources\Task\TaskResource;
use App\Http\Resources\Task\TaskCollection;

class TaskController extends BaseController 
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('permission:task-list|task-create|task-edit|task-delete', ['only' => ['index', 'getAll']]);
      $this->middleware('permission:task-create', ['only' => ['store']]);
      $this->middleware('permission:task-edit', ['only' => ['update']]);
      $this->middleware('permission:task-delete', ['only' => ['destroy']]);
      $this->middleware('permission:task-list', ['only' => ['getTaskCountPerClient', 'getTaskPerClient']]);
  }

   /**
   * Display a view listing of the resource view.
   *
   * @return Response
   */
  public function index(ListTaskRequest $request)
  {
    if (auth()->user()->isAdmin()) {
      $tasks = Task::with('project.owner', 'ticket', 'responsible', 'task_status')->latest()->paginate();
    } else {
      $taskModel = new Task();
      $tasks = $taskModel->ownTasks(auth()->user()->id)->latest()->paginate();
    }

    return $this->sendResponse(new TaskCollection($tasks), 'Tasks retrieved successfully.');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(AddTaskRequest $request)
  {
    $input = $request->validated();
    $input['created_at'] = Carbon::now();
    $input['created_by'] = auth()->user()->id;

    try {
      $task = Task::create($input);
    } catch (\Throwable $th) {
      throw new ItemNotCreatedException('Task');
    }

    $task->deadline;

    return $this->sendResponse(new TaskResource(Task::find($task->id)), 'Task created successfully.'); 
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show(ViewTaskRequest $request, $id)
  {
    $task = Task::with('project.owner', 'ticket', 'responsible')->get();
    $task = $task->find($id);

    if (is_null($task)) {
      throw new ItemNotFoundException($id);
    }

    return $this->sendResponse(new TaskResource($task), 'Task retrieved successfully.');    
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(UpdateTaskRequest $request, $id)
  {
    $task = Task::find($id);
    
    if (!$task) {
      throw new ItemNotFoundException($id);
    }

    $task->updated_at = Carbon::now();
    $task->updated_by = auth()->user()->id;

    $input = $request->validated();
    
     try {
      $updated = $task->fill($input)->save();
    } catch (\Throwable $th) {
      throw new ItemNotUpdatedException('Task');
    }

    if (!$updated)
      throw new ItemNotUpdatedException('Task');
  
    return $this->sendResponse(new TaskResource($task), 'task updated successfully.');     
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy(DeleteTaskRequest $request, $id)
  {
    $task = Task::find($id);

    if (is_null($task)) {
      throw new ItemNotFoundException($id);
    }

    if($task->tracking_history->isNotEmpty()) {
      throw new InvalidDataException([
        'tracking_history' => $task->tracking_history->toArray()
      ],
      'Can\'t delete!, someone work in this task.');
    }

    try {
      $task->delete();
    } catch (\Throwable $th) {
      throw new ItemNotDeletedException('Task');
    }

    return $this->sendResponse(new TaskResource($task), 'Task deleted successfully.');
  }

  public function getTasksByTicketId($id, ListTaskRequest $request)
  {
    $tasks = Task::with('project.owner', 'responsible')->whereHas('ticket', function ($query) use ($id) {
      $query->where('id', $id);
    })->latest()->paginate();

    if (is_null($tasks)) {
      throw new ItemNotFoundException($id);
    }

    return $this->sendResponse(new TaskCollection($tasks), 'Tasks retrieved successfully.');
  }

  public function getTasksCountPerClient($clientId)
  {
    $tasksNumber = Task::with('project')->whereHas('project', function ($query)  use ($clientId) {
                      $query->where('owner_id', $clientId);
                  })->select(DB::Raw('status_id, COUNT(*) as count'))
                  ->groupBy('status_id')->get();

    return $this->sendResponse($tasksNumber->toArray(), 'Tasks Number retrieved successfully.');
  }
  
  public function getTasksPerClient($clientId)
  {
    $tasks = Task::with('project')->whereHas('project', function ($query)  use ($clientId) {
                $query->where('owner_id', $clientId);
              })->paginate();

    return $this->sendResponse(new TaskCollection($tasks), 'Tasks retrieved successfully.');
    }
}
