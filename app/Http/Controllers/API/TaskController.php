<?php 

namespace App\Http\Controllers\API;

use App\Http\Requests\TaskRequest\AddTaskRequest;
use App\Http\Requests\TaskRequest\UpdateTaskRequest;
use App\Http\Requests\TaskRequest\ViewTaskRequest;
use App\Http\Requests\TaskRequest\DeleteTaskRequest;
use App\Http\Requests\TaskRequest\ListTaskRequest;
use App\Models\Task;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\API\BaseController;
use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;
use App\Exceptions\InvalidDataException;
use App\Exceptions\ItemNotFoundException;
use App\Exceptions\ItemNotDeletedException;

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
  }

   /**
   * Display a view listing of the resource view.
   *
   * @return Response
   */
  public function index(ListTaskRequest $request)
  {
    return view('pages.tasks.index');
  }

  /**
   * Display data listing of the resource.
   *
   * @return Response
   */
  public function getAll(ListTaskRequest $request)
  {
    $tasks = Task::with('project.owner', 'ticket', 'responsible')->get();

    return $this->sendResponse($tasks->toArray(), 'Tasks retrieved successfully.');
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

    return $this->sendResponse($task->toArray(), 'Task created successfully.');
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

    return $this->sendResponse($task->toArray(), 'Task retrieved successfully.');    
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

    try {
      $updated = $task->fill($request->validated())->save();
    } catch (\Throwable $th) {
      throw new ItemNotUpdatedException('Task');
    }

    return $this->sendResponse($task->toArray(), 'task updated successfully.');    
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

    return $this->sendResponse($task->toArray(), 'Task deleted successfully.');
  }
}

?>