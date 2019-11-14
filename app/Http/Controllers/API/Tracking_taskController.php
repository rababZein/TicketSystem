<?php 

namespace App\Http\Controllers\API;

use App\Http\Requests\TrackingRequest\AddTrackingRequest;
use App\Http\Requests\TrackingRequest\EditTrackingRequest;
use App\Http\Requests\TrackingRequest\DeleteTrackingRequest;
use App\Http\Requests\TrackingRequest\ListTrackingRequest;
use App\Models\Tracking_task;
use App\Models\Task;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\API\BaseController;
use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;
use App\Exceptions\InvalidDataException;
use App\Exceptions\ItemsNotFoundException;
use App\Exceptions\ItemNotFoundException;
use App\Exceptions\ItemNotDeletedException;
use App\Http\Resources\Tracking\TrackingResource;

class Tracking_taskController extends BaseController 
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('permission:tracking_task-create', ['only' => ['store', 'checkTrackingInProgress', 'tracking']]);
      $this->middleware('permission:tracking_task-edit', ['only' => ['update']]);
      $this->middleware('permission:tracking_task-delete', ['only' => ['destroy']]);
      $this->middleware('permission:tracking_task-list', ['only' => ['getHistory']]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(AddTrackingRequest $request)
  {
    $input = $request->validated();
    
    $tracking_model = new Tracking_task();
    $inprogressTask = $tracking_model->inProgressTracking($input['task_id']);

    if ($inprogressTask){
      throw new InvalidDataException([
        'task' => $inprogressTask->toArray()
      ],
      'There is a tracking task in-progress');
    }

    $task = Task::find($input['task_id']);
    if ($task->status_id == 4) { // done
      throw new InvalidDataException([
        'task' => $inprogressTask
      ],
      'Task is closed');
    } else {
      if ($task->status_id != 3) { //inprogress
        $task->status_id = 3;
        try {
          $task->save();
        } catch (\Throwable $th) {
          throw new ItemNotUpdatedException('Task');
        }
      }
    }

    $input['created_at'] = Carbon::now();
    $input['created_by'] = auth()->user()->id;

    if (! isset($input['start_at'])) {
      $input['start_at'] = Carbon::now();
    }

    if (isset($input['end_at'])){
      $input['count_time'] = Carbon::parse($input['end_at'])->diffInSeconds(Carbon::parse($input['start_at']));
    }

    try {
      $tracking_task = Tracking_task::create($input);
    } catch (\Throwable $th) {
      throw new ItemNotCreatedException('Tracking_task');
    }

    return $this->sendResponse(new TrackingResource($tracking_task), 'Tracking task created successfully.');
  }


  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(EditTrackingRequest $request, $task_id, $tracking_id)
  {
    $tracking_task = Tracking_task::find($tracking_id);

    if (!$tracking_task) {
      throw new ItemNotFoundException($tracking_id);
    }

    $tracking_task->updated_at = Carbon::now();
    $tracking_task->updated_by = auth()->user()->id;

    $input = $request->validated();
    if (isset($input['end_at'])){
      if (isset($input['start_at']))
        $input['count_time'] = Carbon::parse($input['end_at'])->diffInSeconds(Carbon::parse($input['start_at']));
      else
        $input['count_time'] = Carbon::parse($input['end_at'])->diffInSeconds($tracking_task->start_at);
    }

    try {
      $updated = $tracking_task->fill($input)->save();
    } catch (\Throwable $th) {
      throw new ItemNotUpdatedException('Tracking_task');
    }

    if (!$updated)
      throw new ItemNotUpdatedException('Tracking_task');
      
    return $this->sendResponse(new TrackingResource($tracking_task), 'Tracking task updated successfully.');    
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy(DeleteTrackingRequest $request, $task_id, $tracking_id)
  {
    $tracking_task = Tracking_task::find($tracking_id);

    if (is_null($tracking_task)) {
      throw new ItemNotFoundException($tracking_id);
    }

    if($tracking_task->task->receipts->isNotEmpty()) {
      throw new InvalidDataException([
        'task' => $tracking_task->task->toArray()
      ],
      'Can\'t delete!, This task has receipts.');
    }

    try {
      $tracking_task->delete();
    } catch (\Throwable $th) {
      throw new ItemNotDeletedException('Tracking_task');
    }

    return $this->sendResponse(new TrackingResource($tracking_task), 'Tracking task deleted successfully.');
  }

 /**
 * Count Duration for a specfic task.
 *
 * @param  int  $task_id
 * @return Response
 */

public function tracking($task_id)
{
  $task = Task::find($task_id);
  if (!$task)
    throw new ItemNotFoundException($task_id);

  $tracking_model = new Tracking_task();
  $tracking = $tracking_model->tarking($task_id);

  return $this->sendResponse(['tracking' => $tracking], 'Traking task counter retrived successfully.');
}

/**
 * Check there is a tracking in-progress
 *
 * @param  int  $task_id
 * @return Response
 */
public function checkTrackingInProgress($task_id)
{
  $task = Task::find($task_id);
    if (!$task)
      throw new ItemNotFoundException($task_id);
  
  $tracking_model = new Tracking_task();
  $tracking = $tracking_model->inProgressTracking($task_id);
  if (! $tracking)
    throw new ItemsNotFoundException();

  return $this->sendResponse(new TrackingResource($tracking), 'Traking task counter retrived successfully.');
}

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function getHistory(ListTrackingRequest $request, $task_id)
  {
    $task = Task::find($task_id);
    if (!$task)
      throw new ItemNotFoundException($task_id);

    $tracking_model = new Tracking_task();
    $tracking = $tracking_model->history($task_id);
    if (! $tracking)
      throw new ItemsNotFoundException();

    return $this->sendResponse(TrackingResource::collection($tracking), 'Traking History retrieved successfully.');
  }
}

?>