<?php 

namespace App\Http\Controllers\API;

use App\Http\Requests\TaskRequest\AddTaskRequest;
use App\Http\Requests\TaskRequest\UpdateTaskRequest;
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
use App\Http\Resources\TaskResource;
use App\Notifications\Task\TaskAssign;

class TaskController extends BaseController 
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('permission:task-list|task-create|task-edit|task-delete', ['only' => ['index']]);
      $this->middleware('permission:task-create', ['only' => ['store']]);
      $this->middleware('permission:task-edit', ['only' => ['update', 'changeStatus']]);
      $this->middleware('permission:task-delete', ['only' => ['destroy']]);
  }

   /**
   * Display a view listing of the resource view.
   *
   * @return Response
   */
  public function index()
  {
    return view('pages.tasks.index');
  }

  /**
   * Display data listing of the resource.
   *
   * @return Response
   */
  public function getAll()
  {
    $tasks = Task::with('project.owner', 'ticket', 'responsible')->get();

    return $this->sendResponse(TaskResource::collection($tasks), 'Tasks retrieved successfully.');
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

    $responsible = User::find($input['responsible_id']);
    $responsible->notify(new TaskAssign($task));

    return $this->sendResponse(new TaskResource($task), 'Task created successfully.'); 
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
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

    if (isset($input['responsible_id'])) {
      $responsible = User::find($input['responsible_id']);
      $responsible->notify(new TaskAssign($task));
    }
  
    return $this->sendResponse(new TaskResource($task), 'task updated successfully.');     
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
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

  public function changeStatus(changeStatusRequest $request, $task_id)
  {
    $task = Task::find($task_id);

    if (is_null($task)) {
      return ItemNotFoundException($task_id);
    }

    $input = $request->validated();

    try {
      $updated = $tracking_task->fill($input)->save();
    } catch (\Throwable $th) {
      throw new ItemNotUpdatedException('Task');
    }

    if (!$updated)
      throw new ItemNotUpdatedException('Task');

    return $this->sendResponse(new TaskResource($task), 'task updated successfully.');
  }
  
}

?>