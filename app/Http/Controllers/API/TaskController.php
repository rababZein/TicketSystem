<?php 

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Task;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\API\BaseController;

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

    return $this->sendResponse($tasks->toArray(), 'Tasks retrieved successfully.');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'name' => 'required|string',
      'description' => 'required|string',
      'project_id' => 'required|integer|exists:projects,id',
      'ticket_id' => 'nullable|integer|exists:tickets,id',
      'responsible_id' => 'required|integer|exists:users,id',
      'count_hours' => 'nullable|numeric|min:0'
    ]);

    $input = $request->all();
    $input['created_at'] = Carbon::now();
    $input['created_by'] = auth()->user()->id;

    $task = Task::create($input);

    return $this->sendResponse($task->toArray(), 'Task created successfully.');
    
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
        return $this->sendError('task not found.');
    }

    return $this->sendResponse($task->toArray(), 'Task retrieved successfully.');    
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Request $request, $id)
  {
    $this->validate($request, [
      'name' => 'string',
      'description' => 'string',
      'project_id' => 'integer|exists:projects,id',
      'ticket_id' => 'nullable|integer|exists:tickets,id',
      'responsible_id' => 'integer|exists:users,id',
      'count_hours' => 'nullable|numeric|min:0'
    ]);

    $task = Task::find($id);
    
    if (!$task) {
        return $this->sendError('Not found Error.', 'Sorry, task with id ' . $id . ' cannot be found', 400);
    }

    $task->updated_at = Carbon::now();
    $task->updated_by = auth()->user()->id;

    $updated = $task->fill($request->all())->save();

    if (!$updated)
      return $this->sendError('Not update!.', 'Sorry, task could not be updated', 500);

    return $this->sendResponse($task->toArray(), 'task updated successfully.');    
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
      return $this->sendError('task not found.');
    }

    if($task->tracking_history->isNotEmpty()) {
      return $this->sendError('Can\'t delete!, someone work in this task.');
    }

    $task->delete();

    return $this->sendResponse($task->toArray(), 'Task deleted successfully.');
  }

  public function changeStatus(changeStatusRequest $request, $task_id)
  {
    $task = Task::find($id);

    if (is_null($task)) {
      return $this->sendError('task not found.');
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